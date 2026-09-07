<?php

namespace App\Http\Controllers;

use App\Models\TenderVisitRepresentative;
use App\Tender;
use App\TenderVendor;
use App\TenderVisit;
use App\TenderVisitor;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LawatanTapakUrusetiaController extends Controller
{
    public function __construct()
    {
        $this->menuMiddleware('SiteVisit:list');
    }

    public function index(Request $request)
    {
        $query = Tender::query()
            // Dahulunya diikuti where(lawatan_tapak = 1 OR hasSiteVisits). Cabang
            // kedua itu sama dengan syarat di atas, jadi kumpulan itu sentiasa
            // benar dan syarat lawatan_tapak tidak pernah menapis apa-apa. Ia
            // hanya menambah satu subkueri EXISTS berkorelasi bagi setiap baris
            // pada halaman yang sedang tamat masa. Disahkan pada pangkalan data:
            // set tender yang dipadankan tidak berubah tanpanya.
            ->whereHas('siteVisits')
            ->where(function ($q) {
                $q->whereHas('participants', function ($participantQuery) {
                    $participantQuery->where('participate', 1)->whereNotNull('ref_number');
                })->orWhereExists(function ($existsQuery) {
                    $existsQuery->select(DB::raw(1))
                        ->from('tender_visit_representatives as tvr')
                        ->join('tender_visits as tv', 'tv.id', '=', 'tvr.visit_id')
                        ->whereColumn('tv.tender_id', 'tenders.id');
                })->orWhereExists(function ($existsQuery) {
                    $existsQuery->select(DB::raw(1))
                        ->from('tender_visitors as tvs')
                        ->join('tender_visits as tv', 'tv.id', '=', 'tvs.visit_id')
                        ->whereColumn('tv.tender_id', 'tenders.id');
                });
            })
            ->with(['tenderer', 'siteVisits'])
            ->withCount([
                'participants as purchases_count' => function ($q) {
                    $q->where('participate', 1)->whereNotNull('ref_number');
                },
            ]);

        $user = auth()->user();
        if ($user && !$user->hasRole('Admin') && $user->organization_unit_id) {
            $query->where('organization_unit_id', $user->organization_unit_id);
        }

        if ($request->filled('no_tender')) {
            $term = $request->no_tender;
            $query->where(function ($q) use ($term) {
                $q->where('ref_number', 'like', "%{$term}%")
                    ->orWhere('no_tender', 'like', "%{$term}%");
            });
        }

        if ($request->filled('tajuk')) {
            $query->where('name', 'like', '%' . $request->tajuk . '%');
        }

        if ($request->filled('tarikh')) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tarikh)->format('Y-m-d');
                $query->whereDate('document_start_date', '<=', $date)
                    ->whereDate('document_stop_date', '>=', $date);
            } catch (\Exception $e) {
                // ignore invalid date
            }
        }

        $tenders = $query->orderByDesc('id')->get();

        // Status lawatan dahulunya dikira per tender, dan setiap pengiraan itu
        // menjalankan pertanyaannya sendiri - termasuk satu pertanyaan bagi
        // SETIAP pasangan vendor x lawatan. Pada senarai yang tidak berhalaman
        // ini jumlahnya menjadi ribuan pertanyaan, dan halaman ini 504.
        // Data itu kini dimuatkan sekali untuk semua tender, dan status
        // dikira dalam memori.
        $index = $this->loadLawatanStatusIndex($tenders);

        $tenders = $tenders->map(function (Tender $tender) use ($index) {
            $tender->lawatan_status = $this->resolveTenderLawatanStatus($tender, $index);
            return $tender;
        });

        if ($request->status === 'belum_disiarkan') {
            $tenders = $tenders->filter(fn ($t) => $t->lawatan_status['key'] !== 'selesai');
        }

        return view('newModule.lawatanTapak.index', compact('tenders'));
    }

    public function pengesahan($tenderId)
    {
        $tender = $this->findTenderForUrusetia($tenderId);
        $purchases = $this->purchasedVendors($tender);
        $visits = $tender->siteVisits->sortBy('id')->values();

        $attendanceRows = $this->buildAttendanceRows($tender, $purchases, $visits);

        return view('newModule.lawatanTapak.pengesahanLawatanTapak', compact(
            'tender',
            'purchases',
            'visits',
            'attendanceRows'
        ));
    }

    public function updatePengesahan(Request $request, $tenderId)
    {
        $tender = $this->findTenderForUrusetia($tenderId);

        $data = $request->validate([
            'rows' => 'array',
            'rows.*.visit_id' => 'required|integer',
            'rows.*.vendor_id' => 'nullable|integer',
            'rows.*.vendor_registration' => 'nullable|string|max:64',
            'rows.*.rep_id' => 'nullable|integer',
            'rows.*.ic_no' => 'nullable|string|max:32',
            'rows.*.name' => 'nullable|string|max:255',
            'rows.*.attended' => 'nullable|boolean',
        ]);

        $visitIds = $tender->siteVisits()->pluck('id')->all();

        DB::transaction(function () use ($data, $visitIds) {
            $touched = [];

            foreach ($data['rows'] ?? [] as $row) {
                $visitId = (int) $row['visit_id'];
                $vendorId = (int) ($row['vendor_id'] ?? 0);

                if (!in_array($visitId, $visitIds, true)) {
                    continue;
                }

                if ($vendorId <= 0 && !empty($row['vendor_registration'])) {
                    $vendorId = (int) (Vendor::query()
                        ->where('registration', trim($row['vendor_registration']))
                        ->value('id') ?? 0);
                }

                if ($vendorId <= 0 || !Vendor::where('id', $vendorId)->exists()) {
                    continue;
                }

                $ic = trim($row['ic_no'] ?? '');
                $name = trim($row['name'] ?? '');
                $attended = !empty($row['attended']);
                $touched["{$visitId}-{$vendorId}"] = true;

                if (empty($ic) && empty($name) && empty($row['rep_id'])) {
                    if ($attended) {
                        TenderVisitRepresentative::create([
                            'visit_id' => $visitId,
                            'vendor_id' => $vendorId,
                            'ic_no' => null,
                            'name' => null,
                            'attended' => true,
                        ]);
                    }
                    continue;
                }

                if (!empty($row['rep_id'])) {
                    $rep = TenderVisitRepresentative::where('id', $row['rep_id'])
                        ->where('visit_id', $visitId)
                        ->where('vendor_id', $vendorId)
                        ->first();

                    if ($rep) {
                        $rep->update([
                            'ic_no' => $ic ?: $rep->ic_no,
                            'name' => $name ?: $rep->name,
                            'attended' => $attended,
                        ]);
                    }
                } elseif ($ic || $name) {
                    TenderVisitRepresentative::create([
                        'visit_id' => $visitId,
                        'vendor_id' => $vendorId,
                        'ic_no' => $ic ?: null,
                        'name' => $name ?: null,
                        'attended' => $attended,
                    ]);
                }
            }

            foreach ($touched as $key => $_) {
                [$visitId, $vendorId] = explode('-', $key);
                $visitId = (int) $visitId;
                $vendorId = (int) $vendorId;

                $hasAttended = TenderVisitRepresentative::where('visit_id', $visitId)
                    ->where('vendor_id', $vendorId)
                    ->where('attended', 1)
                    ->exists();

                if ($hasAttended) {
                    if (!TenderVisitor::hasVisit($visitId, $vendorId)) {
                        TenderVisitor::create([
                            'visit_id' => $visitId,
                            'vendor_id' => $vendorId,
                        ]);
                    }
                } else {
                    TenderVisitor::where('visit_id', $visitId)
                        ->where('vendor_id', $vendorId)
                        ->delete();
                }
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kehadiran lawatan tapak berjaya dikemaskini.']);
        }

        return redirect()
            ->route('pengesahanLawatanTapak', $tender->id)
            ->with('success', 'Kehadiran lawatan tapak berjaya dikemaskini.');
    }

    public function kelulusan($tenderId)
    {
        $tender = $this->findTenderForUrusetia($tenderId);
        $purchases = $this->purchasedVendors($tender);
        $visits = $tender->siteVisits->sortBy('id')->values();
        $attendanceRows = $this->buildAttendanceRows($tender, $purchases, $visits);

        return view('newModule.lawatanTapak.kelulusanLawatanTapak', compact(
            'tender',
            'purchases',
            'visits',
            'attendanceRows'
        ));
    }

    protected function findTenderForUrusetia($tenderId): Tender
    {
        $user = auth()->user();
        if (!$user || !$user->ability(['Admin', 'Agency Admin', 'Agency User', 'Agency Urusetia'], [])) {
            abort(403);
        }

        $tender = Tender::with(['tenderer', 'siteVisits'])->findOrFail($tenderId);

        if (!$user->hasRole('Admin') && $user->organization_unit_id != $tender->organization_unit_id) {
            abort(403);
        }

        if ($tender->siteVisits->isEmpty()) {
            abort(404, 'Tender ini tiada lawatan tapak.');
        }

        return $tender;
    }

    protected function purchasedVendors(Tender $tender)
    {
        return $tender->participants()
            ->where('participate', 1)
            ->whereNotNull('ref_number')
            ->with('vendor')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @return array<int, int>
     */
    protected function attendanceVendorIds(Tender $tender, $purchases, $visits): array
    {
        $visitIds = $visits->pluck('id')->all();

        $repVendorIds = TenderVisitRepresentative::query()
            ->whereIn('visit_id', $visitIds)
            ->pluck('vendor_id');

        $visitorVendorIds = TenderVisitor::query()
            ->whereIn('visit_id', $visitIds)
            ->pluck('vendor_id');

        return collect($purchases->pluck('vendor_id'))
            ->merge($repVendorIds)
            ->merge($visitorVendorIds)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function buildAttendanceRows(Tender $tender, $purchases, $visits): array
    {
        $rows = [];
        $vendorIds = $this->attendanceVendorIds($tender, $purchases, $visits);
        $vendors = Vendor::query()->whereIn('id', $vendorIds)->get()->keyBy('id');
        $purchasesByVendor = $purchases->keyBy('vendor_id');

        foreach ($visits as $visit) {
            foreach ($vendorIds as $vendorId) {
                $vendor = $vendors->get($vendorId);
                if (!$vendor) {
                    continue;
                }

                $purchase = $purchasesByVendor->get($vendorId);

                $reps = TenderVisitRepresentative::where('visit_id', $visit->id)
                    ->where('vendor_id', $vendor->id)
                    ->orderBy('id')
                    ->get();

                if ($reps->isEmpty()) {
                    $rows[] = [
                        'visit' => $visit,
                        'purchase' => $purchase,
                        'vendor' => $vendor,
                        'rep' => null,
                        'attended' => TenderVisitor::hasVisit($visit->id, $vendor->id),
                    ];
                    continue;
                }

                foreach ($reps as $rep) {
                    $rows[] = [
                        'visit' => $visit,
                        'purchase' => $purchase,
                        'vendor' => $vendor,
                        'rep' => $rep,
                        'attended' => (bool) $rep->attended || TenderVisitor::hasVisit($visit->id, $vendor->id),
                    ];
                }
            }
        }

        return $rows;
    }

    /**
     * Ringkaskan status lawatan bagi semua tender dalam beberapa pertanyaan
     * teragregat, satu baris hasil per tender.
     *
     * Percubaan sebelum ini memuatkan setiap baris wakil dan pelawat ke dalam
     * PHP dan menghabiskan memory_limit di staging. Pengiraan dibuat dalam SQL
     * di sini, jadi penggunaan memori bergantung pada bilangan tender yang
     * dipaparkan sahaja, bukan pada bilangan rekod kehadiran.
     *
     * @param  \Illuminate\Support\Collection  $tenders
     * @return array{tracked: array, reps: array, pairs: array, required: array}
     */
    private function loadLawatanStatusIndex($tenders): array
    {
        $tenderIds = $tenders->pluck('id')->filter()->unique()->values()->all();

        // Lawatan "required" ditentukan per tender: yang bertanda required, atau
        // semua lawatan apabila tiada satu pun ditanda. siteVisits sudah dimuat
        // awal, jadi ini tidak menambah pertanyaan.
        $required = [];

        foreach ($tenders as $tender) {
            $visits = $tender->siteVisits;
            $chosen = $visits->where('required', 1);

            if ($chosen->isEmpty()) {
                $chosen = $visits;
            }

            $required[(int) $tender->id] = $chosen->pluck('id')->map(fn ($id) => (int) $id)->all();
        }

        $requiredVisitIds = collect($required)->flatten()->unique()->values()->all();

        $tracked = [];
        $reps = [];
        $pairs = [];

        if ($tenderIds !== []) {
            // Vendor "tracked" ialah gabungan pembeli, wakil dan pelawat. Hanya
            // bilangan unik diperlukan, jadi ia dikira dalam SQL.
            $pembeli = DB::table('tender_vendors')
                ->select('tender_id', 'vendor_id')
                ->whereIn('tender_id', $tenderIds)
                ->where('participate', 1)
                ->whereNotNull('ref_number')
                ->where('vendor_id', '>', 0);

            $wakil = DB::table('tender_visit_representatives as tvr')
                ->join('tender_visits as tv', 'tv.id', '=', 'tvr.visit_id')
                ->select('tv.tender_id', 'tvr.vendor_id')
                ->whereIn('tv.tender_id', $tenderIds)
                ->where('tvr.vendor_id', '>', 0);

            $pelawat = DB::table('tender_visitors as tvs')
                ->join('tender_visits as tv', 'tv.id', '=', 'tvs.visit_id')
                ->select('tv.tender_id', 'tvs.vendor_id')
                ->whereIn('tv.tender_id', $tenderIds)
                ->where('tvs.vendor_id', '>', 0);

            $tracked = DB::query()
                ->fromSub($pembeli->union($wakil)->union($pelawat), 'x')
                ->select('tender_id', DB::raw('COUNT(DISTINCT vendor_id) as n'))
                ->groupBy('tender_id')
                ->pluck('n', 'tender_id')
                ->map(fn ($n) => (int) $n)
                ->all();
        }

        if ($requiredVisitIds !== []) {
            // Setiap wakil pada lawatan required semestinya sudah termasuk dalam
            // set tracked (tracked merangkumi wakil bagi semua lawatan tender
            // itu), jadi penapis "vendor mesti tracked" dalam kod asal tidak
            // pernah menyingkirkan apa-apa dan tidak diulang di sini.
            $reps = DB::table('tender_visit_representatives as tvr')
                ->join('tender_visits as tv', 'tv.id', '=', 'tvr.visit_id')
                ->whereIn('tvr.visit_id', $requiredVisitIds)
                ->select('tv.tender_id', DB::raw('COUNT(*) as n'))
                ->groupBy('tv.tender_id')
                ->pluck('n', 'tender_id')
                ->map(fn ($n) => (int) $n)
                ->all();

            // Pasangan (vendor, lawatan) yang mempunyai TEPAT satu rekod hadir.
            // Semakan asal TenderVisitor::hasVisit() menuntut tepat satu baris,
            // jadi rekod pendua dikira belum disemak; HAVING mengekalkannya.
            $sub = DB::table('tender_visitors as tvs')
                ->join('tender_visits as tv', 'tv.id', '=', 'tvs.visit_id')
                ->whereIn('tvs.visit_id', $requiredVisitIds)
                ->where('tvs.vendor_id', '>', 0)
                ->select('tv.tender_id as tender_id', 'tvs.visit_id', 'tvs.vendor_id', DB::raw('COUNT(*) as c'))
                ->groupBy('tv.tender_id', 'tvs.visit_id', 'tvs.vendor_id')
                ->having('c', '=', 1);

            $pairs = DB::query()
                ->fromSub($sub, 'y')
                ->select('tender_id', DB::raw('COUNT(*) as n'))
                ->groupBy('tender_id')
                ->pluck('n', 'tender_id')
                ->map(fn ($n) => (int) $n)
                ->all();
        }

        return compact('tracked', 'reps', 'pairs', 'required');
    }

    /**
     * @param  array{tracked: array, reps: array, pairs: array, required: array}  $index
     */
    protected function resolveTenderLawatanStatus(Tender $tender, array $index): array
    {
        $tenderId = (int) $tender->id;

        $trackedCount = $index['tracked'][$tenderId] ?? 0;

        if ($trackedCount === 0) {
            return ['key' => 'tiada_pembeli', 'label' => 'Tiada Rekod', 'class' => 'bg-secondary'];
        }

        if (($index['reps'][$tenderId] ?? 0) === 0) {
            return ['key' => 'menunggu_wakil', 'label' => 'Menunggu Wakil', 'class' => 'bg-warning text-dark'];
        }

        // Selesai hanya apabila SETIAP vendor tracked mempunyai tepat satu rekod
        // hadir pada SETIAP lawatan required — iaitu bilangan pasangan sah sama
        // dengan bilangan vendor didarab bilangan lawatan. Vendor yang langsung
        // tiada rekod menyumbang sifar pasangan, jadi ia gagal syarat ini sama
        // seperti gelung bersarang yang asal.
        $requiredCount = count($index['required'][$tenderId] ?? []);
        $pairsOk       = $index['pairs'][$tenderId] ?? 0;

        if ($pairsOk !== $trackedCount * $requiredCount) {
            return ['key' => 'belum_disemak', 'label' => 'Belum Disemak', 'class' => 'bg-warning text-dark'];
        }

        return ['key' => 'selesai', 'label' => 'Selesai', 'class' => 'bg-success'];
    }
}
