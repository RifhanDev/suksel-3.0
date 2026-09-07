<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\RestrictsTenderByRole;
use App\Models\EbiddingJadualBidaan;
use App\Models\JawatankuasaPerolehanKertasKeputusan;
use App\Models\JawatankuasaPerolehanMeeting;
use App\Models\JawatankuasaPerolehanPemilihanHeader;
use App\Models\JawatankuasaPerolehanPemilihanItem;
use App\Models\JawatankuasaPerolehanPemilihanPetender;
use App\Models\PerakuanJabatanKertasTaklimatItem;
use App\Models\PerakuanJabatanPengesyoranPembekal;
use App\Models\PerakuanJabatanPengesyoranPembekalItem;
use App\Models\Ref\RefJustifikasiPemilihanPembekal;
use App\Models\TenderTeknikalSpesifikasiEvaluation;
use App\Services\StosBackendClient;
use App\Services\TenderProcessStatusService;
use App\Support\TenderProcessStatus;
use App\Support\VendorCidbMeta;
use App\Tender;
use App\TenderVendor;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JawatankuasaPerolehanController extends Controller
{
    use AdvancesTenderProcessStatus;
    use RestrictsTenderByRole;

    public function __construct()
    {
        $this->menuMiddleware('MeetingDecision:list');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = $this->applyLembagaDecisionScope(
            Tender::query()->where('status_process_id', TenderProcessStatus::jawatankuasaPerolehanListStatus())
        )
            ->orderByDesc('id')
            ->get([
                'id',
                'uuid',
                'no_tender',
                'ref_number',
                'name',
                'submission_datetime',
                'status_process_id',
            ])
            ->map(function ($tender) {
                $submissionDate = null;
                $tempohSahLaku = '-';

                if (!empty($tender->submission_datetime)) {
                    $submissionDate = Carbon::parse($tender->submission_datetime);
                    $tempohSahLaku = '90 Hari (Sehingga ' . $submissionDate->copy()->addDays(90)->format('d/m/Y') . ')';
                }

                return [
                    'id' => $tender->id,
                    'uuid' => $tender->uuid,
                    'no_tender' => $tender->no_tender ?: $tender->ref_number ?: '-',
                    'tajuk' => $tender->name ?: '-',
                    'tarikh_serahan' => $submissionDate ? $submissionDate->format('d/m/Y') : '-',
                    'tempoh_sah_laku' => $tempohSahLaku,
                    'status_label' => 'Dalam Proses',
                ];
            })
            ->values();

        return view('newModule.jawatankuasaPerolehan.index', compact('tenders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function form()
    {
        $tender = $this->resolveTender(request('tender'));
        $meetings = collect();
        $taklimatAttachments = collect();
        $kertasKeputusan = null;
        $pemilihanHeader = $this->blankPemilihanHeader();
        $pemilihanItems = collect();
        $pemilihanOpts = $this->pemilihanDropdownOptions(null);
        $pemilihanVendors = collect();
        $kkJustifikasiOptions = $this->justifikasiPemilihanPembekalOptions();

        if ($tender) {
            $meetings = JawatankuasaPerolehanMeeting::query()
                ->where('tender_id', $tender->id)
                ->orderBy('id')
                ->get()
                ->map(function ($meeting) {
                    return [
                        'id' => $meeting->id,
                        'bil_mesyuarat' => $meeting->bil_mesyuarat ?? '',
                        'tarikh_mesyuarat' => optional($meeting->tarikh_mesyuarat)->format('Y-m-d'),
                        'masa' => $meeting->masa ?? '',
                        'tajuk_agenda' => $meeting->tajuk_agenda ?? '',
                        'tempat' => $meeting->tempat ?? '',
                        'no_kod_kertas' => $meeting->no_kod_kertas ?? '',
                        'status' => $meeting->status ?? 'Belum Selesai',
                        'catatan' => $meeting->catatan ?? '',
                    ];
                })
                ->values();

            $taklimatItems = PerakuanJabatanKertasTaklimatItem::query()
                ->whereHas('header', function ($query) use ($tender) {
                    $query->where('tender_id', $tender->id);
                })
                ->with('files')
                ->orderBy('sort_order')
                ->get();

            $taklimatAttachments = $taklimatItems
                ->flatMap(function ($item) use ($tender) {
                    $rows = $item->files->map(function ($file) use ($item) {
                        return [
                            'kandungan' => $item->kandungan,
                            'file_name' => $file->file_original_name,
                            // Papar sahaja: buka fail terus (bukan muat turun paksa)
                            'papar_url' => $file->file_path ? asset($file->file_path) : '#',
                        ];
                    })->all();

                    // Generated at Perakuan Jabatan (not an uploaded file).
                    if ($item->slot_key === 'teknikal') {
                        $rows[] = [
                            'kandungan' => $item->kandungan ?: 'Laporan Jawatankuasa Teknikal',
                            'file_name' => 'Laporan Jawatankuasa Penilaian Teknikal',
                            'papar_url' => route('jawatankuasa.perolehan.laporanTeknikal', $tender),
                        ];
                    }

                    if ($item->slot_key === 'kewangan') {
                        $rows[] = [
                            'kandungan' => $item->kandungan ?: 'Laporan Jawatankuasa Kewangan',
                            'file_name' => 'Laporan Jawatankuasa Penilaian Kewangan',
                            'papar_url' => route('jawatankuasa.perolehan.laporanKewangan', $tender),
                        ];
                    }

                    return $rows;
                })
                ->values();

            // Fallback if PJ header/items were never seeded but reports still exist.
            if (! $taklimatItems->contains(fn ($item) => $item->slot_key === 'teknikal')) {
                $taklimatAttachments->prepend([
                    'kandungan' => 'Laporan Jawatankuasa Teknikal',
                    'file_name' => 'Laporan Jawatankuasa Penilaian Teknikal',
                    'papar_url' => route('jawatankuasa.perolehan.laporanTeknikal', $tender),
                ]);
            }

            if (! $taklimatItems->contains(fn ($item) => $item->slot_key === 'kewangan')) {
                $taklimatAttachments->push([
                    'kandungan' => 'Laporan Jawatankuasa Kewangan',
                    'file_name' => 'Laporan Jawatankuasa Penilaian Kewangan',
                    'papar_url' => route('jawatankuasa.perolehan.laporanKewangan', $tender),
                ]);
            }

            $kertasKeputusan = JawatankuasaPerolehanKertasKeputusan::query()
                ->where('tender_id', $tender->id)
                ->first();

            $this->ensurePemilihanDefaults($tender);
            $this->syncPemilihanFromSources($tender);
            $pemilihanOpts = $this->pemilihanDropdownOptions($tender);
            $headerModel = JawatankuasaPerolehanPemilihanHeader::query()
                ->where('tender_id', $tender->id)
                ->first();
            if ($headerModel) {
                $pemilihanHeader = [
                    'keputusan_mesyuarat' => (string) ($headerModel->keputusan_mesyuarat ?? ''),
                    'kaedah_memuktamadkan_pembekal' => (string) ($headerModel->kaedah_memuktamadkan_pembekal ?? ''),
                    'pemilihan_berdasarkan' => (string) ($headerModel->pemilihan_berdasarkan ?? ''),
                    'loi_loa_disediakan_oleh' => (string) ($headerModel->loi_loa_disediakan_oleh ?? ''),
                    'bil_mesyuarat' => (string) ($headerModel->bil_mesyuarat ?? ''),
                    'no_kod' => (string) ($headerModel->no_kod ?? ''),
                    'sahkan_layak_bidaan' => (bool) $headerModel->sahkan_layak_bidaan,
                ];

                // If Bidaan was already run, clear stale selection so urusetia picks Terus/Lebih.
                if (
                    $this->bidaanAlreadyRun($tender)
                    && ($pemilihanHeader['kaedah_memuktamadkan_pembekal'] ?? '') === 'Bidaan'
                ) {
                    $pemilihanHeader['kaedah_memuktamadkan_pembekal'] = '';
                }
            }

            $pemilihanItems = JawatankuasaPerolehanPemilihanItem::query()
                ->where('tender_id', $tender->id)
                ->with(['petenders' => function ($q) {
                    $q->orderBy('sort_order')->with('vendor:id,name,meta');
                }])
                ->orderBy('sort_order')
                ->get()
                ->map(function (JawatankuasaPerolehanPemilihanItem $item) {
                    return [
                        'id' => $item->id,
                        'perihal_item' => $item->perihal_item,
                        'jenis_item' => $item->jenis_item,
                        'unit_ukuran' => $item->unit_ukuran,
                        'jenis_harga' => $item->jenis_harga,
                        'dibatalkan' => $item->dibatalkan,
                        'pembekal_dipilih' => (int) $item->pembekal_dipilih,
                        'kuantiti' => (string) $item->kuantiti,
                        'petenders' => $item->petenders->map(function (JawatankuasaPerolehanPemilihanPetender $p) {
                            $hasCidbMeta = $p->vendor_id
                                && is_array(VendorCidbMeta::normalizeMeta(is_array($p->vendor?->meta) ? $p->vendor->meta : null));

                            return [
                                'id' => $p->id,
                                'vendor_id' => $p->vendor_id,
                                'cidb_has_meta' => $hasCidbMeta,
                                'bil_label' => $p->bil_label,
                                'status_bumiputra' => $p->status_bumiputra ?: '',
                                'harga_tawaran' => (string) ($p->harga_tawaran ?? 0),
                                'jumlah_skor' => $p->jumlah_skor !== null ? (string) $p->jumlah_skor : '',
                                'kedudukan_penilaian' => $p->kedudukan_penilaian,
                                'status_mof' => $p->status_mof ?: '',
                                'tindakan_disiplin' => null,
                                'lembaga_pengarah_papar_url' => null,
                                'keputusan_urusetia' => $p->keputusan_urusetia ?: '',
                                'catatan_urusetia' => $p->catatan_urusetia ?: '',
                            ];
                        })->values(),
                    ];
                })
                ->values();

            $vendorIds = $pemilihanItems
                ->flatMap(fn(array $item) => collect($item['petenders'])->pluck('vendor_id'))
                ->filter()
                ->unique()
                ->values();

            $pemilihanVendors = $vendorIds->isEmpty()
                ? collect()
                : Vendor::query()->whereIn('id', $vendorIds)->get(['id', 'name', 'meta']);
        }

        return view('newModule.jawatankuasaPerolehan.form', compact(
            'tender',
            'meetings',
            'taklimatAttachments',
            'kertasKeputusan',
            'pemilihanHeader',
            'pemilihanItems',
            'pemilihanOpts',
            'pemilihanVendors',
            'kkJustifikasiOptions',
        ));
    }

    /** Proxy printable teknikal report for JP Paparan Kertas Taklimat (MeetingDecision role). */
    public function muatTurunLaporanTeknikal(Tender $tender)
    {
        return app(PenilaianTeknikalController::class)->cetakLaporan($tender);
    }

    /** Proxy printable kewangan report for JP Paparan Kertas Taklimat (MeetingDecision role). */
    public function muatTurunLaporanKewangan(Tender $tender)
    {
        $identifier = $tender->uuid ?: ($tender->no_tender ?: ($tender->ref_number ?: (string) $tender->id));

        return app(PenilaianKewanganController::class)->cetakLaporan($identifier);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('newModule.jawatankuasaPerolehan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));

        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.bil_mesyuarat' => ['required', 'string', 'max:100'],
            'rows.*.tarikh_mesyuarat' => ['required', 'date'],
            'rows.*.masa' => ['required', 'string', 'max:10'],
            'rows.*.tajuk_agenda' => ['required', 'string', 'max:255'],
            'rows.*.tempat' => ['required', 'string', 'max:255'],
            'rows.*.no_kod_kertas' => ['required', 'string', 'max:100'],
            'rows.*.status' => ['required', 'in:Belum Selesai,Selesai'],
            'rows.*.catatan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($tender, $payload) {
            JawatankuasaPerolehanMeeting::query()
                ->where('tender_id', $tender->id)
                ->delete();

            foreach ($payload['rows'] as $row) {
                JawatankuasaPerolehanMeeting::query()->create([
                    'tender_id' => $tender->id,
                    'bil_mesyuarat' => trim($row['bil_mesyuarat']),
                    'tarikh_mesyuarat' => $row['tarikh_mesyuarat'],
                    'masa' => trim($row['masa']),
                    'tajuk_agenda' => trim($row['tajuk_agenda']),
                    'tempat' => trim($row['tempat']),
                    'no_kod_kertas' => trim($row['no_kod_kertas']),
                    'status' => $row['status'],
                    'catatan' => isset($row['catatan']) ? trim((string) $row['catatan']) : null,
                    'submitted_at' => null,
                ]);
            }
        });

        return response()->json([
            'message' => 'Perincian mesyuarat berjaya disimpan.',
        ]);
    }

    public function hantar(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));

        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.bil_mesyuarat' => ['required', 'string', 'max:100'],
            'rows.*.tarikh_mesyuarat' => ['required', 'date'],
            'rows.*.masa' => ['required', 'string', 'max:10'],
            'rows.*.tajuk_agenda' => ['required', 'string', 'max:255'],
            'rows.*.tempat' => ['required', 'string', 'max:255'],
            'rows.*.no_kod_kertas' => ['required', 'string', 'max:100'],
            'rows.*.status' => ['required', 'in:Belum Selesai,Selesai'],
            'rows.*.catatan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($tender, $payload) {
            JawatankuasaPerolehanMeeting::query()
                ->where('tender_id', $tender->id)
                ->delete();

            $now = now();
            foreach ($payload['rows'] as $row) {
                JawatankuasaPerolehanMeeting::query()->create([
                    'tender_id' => $tender->id,
                    'bil_mesyuarat' => trim($row['bil_mesyuarat']),
                    'tarikh_mesyuarat' => $row['tarikh_mesyuarat'],
                    'masa' => trim($row['masa']),
                    'tajuk_agenda' => trim($row['tajuk_agenda']),
                    'tempat' => trim($row['tempat']),
                    'no_kod_kertas' => trim($row['no_kod_kertas']),
                    'status' => $row['status'],
                    'catatan' => isset($row['catatan']) ? trim((string) $row['catatan']) : null,
                    'submitted_at' => $now,
                ]);
            }
        });

        return response()->json([
            'message' => 'Perincian mesyuarat berjaya dihantar.',
        ]);
    }

    public function simpanKertasKeputusan(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'dengan_syarat' => ['nullable', 'in:0,1'],
            'syarat_nyatakan' => ['nullable', 'string', 'max:65535'],
            'pengesyoran_catatan' => ['nullable', 'string', 'max:65535'],
            'justifikasi_pemilihan_pembekal' => [
                'nullable',
                'string',
                'max:255',
                Rule::in(array_merge([''], $this->justifikasiPemilihanPembekalOptions())),
            ],
            'keputusan' => ['nullable', 'in:Lulus,Tawaran Semula,Batal,Tangguh'],
            'catatan' => ['nullable', 'string', 'max:65535'],
            'lampiran' => ['nullable', 'file', 'max:10240'],
            'buang_lampiran' => ['nullable', 'in:0,1'],
        ]);

        $record = JawatankuasaPerolehanKertasKeputusan::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['submitted_at' => null]
        );

        DB::transaction(function () use ($request, $payload, $record, $tender) {
            $this->applyKertasKeputusanData($request, $payload, $record, $tender, false);
            $record->save();
        });

        return response()->json(['message' => 'Kertas keputusan berjaya disimpan.']);
    }

    public function hantarKertasKeputusan(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $request->validate([
            'dengan_syarat' => ['required', 'in:0,1'],
            'syarat_nyatakan' => ['nullable', 'string', 'max:65535'],
            'pengesyoran_catatan' => ['required', 'string', 'max:65535'],
            'justifikasi_pemilihan_pembekal' => [
                'required',
                'string',
                'max:255',
                Rule::in($this->justifikasiPemilihanPembekalOptions()),
            ],
            'keputusan' => ['required', 'in:Lulus,Tawaran Semula,Batal,Tangguh'],
            'catatan' => ['nullable', 'string', 'max:65535'],
            'lampiran' => ['nullable', 'file', 'max:10240'],
            'buang_lampiran' => ['nullable', 'in:0,1'],
        ], [
            'pengesyoran_catatan.required' => 'Ruangan Pengesyoran adalah wajib.',
            'justifikasi_pemilihan_pembekal.required' => 'Justifikasi Pemilihan Pembekal adalah wajib.',
            'justifikasi_pemilihan_pembekal.in' => 'Justifikasi Pemilihan Pembekal tidak sah.',
            'keputusan.required' => 'Sila pilih Keputusan.',
        ]);

        if (($payload['dengan_syarat'] ?? null) === '1' && empty(trim((string) ($payload['syarat_nyatakan'] ?? '')))) {
            return response()->json([
                'message' => 'Sila nyatakan syarat apabila memilih "Dengan Syarat: Ya".',
            ], 422);
        }

        $record = JawatankuasaPerolehanKertasKeputusan::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            ['submitted_at' => null]
        );

        DB::transaction(function () use ($request, $payload, $record, $tender) {
            $this->applyKertasKeputusanData($request, $payload, $record, $tender, true);
            $record->submitted_at = now();
            $record->save();

            // Use query update to avoid Tender model event side-effects.
            $pemilihanHeader = JawatankuasaPerolehanPemilihanHeader::query()
                ->where('tender_id', $tender->id)
                ->first();
            $isEbidding = $pemilihanHeader
                && trim((string) $pemilihanHeader->kaedah_memuktamadkan_pembekal) === 'Bidaan';

            Tender::query()
                ->where('id', $tender->id)
                ->update([
                    // is_ebidding is set when Memuktamadkan Pemilihan chooses Bidaan.
                    'is_ebidding' => $isEbidding ? true : (bool) $tender->is_ebidding,
                    'ebidding_process_stage_id' => $isEbidding
                        ? ((int) ($tender->ebidding_process_stage_id ?? 0) ?: 1)
                        : $tender->ebidding_process_stage_id,
                ]);

            // Final Lulus (non-bidaan): mark winners and close JP.
            if (! $isEbidding && ($payload['keputusan'] ?? null) === 'Lulus') {
                $this->markWinningVendors($tender);
                app(TenderProcessStatusService::class)->setStatus(
                    $tender->fresh(),
                    TenderProcessStatus::JAWATANKUASA_PEROLEHAN
                );
            }
        });

        return response()->json([
            'message' => 'Kertas keputusan berjaya dihantar.',
            'redirect' => route('jawatankuasa.perolehan.index'),
        ]);
    }

    public function simpanPemilihanPembekal(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $this->validatePemilihanPayload($request, false, $tender);
        DB::transaction(function () use ($tender, $payload) {
            $this->applyPemilihanPayload($tender, $payload, false);
        });

        return response()->json(['message' => 'Memuktamadkan pemilihan pembekal berjaya disimpan.']);
    }

    public function hantarPemilihanPembekal(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $this->validatePemilihanPayload($request, true, $tender);
        $kaedah = trim((string) ($payload['header']['kaedah_memuktamadkan_pembekal'] ?? ''));

        DB::transaction(function () use ($tender, $payload, $kaedah) {
            $this->applyPemilihanPayload($tender, $payload, true);

            if ($kaedah === 'Bidaan') {
                if (! TenderProcessStatus::allowsBidaanKaedah($tender->kategori_perolehan_id)) {
                    throw ValidationException::withMessages([
                        'header.kaedah_memuktamadkan_pembekal' => 'Kaedah Bidaan hanya untuk Bekalan atau Perkhidmatan.',
                    ]);
                }
                if ($this->bidaanAlreadyRun($tender)) {
                    throw ValidationException::withMessages([
                        'header.kaedah_memuktamadkan_pembekal' => 'Bidaan telah dijalankan. Sila pilih Pemilihan Terus atau Pemilihan Lebih Daripada Satu Syarikat.',
                    ]);
                }

                Tender::query()->where('id', $tender->id)->update([
                    'is_ebidding' => true,
                    'ebidding_process_stage_id' => 1,
                    'status_process_id' => TenderProcessStatus::PENILAIAN_KEWANGAN,
                ]);
            } else {
                $this->markWinningVendors($tender, $payload);
                app(TenderProcessStatusService::class)->setStatus(
                    $tender->fresh(),
                    TenderProcessStatus::JAWATANKUASA_PEROLEHAN
                );
            }
        });

        $message = $kaedah === 'Bidaan'
            ? 'Pemilihan Bidaan berjaya dihantar. Proses kembali ke Perakuan Jabatan untuk Penyediaan Jadual Bidaan.'
            : 'Memuktamadkan pemilihan pembekal berjaya dihantar.';

        return response()->json(['message' => $message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function pemilihanDropdownOptions(?Tender $tender = null): array
    {
        $kaedah = [
            'Pemilihan Terus',
            'Pemilihan Lebih Daripada Satu Syarikat',
        ];

        $allowBidaan = $tender
            && TenderProcessStatus::allowsBidaanKaedah($tender->kategori_perolehan_id)
            && ! $this->bidaanAlreadyRun($tender);

        if ($allowBidaan) {
            $kaedah[] = 'Bidaan';
        }

        return [
            'keputusan_mesyuarat' => [
                'Pemilihan Pembekal',
                'Penilaian Semula',
                'Iklan Semula',
                'Kemukakan kepada Pihak Berkuasa Yang Lebih Tinggi',
                'Batal',
            ],
            'kaedah_memuktamadkan_pembekal' => $kaedah,
            'pemilihan_berdasarkan' => [
                '1 item',
                'Pakej',
            ],
            'loi_loa_disediakan_oleh' => [
                'Urusetia atau Setiausaha Sebut Harga',
                'Lembaga Perolehan',
            ],
            'keputusan_urusetia' => [
                'Disyorkan',
                'Ditolak',
                'Dipertimbang',
            ],
        ];
    }

    private function bidaanAlreadyRun(Tender $tender): bool
    {
        if ((int) ($tender->ebidding_process_stage_id ?? 0) >= 3) {
            return true;
        }

        return EbiddingJadualBidaan::query()
            ->where('tender_id', $tender->id)
            ->whereNotNull('started_at')
            ->exists();
    }

    /**
     * @return list<string>
     */
    private function justifikasiPemilihanPembekalOptions(): array
    {
        try {
            $rows = RefJustifikasiPemilihanPembekal::query()
                ->where('active', 1)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->pluck('name')
                ->filter()
                ->values()
                ->all();

            if (! empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            // Table may not be migrated yet; fall back to seeder constants.
        }

        return array_column(\Database\Seeders\Ref\JustifikasiPemilihanPembekal::ROWS, 'name');
    }

    private function blankPemilihanHeader(): array
    {
        return [
            'keputusan_mesyuarat' => '',
            'kaedah_memuktamadkan_pembekal' => '',
            'pemilihan_berdasarkan' => '',
            'loi_loa_disediakan_oleh' => '',
            'bil_mesyuarat' => '',
            'no_kod' => '',
            'sahkan_layak_bidaan' => false,
        ];
    }

    private function ensurePemilihanDefaults(Tender $tender): void
    {
        JawatankuasaPerolehanPemilihanHeader::query()->firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'keputusan_mesyuarat' => 'Pemilihan Pembekal',
                'kaedah_memuktamadkan_pembekal' => TenderProcessStatus::allowsBidaanKaedah($tender->kategori_perolehan_id)
                    ? null
                    : 'Pemilihan Terus',
            ]
        );
    }

    /**
     * Sync Senarai Item + Senarai Pembekal from tender participants, vendors, skor, and Perakuan Jabatan.
     */
    private function syncPemilihanFromSources(Tender $tender): void
    {
        DB::transaction(function () use ($tender) {
            $participants = $tender->participants()
                ->with('vendor')
                ->where('participate', 1)
                ->whereNull('eliminated_at')
                ->orderBy('id')
                ->get();

            $jenisItem = $this->resolveJenisItem($tender);
            $eligibleCount = $participants->count();
            $scores = $this->loadTeknikalScoresByVendor($tender);
            $syorByVendor = $this->loadPerakuanSyorByVendor($tender);

            $item = JawatankuasaPerolehanPemilihanItem::query()
                ->where('tender_id', $tender->id)
                ->orderBy('sort_order')
                ->first();

            if (! $item) {
                $item = JawatankuasaPerolehanPemilihanItem::query()->create([
                    'tender_id' => $tender->id,
                    'sort_order' => 1,
                    'perihal_item' => $tender->name ?: 'Item',
                    'jenis_item' => $jenisItem,
                    'unit_ukuran' => 'Unit',
                    'jenis_harga' => 'Biasa Standard',
                    'dibatalkan' => 'Tidak',
                    'pembekal_dipilih' => $eligibleCount,
                    'kuantiti' => 1,
                ]);
            } else {
                $item->perihal_item = $tender->name ?: ($item->perihal_item ?: 'Item');
                $item->jenis_item = $jenisItem;
                $item->pembekal_dipilih = $eligibleCount;
                if (empty($item->unit_ukuran)) {
                    $item->unit_ukuran = 'Unit';
                }
                if (empty($item->jenis_harga)) {
                    $item->jenis_harga = 'Biasa Standard';
                }
                $item->save();
            }

            $keepVendorIds = [];
            $total = max($eligibleCount, 1);

            foreach ($participants->values() as $idx => $participant) {
                $vendorId = (int) $participant->vendor_id;
                if ($vendorId <= 0) {
                    continue;
                }
                $keepVendorIds[] = $vendorId;

                $vendor = $participant->vendor;
                $score = $scores[$vendorId] ?? null;
                $syor = $syorByVendor[$vendorId] ?? null;

                $bumi = (bool) ($participant->is_bumiputera ?? false)
                    || (bool) ($vendor?->mof_bumi ?? false)
                    || (bool) ($vendor?->cidb_bumi ?? false);

                $harga = $participant->harga_tawaran ?? $participant->price ?? $participant->amount ?? 0;

                $mofStatus = '—';
                if ($vendor && method_exists($vendor, 'mofValid')) {
                    $mofStatus = $vendor->mofValid() ? 'Aktif' : 'Tamat Tempoh';
                } elseif (! empty($vendor?->mof_ref_no)) {
                    $mofStatus = 'Aktif';
                }

                JawatankuasaPerolehanPemilihanPetender::query()->updateOrCreate(
                    [
                        'pemilihan_item_id' => $item->id,
                        'vendor_id' => $vendorId,
                    ],
                    [
                        'sort_order' => $idx + 1,
                        'bil_label' => ($idx + 1) . '/' . $total,
                        'status_bumiputra' => $bumi ? 'Ya' : 'Tidak',
                        'harga_tawaran' => (float) $harga,
                        'jumlah_skor' => $score['skor'] ?? 0,
                        'kedudukan_penilaian' => $score['kedudukan'] ?? ($idx + 1),
                        'status_mof' => $mofStatus,
                        'tindakan_disiplin' => null,
                        'lembaga_pengarah_file_path' => null,
                        'keputusan_urusetia' => $syor['syor_urusetia'] ?? null,
                        'catatan_urusetia' => $syor['catatan_urusetia'] ?? null,
                    ]
                );
            }

            // Drop dummy / stale petenders not in the eligible participant set.
            $query = JawatankuasaPerolehanPemilihanPetender::query()
                ->where('pemilihan_item_id', $item->id);

            if ($keepVendorIds === []) {
                $query->delete();
            } else {
                $query->where(function ($q) use ($keepVendorIds) {
                    $q->whereNull('vendor_id')
                        ->orWhereNotIn('vendor_id', $keepVendorIds);
                })->delete();
            }
        });
    }

    private function resolveJenisItem(Tender $tender): string
    {
        return match ((int) ($tender->kategori_perolehan_id ?? 0)) {
            1 => 'Bekalan',
            2 => 'Perkhidmatan',
            3 => 'Kerja',
            default => '—',
        };
    }

    /**
     * @return array<int, array{syor_urusetia: ?string, catatan_urusetia: ?string}>
     */
    private function loadPerakuanSyorByVendor(Tender $tender): array
    {
        $pengesyoran = PerakuanJabatanPengesyoranPembekal::query()
            ->where('tender_id', $tender->id)
            ->first();

        if (! $pengesyoran) {
            return [];
        }

        $map = [];
        foreach ($pengesyoran->items()->get() as $item) {
            $map[(int) $item->vendor_id] = [
                'syor_urusetia' => $item->syor_urusetia,
                'catatan_urusetia' => $item->catatan_urusetia,
            ];
        }

        return $map;
    }

    /**
     * @return array<int, array{skor: float|null, kedudukan: int|null}>
     */
    private function loadTeknikalScoresByVendor(Tender $tender): array
    {
        $scores = [];

        try {
            $stos = app(StosBackendClient::class);
            if ($stos->isConfigured()) {
                $response = $stos->getRumusanPenilaianTeknikal($tender->id);
                if ($response->successful()) {
                    $data = $response->json('data') ?? [];
                    foreach (array_merge($data['layak'] ?? [], $data['tidak_layak'] ?? []) as $row) {
                        $vid = (int) ($row['vendor_id'] ?? 0);
                        if ($vid <= 0) {
                            continue;
                        }
                        $scores[$vid] = [
                            'skor' => isset($row['peratus']) ? (float) $row['peratus'] : null,
                            'kedudukan' => isset($row['kedudukan']) ? (int) $row['kedudukan'] : null,
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Local fallback below.
        }

        if (! empty($scores)) {
            return $scores;
        }

        $rows = TenderTeknikalSpesifikasiEvaluation::query()
            ->where('tender_id', $tender->id)
            ->selectRaw('vendor_id, SUM(COALESCE(skor_automatik,0) + COALESCE(skor_manual,0)) as jumlah')
            ->groupBy('vendor_id')
            ->orderByDesc('jumlah')
            ->get();

        $rank = 1;
        foreach ($rows as $row) {
            $scores[(int) $row->vendor_id] = [
                'skor' => round((float) $row->jumlah, 2),
                'kedudukan' => $rank++,
            ];
        }

        return $scores;
    }

    private function validatePemilihanPayload(Request $request, bool $forSubmit, ?Tender $tender = null): array
    {
        $opts = $this->pemilihanDropdownOptions($tender);
        $emptyPad = $forSubmit ? [] : [''];

        $headerRules = [
            'header' => ['required', 'array'],
            'header.keputusan_mesyuarat' => [
                $forSubmit ? 'required' : 'nullable',
                'string',
                'max:255',
                Rule::in(array_merge($emptyPad, $opts['keputusan_mesyuarat'])),
            ],
            'header.kaedah_memuktamadkan_pembekal' => [
                $forSubmit ? 'required' : 'nullable',
                'string',
                'max:255',
                Rule::in(array_merge($emptyPad, $opts['kaedah_memuktamadkan_pembekal'])),
            ],
            'header.pemilihan_berdasarkan' => [
                $forSubmit ? 'required' : 'nullable',
                'string',
                'max:255',
                Rule::in(array_merge($emptyPad, $opts['pemilihan_berdasarkan'])),
            ],
            'header.loi_loa_disediakan_oleh' => [
                $forSubmit ? 'required' : 'nullable',
                'string',
                'max:255',
                Rule::in(array_merge($emptyPad, $opts['loi_loa_disediakan_oleh'])),
            ],
            'header.bil_mesyuarat' => [$forSubmit ? 'required' : 'nullable', 'string', 'max:100'],
            'header.no_kod' => [$forSubmit ? 'required' : 'nullable', 'string', 'max:100'],
            'header.sahkan_layak_bidaan' => ['nullable', 'boolean'],
        ];

        $itemRules = [
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer'],
            'items.*.dibatalkan' => ['nullable', Rule::in(['Tidak', 'Ya'])],
            'items.*.pembekal_dipilih' => ['nullable', 'integer', 'min:0'],
            'items.*.kuantiti' => ['nullable', 'numeric', 'min:0'],
            'items.*.petenders' => ['required', 'array'],
            'items.*.petenders.*.id' => ['required', 'integer'],
            'items.*.petenders.*.vendor_id' => ['nullable', 'integer'],
            'items.*.petenders.*.selected_for_selection' => ['nullable', 'boolean'],
            'items.*.petenders.*.status_bumiputra' => ['nullable', Rule::in(['Ya', 'Tidak'])],
            'items.*.petenders.*.harga_tawaran' => ['nullable', 'numeric'],
            'items.*.petenders.*.jumlah_skor' => ['nullable', 'numeric'],
            'items.*.petenders.*.kedudukan_penilaian' => ['nullable', 'integer', 'min:0'],
            'items.*.petenders.*.status_mof' => ['nullable', 'string', 'max:100'],
            'items.*.petenders.*.tindakan_disiplin' => ['nullable', 'string', 'max:65535'],
            'items.*.petenders.*.keputusan_urusetia' => [
                'nullable',
                'string',
                'max:100',
                Rule::in(array_merge($emptyPad, $opts['keputusan_urusetia'])),
            ],
            'items.*.petenders.*.catatan_urusetia' => ['nullable', 'string', 'max:65535'],
        ];

        $validated = $request->validate(array_merge([
            'tender' => ['required'],
        ], $headerRules, $itemRules));

        $kaedah = trim((string) ($validated['header']['kaedah_memuktamadkan_pembekal'] ?? ''));
        $sahkan = $validated['header']['sahkan_layak_bidaan'] ?? false;
        if ($forSubmit && $kaedah === 'Bidaan' && ! filter_var($sahkan, FILTER_VALIDATE_BOOLEAN)) {
            throw ValidationException::withMessages([
                'header.sahkan_layak_bidaan' => 'Sila tandakan pengesahan petender layak untuk menyertai Bidaan.',
            ]);
        }

        if (
            $forSubmit
            && in_array($kaedah, ['Pemilihan Terus', 'Pemilihan Lebih Daripada Satu Syarikat'], true)
            && $this->resolveWinnerVendorIds($tender, $validated) === []
        ) {
            throw ValidationException::withMessages([
                'items' => 'Sila pilih sekurang-kurangnya satu pembekal pemenang (kotak Pemilihan) atau pastikan ada pembekal Disyorkan.',
            ]);
        }

        return $validated;
    }

    /**
     * Set tender_vendors.winner for selected / Disyorkan pembekal.
     *
     * @param  array<string, mixed>|null  $pemilihanPayload
     */
    private function markWinningVendors(Tender $tender, ?array $pemilihanPayload = null): void
    {
        $winnerVendorIds = $this->resolveWinnerVendorIds($tender, $pemilihanPayload);

        TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->update(['winner' => 0]);

        if ($winnerVendorIds === []) {
            return;
        }

        TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->whereIn('vendor_id', $winnerVendorIds)
            ->update(['winner' => 1]);
    }

    /**
     * @param  array<string, mixed>|null  $pemilihanPayload
     * @return list<int>
     */
    private function resolveWinnerVendorIds(Tender $tender, ?array $pemilihanPayload = null): array
    {
        $winnerVendorIds = [];

        if (is_array($pemilihanPayload)) {
            $petenderIds = [];
            foreach ($pemilihanPayload['items'] ?? [] as $item) {
                foreach ($item['petenders'] ?? [] as $p) {
                    if (filter_var($p['selected_for_selection'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                        $vendorId = (int) ($p['vendor_id'] ?? 0);
                        if ($vendorId > 0) {
                            $winnerVendorIds[] = $vendorId;
                        } else {
                            $petenderIds[] = (int) ($p['id'] ?? 0);
                        }
                    }
                }
            }

            if ($petenderIds !== []) {
                $fromPets = JawatankuasaPerolehanPemilihanPetender::query()
                    ->whereIn('id', array_filter($petenderIds))
                    ->whereNotNull('vendor_id')
                    ->pluck('vendor_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
                $winnerVendorIds = array_merge($winnerVendorIds, $fromPets);
            }
        }

        if ($winnerVendorIds === []) {
            $winnerVendorIds = JawatankuasaPerolehanPemilihanPetender::query()
                ->whereHas('item', fn ($q) => $q->where('tender_id', $tender->id))
                ->where('keputusan_urusetia', PerakuanJabatanPengesyoranPembekalItem::SYOR_DISYORKAN)
                ->whereNotNull('vendor_id')
                ->pluck('vendor_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        if ($winnerVendorIds === []) {
            $pengesyoran = PerakuanJabatanPengesyoranPembekal::query()
                ->where('tender_id', $tender->id)
                ->first();

            if ($pengesyoran) {
                $winnerVendorIds = PerakuanJabatanPengesyoranPembekalItem::query()
                    ->where('pengesyoran_pembekal_id', $pengesyoran->id)
                    ->where('syor_urusetia', PerakuanJabatanPengesyoranPembekalItem::SYOR_DISYORKAN)
                    ->pluck('vendor_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
            }
        }

        return array_values(array_unique(array_filter($winnerVendorIds)));
    }

    private function applyPemilihanPayload(Tender $tender, array $payload, bool $forSubmit): void
    {
        $header = JawatankuasaPerolehanPemilihanHeader::query()->firstOrCreate(['tender_id' => $tender->id]);
        $h = $payload['header'];

        $header->keputusan_mesyuarat = $this->nullableTrim($h['keputusan_mesyuarat'] ?? null);
        $header->kaedah_memuktamadkan_pembekal = $this->nullableTrim($h['kaedah_memuktamadkan_pembekal'] ?? null);
        $header->pemilihan_berdasarkan = $this->nullableTrim($h['pemilihan_berdasarkan'] ?? null);
        $header->loi_loa_disediakan_oleh = $this->nullableTrim($h['loi_loa_disediakan_oleh'] ?? null);
        $header->bil_mesyuarat = $this->nullableTrim($h['bil_mesyuarat'] ?? null);
        $header->no_kod = $this->nullableTrim($h['no_kod'] ?? null);
        $header->sahkan_layak_bidaan = (bool) ($h['sahkan_layak_bidaan'] ?? false);

        if ($forSubmit) {
            $header->submitted_at = now();
        }

        $header->save();

        foreach ($payload['items'] as $row) {
            $item = JawatankuasaPerolehanPemilihanItem::query()
                ->where('tender_id', $tender->id)
                ->where('id', (int) $row['id'])
                ->first();

            if (!$item) {
                continue;
            }

            $item->dibatalkan = $row['dibatalkan'] ?? 'Tidak';
            $item->pembekal_dipilih = (int) ($row['pembekal_dipilih'] ?? 0);
            $item->kuantiti = $row['kuantiti'] ?? 1;
            $item->save();

            foreach ($row['petenders'] as $p) {
                $pet = JawatankuasaPerolehanPemilihanPetender::query()
                    ->where('pemilihan_item_id', $item->id)
                    ->where('id', (int) $p['id'])
                    ->first();

                if (!$pet) {
                    continue;
                }

                $pet->status_bumiputra = $p['status_bumiputra'] ?? $pet->status_bumiputra;
                $pet->harga_tawaran = $p['harga_tawaran'] ?? $pet->harga_tawaran;
                $pet->jumlah_skor = $p['jumlah_skor'] ?? $pet->jumlah_skor;
                $pet->kedudukan_penilaian = $p['kedudukan_penilaian'] ?? $pet->kedudukan_penilaian;
                $pet->status_mof = $this->nullableTrim($p['status_mof'] ?? null) ?? $pet->status_mof;
                // Prestasi / Lembaga left blank ("—") for now; Keputusan/Catatan come from Perakuan Jabatan sync.
                $pet->tindakan_disiplin = null;
                if (array_key_exists('keputusan_urusetia', $p)) {
                    $pet->keputusan_urusetia = $this->nullableTrim($p['keputusan_urusetia'] ?? null);
                }
                if (array_key_exists('catatan_urusetia', $p)) {
                    $pet->catatan_urusetia = $this->nullableTrim($p['catatan_urusetia'] ?? null);
                }
                $pet->save();
            }
        }
    }

    private function resolveTender($identifier): ?Tender
    {
        if (empty($identifier)) {
            return null;
        }

        if (is_numeric($identifier)) {
            $tender = Tender::query()->where('id', (int) $identifier)->first();
        } else {
            $tender = Tender::query()->where('uuid', $identifier)->first();
        }

        if ($tender) {
            $this->assertLembagaDecisionAccess($tender);
        }

        return $tender;
    }

    private function applyKertasKeputusanData(
        Request $request,
        array $payload,
        JawatankuasaPerolehanKertasKeputusan $record,
        Tender $tender,
        bool $forSubmit
    ): void {
        $record->dengan_syarat = array_key_exists('dengan_syarat', $payload)
            ? ((string) $payload['dengan_syarat'] === '1')
            : null;
        $record->syarat_nyatakan = $this->nullableTrim($payload['syarat_nyatakan'] ?? null);
        $record->pengesyoran_catatan = $this->nullableTrim($payload['pengesyoran_catatan'] ?? null);
        $record->justifikasi_pemilihan_pembekal = $this->nullableTrim($payload['justifikasi_pemilihan_pembekal'] ?? null);
        $record->keputusan = $payload['keputusan'] ?? null;
        $record->catatan = $this->nullableTrim($payload['catatan'] ?? null);

        $shouldRemoveAttachment = ($payload['buang_lampiran'] ?? '0') === '1';
        if ($shouldRemoveAttachment) {
            $this->deleteStoredFile($record->lampiran_file_path);
            $record->lampiran_file_nama = null;
            $record->lampiran_file_path = null;
        }

        $upload = $request->file('lampiran');
        if ($upload instanceof UploadedFile && $upload->isValid()) {
            $this->deleteStoredFile($record->lampiran_file_path);
            $stored = $this->storeKertasKeputusanLampiran($upload, $tender->id);
            $record->lampiran_file_nama = $stored['original_name'];
            $record->lampiran_file_path = $stored['relative_path'];
        }
    }

    private function storeKertasKeputusanLampiran(UploadedFile $file, int $tenderId): array
    {
        $relativeDir = 'uploads/jawatankuasa_perolehan/' . $tenderId . '/kertas_keputusan';
        $absoluteDir = public_path($relativeDir);
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }

        $safeOriginal = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = date('YmdHis') . '_' . $safeOriginal;
        $file->move($absoluteDir, $fileName);

        return [
            'original_name' => $file->getClientOriginalName(),
            'relative_path' => $relativeDir . '/' . $fileName,
        ];
    }

    private function deleteStoredFile(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $fullPath = public_path($relativePath);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function nullableTrim($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }
}
