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
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->ability(['Admin', 'Agency Admin', 'Agency User'], [])) {
            return $this->_access_denied();
        }

        $query = Tender::query()
            ->whereHas('siteVisits')
            ->where(function ($q) {
                $q->where('lawatan_tapak', 1)
                    ->orWhereHas('siteVisits');
            })
            ->whereHas('participants', function ($q) {
                $q->where('participate', 1)->whereNotNull('ref_number');
            })
            ->with(['tenderer', 'siteVisits'])
            ->withCount([
                'participants as purchases_count' => function ($q) {
                    $q->where('participate', 1)->whereNotNull('ref_number');
                },
            ]);

        if (!$user->hasRole('Admin') && $user->organization_unit_id) {
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

        $tenders = $query->orderByDesc('id')->get()->map(function (Tender $tender) {
            $tender->lawatan_status = $this->resolveTenderLawatanStatus($tender);
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
            'rows.*.vendor_id' => 'required|integer',
            'rows.*.rep_id' => 'nullable|integer',
            'rows.*.ic_no' => 'nullable|string|max:32',
            'rows.*.name' => 'nullable|string|max:255',
            'rows.*.attended' => 'nullable|boolean',
        ]);

        $visitIds = $tender->siteVisits()->pluck('id')->all();
        $purchaseVendorIds = $this->purchasedVendors($tender)->pluck('vendor_id')->all();

        DB::transaction(function () use ($data, $visitIds, $purchaseVendorIds) {
            $touched = [];

            foreach ($data['rows'] ?? [] as $row) {
                $visitId = (int) $row['visit_id'];
                $vendorId = (int) $row['vendor_id'];

                if (!in_array($visitId, $visitIds, true) || !in_array($vendorId, $purchaseVendorIds, true)) {
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
        if (!$user || !$user->ability(['Admin', 'Agency Admin', 'Agency User'], [])) {
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

    protected function buildAttendanceRows(Tender $tender, $purchases, $visits): array
    {
        $rows = [];

        foreach ($visits as $visit) {
            foreach ($purchases as $purchase) {
                $vendor = $purchase->vendor;
                if (!$vendor) {
                    continue;
                }

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

    protected function resolveTenderLawatanStatus(Tender $tender): array
    {
        $purchases = TenderVendor::where('tender_id', $tender->id)
            ->where('participate', 1)
            ->whereNotNull('ref_number')
            ->pluck('vendor_id');

        if ($purchases->isEmpty()) {
            return ['key' => 'tiada_pembeli', 'label' => 'Tiada Pembeli', 'class' => 'bg-secondary'];
        }

        $requiredVisits = $tender->siteVisits->where('required', 1);
        if ($requiredVisits->isEmpty()) {
            $requiredVisits = $tender->siteVisits;
        }

        $hasReps = TenderVisitRepresentative::whereIn('visit_id', $requiredVisits->pluck('id'))
            ->whereIn('vendor_id', $purchases)
            ->exists();

        if (!$hasReps) {
            return ['key' => 'menunggu_wakil', 'label' => 'Menunggu Wakil', 'class' => 'bg-warning text-dark'];
        }

        foreach ($purchases as $vendorId) {
            foreach ($requiredVisits as $visit) {
                if (!TenderVisitor::hasVisit($visit->id, $vendorId)) {
                    return ['key' => 'belum_disemak', 'label' => 'Belum Disemak', 'class' => 'bg-warning text-dark'];
                }
            }
        }

        return ['key' => 'selesai', 'label' => 'Selesai', 'class' => 'bg-success'];
    }
}
