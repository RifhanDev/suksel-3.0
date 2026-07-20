<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Models\JawatankuasaPerolehanKertasKeputusan;
use App\Models\JawatankuasaPerolehanMeeting;
use App\Models\JawatankuasaPerolehanPemilihanHeader;
use App\Models\JawatankuasaPerolehanPemilihanItem;
use App\Models\JawatankuasaPerolehanPemilihanPetender;
use App\Models\PerakuanJabatanKertasTaklimatItem;
use App\Support\TenderProcessStatus;
use App\Support\VendorCidbMeta;
use App\Tender;
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = Tender::query()
            ->where('status_process_id', TenderProcessStatus::jawatankuasaPerolehanListStatus())
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
        $pemilihanOpts = $this->pemilihanDropdownOptions();
        $pemilihanVendors = collect();

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
                        'tajuk_agenda' => $meeting->tajuk_agenda ?? '',
                        'tempat' => $meeting->tempat ?? '',
                        'no_kod_kertas' => $meeting->no_kod_kertas ?? '',
                        'status' => $meeting->status ?? 'Belum Selesai',
                        'catatan' => $meeting->catatan ?? '',
                    ];
                })
                ->values();

            $taklimatAttachments = PerakuanJabatanKertasTaklimatItem::query()
                ->whereHas('header', function ($query) use ($tender) {
                    $query->where('tender_id', $tender->id);
                })
                ->with('files')
                ->orderBy('sort_order')
                ->get()
                ->flatMap(function ($item) {
                    return $item->files->map(function ($file) use ($item) {
                        return [
                            'kandungan' => $item->kandungan,
                            'file_name' => $file->file_original_name,
                            // Papar sahaja: buka fail terus (bukan muat turun paksa)
                            'papar_url' => $file->file_path ? asset($file->file_path) : '#',
                        ];
                    });
                })
                ->values();

            $kertasKeputusan = JawatankuasaPerolehanKertasKeputusan::query()
                ->where('tender_id', $tender->id)
                ->first();

            $this->ensurePemilihanDefaults($tender);
            $this->syncPemilihanPetenderVendors($tender);
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
                                'status_bumiputra' => $p->status_bumiputra,
                                'harga_tawaran' => (string) $p->harga_tawaran,
                                'jumlah_skor' => (string) $p->jumlah_skor,
                                'kedudukan_penilaian' => $p->kedudukan_penilaian,
                                'status_mof' => $p->status_mof,
                                'tindakan_disiplin' => $p->tindakan_disiplin,
                                'lembaga_pengarah_papar_url' => $p->lembaga_pengarah_file_path
                                    ? asset($p->lembaga_pengarah_file_path)
                                    : null,
                                'keputusan_urusetia' => $p->keputusan_urusetia,
                                'catatan_urusetia' => $p->catatan_urusetia,
                            ];
                        })->values(),
                    ];
                })
                ->values();

            $vendorIds = $pemilihanItems
                ->flatMap(fn (array $item) => collect($item['petenders'])->pluck('vendor_id'))
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
        ));
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
            'justifikasi_pemilihan_pembekal' => ['nullable', 'string', 'max:255'],
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
            'justifikasi_pemilihan_pembekal' => ['required', 'string', 'max:255'],
            'keputusan' => ['required', 'in:Lulus,Tawaran Semula,Batal,Tangguh'],
            'catatan' => ['nullable', 'string', 'max:65535'],
            'lampiran' => ['nullable', 'file', 'max:10240'],
            'buang_lampiran' => ['nullable', 'in:0,1'],
        ], [
            'pengesyoran_catatan.required' => 'Ruangan Pengesyoran adalah wajib.',
            'justifikasi_pemilihan_pembekal.required' => 'Justifikasi Pemilihan Pembekal adalah wajib.',
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
                    'is_ebidding' => $isEbidding,
                    'ebidding_process_stage_id' => $isEbidding ? 1 : null,
                ]);
        });

        return response()->json(['message' => 'Kertas keputusan berjaya dihantar.']);
    }

    public function simpanPemilihanPembekal(Request $request)
    {
        $tender = $this->resolveTender($request->input('tender'));
        if (!$tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $payload = $this->validatePemilihanPayload($request, false);
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

        $payload = $this->validatePemilihanPayload($request, true);
        DB::transaction(function () use ($tender, $payload) {
            $this->applyPemilihanPayload($tender, $payload, true);
        });

        $this->advanceTenderProcess(
            $tender->fresh(),
            TenderProcessStatus::JAWATANKUASA_PEROLEHAN,
            TenderProcessStatus::jawatankuasaPerolehanListStatus()
        );

        return response()->json(['message' => 'Memuktamadkan pemilihan pembekal berjaya dihantar.']);
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

    private function pemilihanDropdownOptions(): array
    {
        return [
            'keputusan_mesyuarat' => [
                'Pengesyoran Pembekal',
                'Penilaian Semula',
                'Iklan Semula',
                'Kemukakan kepada Pihak Berkuasa Yang Lebih Tinggi',
                'Batal',
            ],
            'kaedah_memuktamadkan_pembekal' => [
                'Pemilihan Terus',
                'Pemilihan Lebih Daripada Satu Syarikat',
                'Bidaan',
            ],
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
                'Tidak Disyorkan',
                'Tangguh',
            ],
        ];
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
        DB::transaction(function () use ($tender) {
            JawatankuasaPerolehanPemilihanHeader::query()->firstOrCreate(
                ['tender_id' => $tender->id],
                [
                    'keputusan_mesyuarat' => 'Pengesyoran Pembekal',
                    'kaedah_memuktamadkan_pembekal' => 'Bidaan',
                ]
            );

            if (JawatankuasaPerolehanPemilihanItem::query()->where('tender_id', $tender->id)->exists()) {
                return;
            }

            $item = JawatankuasaPerolehanPemilihanItem::query()->create([
                'tender_id' => $tender->id,
                'sort_order' => 1,
                'perihal_item' => $tender->name ?: 'Item',
                'jenis_item' => 'Perkhidmatan',
                'unit_ukuran' => 'Activity Unit',
                'jenis_harga' => 'Biasa Standard',
                'dibatalkan' => 'Tidak',
                'pembekal_dipilih' => 2,
                'kuantiti' => 1,
            ]);

            $defaults = [
                [
                    'sort_order' => 1,
                    'bil_label' => '2/2',
                    'status_bumiputra' => 'Tidak',
                    'harga_tawaran' => 360000,
                    'jumlah_skor' => 96.43,
                    'kedudukan_penilaian' => 1,
                    'status_mof' => 'Aktif',
                    'keputusan_urusetia' => 'Disyorkan',
                ],
                [
                    'sort_order' => 2,
                    'bil_label' => '1/2',
                    'status_bumiputra' => 'Ya',
                    'harga_tawaran' => 360000,
                    'jumlah_skor' => 96.43,
                    'kedudukan_penilaian' => 2,
                    'status_mof' => 'Aktif',
                    'keputusan_urusetia' => 'Disyorkan',
                ],
            ];

            foreach ($defaults as $row) {
                JawatankuasaPerolehanPemilihanPetender::query()->create(array_merge($row, [
                    'pemilihan_item_id' => $item->id,
                ]));
            }
        });
    }

    private function validatePemilihanPayload(Request $request, bool $forSubmit): array
    {
        $opts = $this->pemilihanDropdownOptions();
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
            'items.*.petenders.*.status_bumiputra' => ['nullable', Rule::in(['Ya', 'Tidak'])],
            'items.*.petenders.*.harga_tawaran' => ['nullable', 'numeric'],
            'items.*.petenders.*.jumlah_skor' => ['nullable', 'numeric'],
            'items.*.petenders.*.kedudukan_penilaian' => ['nullable', 'integer', 'min:0'],
            'items.*.petenders.*.status_mof' => ['nullable', 'string', 'max:100'],
            'items.*.petenders.*.tindakan_disiplin' => ['nullable', 'string', 'max:65535'],
            'items.*.petenders.*.keputusan_urusetia' => [
                $forSubmit ? 'required' : 'nullable',
                'string',
                'max:100',
                Rule::in(array_merge($emptyPad, $opts['keputusan_urusetia'])),
            ],
            'items.*.petenders.*.catatan_urusetia' => ['nullable', 'string', 'max:65535'],
        ];

        $validated = $request->validate(array_merge([
            'tender' => ['required'],
        ], $headerRules, $itemRules));

        $sahkan = $validated['header']['sahkan_layak_bidaan'] ?? false;
        if ($forSubmit && !filter_var($sahkan, FILTER_VALIDATE_BOOLEAN)) {
            throw ValidationException::withMessages([
                'header.sahkan_layak_bidaan' => 'Sila tandakan pengesahan petender layak untuk menyertai Bidaan.',
            ]);
        }

        return $validated;
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
                $pet->tindakan_disiplin = $this->nullableTrim($p['tindakan_disiplin'] ?? null);
                $pet->keputusan_urusetia = $this->nullableTrim($p['keputusan_urusetia'] ?? null);
                $pet->catatan_urusetia = $this->nullableTrim($p['catatan_urusetia'] ?? null);
                $pet->save();
            }
        }
    }

    private function syncPemilihanPetenderVendors(Tender $tender): void
    {
        $vendorIds = Vendor::query()
            ->whereNotNull('meta')
            ->orderBy('id')
            ->pluck('id')
            ->values();

        if ($vendorIds->isEmpty()) {
            return;
        }

        $petenders = JawatankuasaPerolehanPemilihanPetender::query()
            ->whereHas('item', function ($query) use ($tender) {
                $query->where('tender_id', $tender->id);
            })
            ->orderBy('pemilihan_item_id')
            ->orderBy('sort_order')
            ->get();

        foreach ($petenders as $index => $petender) {
            if ($petender->vendor_id || ! isset($vendorIds[$index])) {
                continue;
            }

            $petender->vendor_id = $vendorIds[$index];
            $petender->save();
        }
    }

    private function resolveTender($identifier): ?Tender
    {
        if (empty($identifier)) {
            return null;
        }

        if (is_numeric($identifier)) {
            return Tender::query()->where('id', (int) $identifier)->first();
        }

        return Tender::query()->where('uuid', $identifier)->first();
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
