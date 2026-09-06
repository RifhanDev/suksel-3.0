<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Models\EbiddingJadualBidaan;
use App\Models\PerakuanJabatanKertasTaklimat;
use App\Models\PerakuanJabatanKertasTaklimatItem;
use App\Models\PerakuanJabatanKertasTaklimatItemFile;
use App\Models\PerakuanJabatanPengesyoranPembekal;
use App\Models\PerakuanJabatanPengesyoranPembekalItem;
use App\Models\TenderKewanganKerjaEvaluation;
use App\Models\TenderTeknikalSpesifikasiEvaluation;
use App\Services\StosBackendClient;
use App\Services\TenderProcessStatusService;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PerakuanJabatanController extends Controller
{
    use AdvancesTenderProcessStatus;

    public function __construct()
    {
        $this->menuMiddleware('DepartmentCertification:list');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = Tender::query()
            ->where('status_process_id', TenderProcessStatus::perakuanJabatanListStatus())
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
                if (!empty($tender->submission_datetime)) {
                    $submissionDate = Carbon::parse($tender->submission_datetime);
                }

                $noTender = $tender->no_tender ?: $tender->ref_number ?: (string) $tender->id;

                return [
                    'id' => $tender->id,
                    'uuid' => $tender->uuid,
                    'no_tender' => $noTender,
                    'tajuk' => $tender->name ?: '-',
                    'tarikh' => $submissionDate ? $submissionDate->format('d/m/Y') : '-',
                    'status_label' => 'Dalam Proses',
                    'show_url' => route('perakuanjabatan.show', ['id' => $tender->id]),
                ];
            })
            ->values();

        return view('newModule.perakuanJabatan.index', compact('tenders'));
    }

    /**
     * Show perakuan jabatan workspace for a tender.
     */
    public function show($id)
    {
        $tender = Tender::with('tenderer')->findOrFail($id);

        $this->promoteEbiddingReviewIfWindowEnded($tender);
        $tender->refresh();

        $pjMode = $this->resolvePerakuanMode($tender);

        $header = PerakuanJabatanKertasTaklimat::firstOrCreate(
            ['tender_id' => $tender->id],
            ['catatan' => null, 'submitted_at' => null]
        );

        $this->seedDefaultKertasTaklimatItems($header);
        $this->pruneRemovedKertasTaklimatSlots($header);
        if (in_array($pjMode, ['jadual', 'laporan'], true)) {
            $this->ensureLaporanBidaanSlot($header);
        }

        $kertasItems = $header->items()->with('files')->orderBy('sort_order')->get();
        if ($pjMode === 'normal') {
            $kertasItems = $kertasItems->reject(fn ($item) => $item->slot_key === 'laporan_bidaan')->values();
        }

        $kertasSourceLinks = $this->buildKertasTaklimatSourceLinks($tender);

        $pengesyoranPembekal = PerakuanJabatanPengesyoranPembekal::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'catatan' => null,
                'sahkan_petender_layak' => false,
                'pengesahan_bidaan' => false,
                'submitted_at' => null,
            ]
        );

        $senaraiItem = [
            'item' => $tender->name ?: '-',
            'jenis_item' => $this->resolveJenisItem($tender),
            'unit_ukuran' => 'Unit',
            'jenis_harga' => 'Biasa Standard',
        ];
        $pembekalRows = $this->buildPengesyoranPembekalRows($tender, $pengesyoranPembekal);
        $isKerja = (int) ($tender->kategori_perolehan_id ?? 0) === 3;

        $jadualBidaan = null;
        $jadualReadOnly = false;
        if (in_array($pjMode, ['jadual', 'laporan'], true)) {
            $jadualBidaan = EbiddingJadualBidaan::query()->firstOrCreate(['tender_id' => $tender->id]);
            $jadualReadOnly = $pjMode === 'laporan'
                || (int) ($tender->ebidding_process_stage_id ?? 0) >= 2
                || ! empty($jadualBidaan->started_at);
        }

        $tabsReadOnly = in_array($pjMode, ['jadual', 'laporan'], true);

        return view(
            'newModule.perakuanJabatan.show',
            compact(
                'tender',
                'header',
                'kertasItems',
                'kertasSourceLinks',
                'pengesyoranPembekal',
                'senaraiItem',
                'pembekalRows',
                'pjMode',
                'jadualBidaan',
                'jadualReadOnly',
                'tabsReadOnly',
                'isKerja'
            )
        );
    }

    public function jadualBidaanSimpan(Request $request, Tender $tender)
    {
        return app(EbiddingController::class)->simpanJadualBidaan($request, $tender->id);
    }

    public function jadualBidaanMula(Request $request, Tender $tender)
    {
        return app(EbiddingController::class)->mulaBidaan($request, $tender->id);
    }

    public function semakanBidaanHantar(Request $request, Tender $tender)
    {
        if (! (bool) $tender->is_ebidding || (int) ($tender->ebidding_process_stage_id ?? 0) < 3) {
            return response()->json(['message' => 'Semakan bidaan hanya selepas tempoh bidaan tamat.'], 422);
        }

        $header = PerakuanJabatanKertasTaklimat::firstOrCreate(
            ['tender_id' => $tender->id],
            ['catatan' => null, 'submitted_at' => null]
        );
        $this->ensureLaporanBidaanSlot($header);
        $laporanItem = $header->items()->where('slot_key', 'laporan_bidaan')->with('files')->first();
        $hasExistingLaporan = $laporanItem && $laporanItem->files->isNotEmpty();

        $request->validate([
            'pengesahan_bidaan' => ['required', 'accepted'],
            'laporan_bidaan' => [$hasExistingLaporan ? 'nullable' : 'required', 'file', 'max:10240'],
        ], [
            'pengesahan_bidaan.accepted' => 'Sila tandakan pengesahan bidaan.',
            'laporan_bidaan.required' => 'Sila muat naik Laporan Bidaan sebelum menghantar.',
        ]);

        if ($request->hasFile('laporan_bidaan') && $laporanItem) {
            $upload = $request->file('laporan_bidaan');
            if ($upload instanceof UploadedFile && $upload->isValid()) {
                $this->storeItemFile($laporanItem, $upload, (int) $tender->id);
            }
        }

        $laporanItem?->load('files');
        if (! $laporanItem || $laporanItem->files->isEmpty()) {
            throw ValidationException::withMessages([
                'laporan_bidaan' => 'Sila muat naik Laporan Bidaan sebelum menghantar.',
            ]);
        }

        $record = PerakuanJabatanPengesyoranPembekal::firstOrCreate(
            ['tender_id' => $tender->id],
            [
                'catatan' => null,
                'sahkan_petender_layak' => false,
                'pengesahan_bidaan' => false,
                'submitted_at' => null,
            ]
        );
        $record->pengesahan_bidaan = true;
        $record->save();

        app(TenderProcessStatusService::class)->syncPerakuanJabatanCompletion($tender->fresh());

        return response()->json([
            'message' => 'Semakan bidaan berjaya dihantar. Proses kembali ke Jawatankuasa Perolehan.',
        ]);
    }

    public function kertasTaklimatSimpan(Request $request, Tender $tender)
    {
        if ($this->resolvePerakuanMode($tender) !== 'normal') {
            return response()->json(['message' => 'Kertas taklimat tidak boleh diubah pada peringkat ini.'], 422);
        }

        $this->persistKertasTaklimat($request, $tender, false);

        return response()->json(['message' => 'Kertas taklimat berjaya disimpan.']);
    }

    public function kertasTaklimatHantar(Request $request, Tender $tender)
    {
        if ($this->resolvePerakuanMode($tender) !== 'normal') {
            return response()->json(['message' => 'Kertas taklimat tidak boleh dihantar pada peringkat ini.'], 422);
        }

        $this->persistKertasTaklimat($request, $tender, true);

        return response()->json(['message' => 'Kertas taklimat berjaya dihantar.']);
    }

    public function pengesyoranPembekalSimpan(Request $request, Tender $tender)
    {
        if ($this->resolvePerakuanMode($tender) !== 'normal') {
            return response()->json(['message' => 'Pengesyoran pembekal tidak boleh diubah pada peringkat ini.'], 422);
        }

        $this->persistPengesyoranPembekal($request, $tender, false);

        return response()->json(['message' => 'Pengesyoran Pembekal berjaya disimpan.']);
    }

    public function pengesyoranPembekalHantar(Request $request, Tender $tender)
    {
        if ($this->resolvePerakuanMode($tender) !== 'normal') {
            return response()->json(['message' => 'Pengesyoran pembekal tidak boleh dihantar pada peringkat ini.'], 422);
        }

        $this->persistPengesyoranPembekal($request, $tender, true);

        return response()->json(['message' => 'Pengesyoran Pembekal berjaya dihantar. Status proses tender dikemas kini.']);
    }

    public function kertasTaklimatDownload(PerakuanJabatanKertasTaklimatItemFile $file)
    {
        $file->loadMissing('item.header');
        $item = $file->item;
        abort_unless($item && $item->header, 404);

        $path = public_path($file->file_path);
        abort_unless(is_file($path), 404);

        return response()->download($path, $file->file_original_name);
    }

    /** Proxy printable teknikal report so Urusetia can open it without TechnicalEvaluation menu. */
    public function muatTurunLaporanTeknikal(Tender $tender)
    {
        return app(PenilaianTeknikalController::class)->cetakLaporan($tender);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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

    public function form()
    {
        return view('newModule.perakuanJabatan.form');
    }

    public function pengesyoranPembekal()
    {
        return redirect()->route('perakuanjabatan.index');
    }

    public function kertasTaklimat()
    {
        return view('newModule.perakuanJabatan.kertas_taklimat');
    }

    private function seedDefaultKertasTaklimatItems(PerakuanJabatanKertasTaklimat $header): void
    {
        if ($header->items()->exists()) {
            return;
        }

        $defaults = [
            ['slot_key' => 'teknikal', 'kandungan' => 'Laporan Jawatankuasa Teknikal', 'sort_order' => 1],
            ['slot_key' => 'kewangan', 'kandungan' => 'Laporan Jawatankuasa Kewangan', 'sort_order' => 2],
            ['slot_key' => 'kertas_perakuan', 'kandungan' => 'Kertas Taklimat (Perakuan Jabatan)', 'sort_order' => 3],
            ['slot_key' => 'ringkasan', 'kandungan' => 'Ringkasan Kertas Taklimat (wajib untuk tender)', 'sort_order' => 4],
        ];

        foreach ($defaults as $row) {
            $header->items()->create($row);
        }
    }

    private function ensureLaporanBidaanSlot(PerakuanJabatanKertasTaklimat $header): void
    {
        if ($header->items()->where('slot_key', 'laporan_bidaan')->exists()) {
            return;
        }

        $maxSort = (int) $header->items()->max('sort_order');
        $header->items()->create([
            'slot_key' => 'laporan_bidaan',
            'kandungan' => 'Laporan Bidaan',
            'sort_order' => $maxSort + 1,
        ]);
    }

    /**
     * @return 'normal'|'jadual'|'laporan'
     */
    private function resolvePerakuanMode(Tender $tender): string
    {
        if (! (bool) ($tender->is_ebidding ?? false)) {
            return 'normal';
        }

        $stage = (int) ($tender->ebidding_process_stage_id ?? 0);
        if ($stage >= 3) {
            return 'laporan';
        }

        return 'jadual';
    }

    private function promoteEbiddingReviewIfWindowEnded(Tender $tender): void
    {
        if (! (bool) ($tender->is_ebidding ?? false)) {
            return;
        }

        $stage = (int) ($tender->ebidding_process_stage_id ?? 0);
        if ($stage !== 2) {
            return;
        }

        $schedule = EbiddingJadualBidaan::query()->where('tender_id', $tender->id)->first();
        if (
            ! $schedule
            || ! $schedule->tarikh_bidaan_mula
            || ! $schedule->masa_bidaan_mula
            || ! $schedule->tarikh_bidaan_tamat
            || ! $schedule->masa_bidaan_tamat
        ) {
            return;
        }

        $endAt = Carbon::parse(
            $schedule->tarikh_bidaan_tamat->format('Y-m-d') . ' ' . $schedule->masa_bidaan_tamat
        );

        if (Carbon::now()->lessThanOrEqualTo($endAt)) {
            return;
        }

        Tender::query()->where('id', $tender->id)->update([
            'ebidding_process_stage_id' => 3,
            'status_process_id' => TenderProcessStatus::PENILAIAN_KEWANGAN,
        ]);
    }

    /** Drop retired slots (e.g. Laporan Jawatankuasa Pembuka) from existing headers. */
    private function pruneRemovedKertasTaklimatSlots(PerakuanJabatanKertasTaklimat $header): void
    {
        $retired = ['pembuka'];

        $items = $header->items()->whereIn('slot_key', $retired)->with('files')->get();
        foreach ($items as $item) {
            foreach ($item->files as $file) {
                $this->deleteStoredFile($file->file_path);
                $file->delete();
            }
            $item->delete();
        }
    }

    /**
     * Module-sourced download URLs for download_only slots (not user uploads).
     * Perakuan Jabatan only opens after teknikal + kewangan are done, so links are always available.
     *
     * @return array<string, array{label: string, url: string}|null>
     */
    private function buildKertasTaklimatSourceLinks(Tender $tender): array
    {
        return [
            'teknikal' => [
                'label' => 'Laporan Jawatankuasa Penilaian Teknikal',
                'url' => route('perakuanjabatan.laporanTeknikal', $tender),
            ],
        ];
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
     * Pembekal that reached Perakuan Jabatan (participated, not eliminated).
     *
     * @return list<array<string, mixed>>
     */
    private function buildPengesyoranPembekalRows(Tender $tender, ?PerakuanJabatanPengesyoranPembekal $pengesyoran = null): array
    {
        $participants = $tender->participants()
            ->with('vendor')
            ->where('participate', 1)
            ->whereNull('eliminated_at')
            ->orderBy('id')
            ->get();

        if ($participants->isEmpty()) {
            return [];
        }

        $savedByVendor = [];
        if ($pengesyoran) {
            $savedByVendor = $pengesyoran->items()
                ->get()
                ->keyBy(fn ($item) => (int) $item->vendor_id)
                ->all();
        }

        $isKerja = (int) ($tender->kategori_perolehan_id ?? 0) === 3;
        $teknikalScores = $isKerja ? [] : $this->loadTeknikalScoresByVendor($tender);
        $kerjaScores = $isKerja ? $this->loadKerjaBorang14ScoresByVendor($tender, $participants) : [];
        $total = $participants->count();

        $sortedByHarga = $participants
            ->sortBy(fn ($p) => (float) ($p->harga_tawaran ?? $p->price ?? $p->amount ?? 0))
            ->values();
        $kedudukanKewangan = [];
        foreach ($sortedByHarga as $idx => $p) {
            $kedudukanKewangan[(int) $p->vendor_id] = $idx + 1;
        }

        return $participants->values()->map(function ($p, $idx) use (
            $total,
            $teknikalScores,
            $kerjaScores,
            $kedudukanKewangan,
            $savedByVendor,
            $isKerja
        ) {
            $vendorId = (int) $p->vendor_id;
            $vendor = $p->vendor;
            $score = $isKerja
                ? ($kerjaScores[$vendorId] ?? null)
                : ($teknikalScores[$vendorId] ?? null);
            $saved = $savedByVendor[$vendorId] ?? null;
            $bumi = (bool) ($p->is_bumiputera ?? false)
                || (bool) ($vendor?->mof_bumi ?? false)
                || (bool) ($vendor?->cidb_bumi ?? false);

            $harga = $p->harga_tawaran ?? $p->price ?? $p->amount ?? null;
            $mofStatus = '—';
            if ($vendor && method_exists($vendor, 'mofValid')) {
                $mofStatus = $vendor->mofValid() ? 'Aktif' : 'Tamat Tempoh';
            } elseif (! empty($vendor?->mof_ref_no)) {
                $mofStatus = 'Aktif';
            }

            return [
                'vendor_id' => $vendorId,
                'bil' => ($idx + 1) . '/' . $total,
                'status_bumiputra' => $bumi ? 'Ya' : 'Tidak',
                'harga_tawaran' => $harga !== null ? (float) $harga : null,
                'skor_teknikal' => $score['skor'] ?? null,
                'skor_keseluruhan' => $isKerja ? ($score['skor'] ?? null) : null,
                'kedudukan_teknikal' => $isKerja ? null : ($score['kedudukan'] ?? null),
                'kedudukan_kewangan' => $isKerja ? null : ($kedudukanKewangan[$vendorId] ?? null),
                'kedudukan_keseluruhan' => $isKerja ? ($score['kedudukan'] ?? null) : null,
                'status_mof' => $mofStatus,
                'prestasi_pembekal' => '—',
                'lembaga_pengarah_url' => null,
                'syor_urusetia' => $saved?->syor_urusetia,
                'catatan_urusetia' => $saved?->catatan_urusetia,
            ];
        })->all();
    }

    /**
     * Skor keseluruhan + kedudukan from Borang 14 (Kerja).
     *
     * @param  \Illuminate\Support\Collection<int, mixed>  $participants
     * @return array<int, array{skor: float|null, kedudukan: int|null}>
     */
    private function loadKerjaBorang14ScoresByVendor(Tender $tender, $participants): array
    {
        $scores = [];

        $evals = TenderKewanganKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->where('borang_code', 'borang14')
            ->get()
            ->keyBy(fn ($row) => (int) $row->vendor_id);

        foreach ($participants as $p) {
            $vendorId = (int) $p->vendor_id;
            $payload = $evals->get($vendorId)?->payload ?? [];
            if (is_string($payload)) {
                $payload = json_decode($payload, true) ?: [];
            }

            $skorRaw = $payload['skor_keseluruhan']
                ?? $payload['markah_terlaras']
                ?? null;

            $skor = null;
            if ($skorRaw !== null && $skorRaw !== '' && $skorRaw !== '-') {
                $skor = round((float) str_replace(',', '', (string) $skorRaw), 2);
            }

            $kedudukan = null;
            if (isset($payload['kedudukan']) && $payload['kedudukan'] !== '' && $payload['kedudukan'] !== '-') {
                $kedudukan = (int) $payload['kedudukan'];
            }

            $scores[$vendorId] = [
                'skor' => $skor,
                'kedudukan' => $kedudukan,
            ];
        }

        // Fallback kedudukan: lowest harga tender = rank 1 (same as Borang 14).
        $missingKedudukan = collect($scores)->contains(fn ($s) => ($s['kedudukan'] ?? null) === null);
        if ($missingKedudukan) {
            $ranked = $participants
                ->sortBy(fn ($p) => (float) ($p->harga_tawaran ?? $p->price ?? $p->amount ?? $p->tawaran_harga ?? 0))
                ->values();
            foreach ($ranked as $idx => $p) {
                $vendorId = (int) $p->vendor_id;
                if (($scores[$vendorId]['kedudukan'] ?? null) === null) {
                    $scores[$vendorId]['kedudukan'] = $idx + 1;
                }
            }
        }

        return $scores;
    }

    private function persistPengesyoranPembekal(Request $request, Tender $tender, bool $submit): void
    {
        $syorOptions = PerakuanJabatanPengesyoranPembekalItem::SYOR_OPTIONS;

        $rules = [
            'catatan' => ['nullable', 'string', 'max:65535'],
            // First-pass "layak bidaan" checkbox is optional; mandatory pengesahan is post-bidaan.
            'sahkan_petender_layak' => ['nullable', 'boolean'],
            'rows' => ['nullable', 'array'],
            'rows.*.vendor_id' => ['required', 'integer'],
            'rows.*.syor_urusetia' => ['nullable', 'string', Rule::in($syorOptions)],
            'rows.*.catatan_urusetia' => ['nullable', 'string', 'max:65535'],
        ];

        $validated = $request->validate($rules);
        $rows = collect($validated['rows'] ?? []);

        $allowedVendorIds = $tender->participants()
            ->where('participate', 1)
            ->whereNull('eliminated_at')
            ->pluck('vendor_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $disyorkanCount = $rows
            ->filter(fn ($row) => ($row['syor_urusetia'] ?? null) === PerakuanJabatanPengesyoranPembekalItem::SYOR_DISYORKAN)
            ->count();

        if ($disyorkanCount > 1) {
            throw ValidationException::withMessages([
                'rows' => 'Hanya satu syarikat boleh dipilih sebagai Disyorkan.',
            ]);
        }

        if ($submit) {
            $hasSyor = $rows->contains(fn ($row) => filled($row['syor_urusetia'] ?? null));
            if (! $hasSyor) {
                throw ValidationException::withMessages([
                    'rows' => 'Sila pilih Syor Urusetia untuk sekurang-kurangnya satu pembekal sebelum menghantar.',
                ]);
            }
        }

        foreach ($rows as $idx => $row) {
            $vendorId = (int) ($row['vendor_id'] ?? 0);
            if (! in_array($vendorId, $allowedVendorIds, true)) {
                throw ValidationException::withMessages([
                    "rows.$idx.vendor_id" => 'Pembekal tidak sah untuk tender ini.',
                ]);
            }
        }

        DB::transaction(function () use ($tender, $validated, $rows, $submit, $allowedVendorIds) {
            $record = PerakuanJabatanPengesyoranPembekal::firstOrCreate(
                ['tender_id' => $tender->id],
                [
                    'catatan' => null,
                    'sahkan_petender_layak' => false,
                    'submitted_at' => null,
                ]
            );

            $record->catatan = $validated['catatan'] ?? null;
            $record->sahkan_petender_layak = (bool) ($validated['sahkan_petender_layak'] ?? false);

            if ($submit) {
                $record->submitted_at = now();
            }
            $record->save();

            foreach ($rows as $row) {
                $vendorId = (int) $row['vendor_id'];

                $syor = $row['syor_urusetia'] ?? null;
                $catatan = $this->nullableTrim($row['catatan_urusetia'] ?? null);

                if ($syor === null && $catatan === null) {
                    PerakuanJabatanPengesyoranPembekalItem::query()
                        ->where('pengesyoran_pembekal_id', $record->id)
                        ->where('vendor_id', $vendorId)
                        ->delete();
                    continue;
                }

                PerakuanJabatanPengesyoranPembekalItem::query()->updateOrCreate(
                    [
                        'pengesyoran_pembekal_id' => $record->id,
                        'vendor_id' => $vendorId,
                    ],
                    [
                        'syor_urusetia' => $syor,
                        'catatan_urusetia' => $catatan,
                    ]
                );
            }

            // Drop stale rows for vendors no longer in the eligible list.
            PerakuanJabatanPengesyoranPembekalItem::query()
                ->where('pengesyoran_pembekal_id', $record->id)
                ->whereNotIn('vendor_id', $allowedVendorIds)
                ->delete();

            if ($submit) {
                app(TenderProcessStatusService::class)->syncPerakuanJabatanCompletion($tender->fresh());
            }
        });
    }

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
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

    private function persistKertasTaklimat(Request $request, Tender $tender, bool $submit): void
    {
        $header = PerakuanJabatanKertasTaklimat::firstOrCreate(
            ['tender_id' => $tender->id],
            ['catatan' => null, 'submitted_at' => null]
        );
        $this->seedDefaultKertasTaklimatItems($header);
        $this->pruneRemovedKertasTaklimatSlots($header);

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:65535'],
            'rows' => ['nullable', 'array'],
            'rows.*.id' => ['nullable', 'integer'],
            'rows.*.kandungan' => ['required_with:rows', 'string', 'max:500'],
            'rows.*.files' => ['nullable', 'array'],
            'rows.*.files.*' => ['file', 'max:10240'],
            'deleted_item_ids' => ['nullable', 'array'],
            'deleted_item_ids.*' => ['integer'],
            'deleted_file_ids' => ['nullable', 'array'],
            'deleted_file_ids.*' => ['integer'],
        ]);

        $rows = $validated['rows'] ?? [];

        DB::transaction(function () use ($header, $tender, $validated, $request, $submit, $rows) {
            foreach ($validated['deleted_file_ids'] ?? [] as $fileId) {
                $file = PerakuanJabatanKertasTaklimatItemFile::find($fileId);
                if (!$file) {
                    continue;
                }
                $item = $file->item;
                if (!$item || (int) $item->header->tender_id !== (int) $tender->id) {
                    continue;
                }
                $this->deleteStoredFile($file->file_path);
                $file->delete();
            }

            foreach ($validated['deleted_item_ids'] ?? [] as $itemId) {
                $item = PerakuanJabatanKertasTaklimatItem::find($itemId);
                if (!$item || (int) $item->kertas_taklimat_id !== (int) $header->id) {
                    continue;
                }
                if ($item->slot_key !== null) {
                    continue;
                }
                foreach ($item->files as $f) {
                    $this->deleteStoredFile($f->file_path);
                    $f->delete();
                }
                $item->delete();
            }

            $header->catatan = $validated['catatan'] ?? null;
            if ($submit) {
                $header->submitted_at = now();
            }
            $header->save();

            $maxSort = (int) $header->items()->max('sort_order');
            $itemByIndex = [];

            foreach ($rows as $idx => $row) {
                $id = $row['id'] ?? null;
                if ($id) {
                    $item = PerakuanJabatanKertasTaklimatItem::query()
                        ->where('id', $id)
                        ->where('kertas_taklimat_id', $header->id)
                        ->first();
                    if (!$item) {
                        continue;
                    }
                    if ($item->slot_key === null) {
                        $item->kandungan = $row['kandungan'];
                        $item->save();
                    }
                    $itemByIndex[$idx] = $item;
                } else {
                    $maxSort++;
                    $itemByIndex[$idx] = $header->items()->create([
                        'slot_key' => null,
                        'kandungan' => $row['kandungan'],
                        'sort_order' => $maxSort,
                    ]);
                }
            }

            foreach ($rows as $idx => $row) {
                if (!isset($itemByIndex[$idx])) {
                    continue;
                }
                $item = $itemByIndex[$idx];
                $uploads = $request->file("rows.$idx.files") ?? [];
                foreach ((array) $uploads as $upload) {
                    if ($upload instanceof UploadedFile && $upload->isValid()) {
                        $this->storeItemFile($item, $upload, (int) $tender->id);
                    }
                }
            }
        });

        app(TenderProcessStatusService::class)->syncPerakuanJabatanCompletion($tender->fresh());
    }

    private function storeItemFile(PerakuanJabatanKertasTaklimatItem $item, UploadedFile $file, int $tenderId): void
    {
        $relativeDir = 'uploads/perakuan_jabatan/' . $tenderId . '/kertas_taklimat';
        $absoluteDir = public_path($relativeDir);
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755, true);
        }
        $safeOriginal = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = date('YmdHis') . '_' . $safeOriginal;
        $file->move($absoluteDir, $fileName);
        $relative = $relativeDir . '/' . $fileName;

        $item->files()->create([
            'file_path' => $relative,
            'file_original_name' => $file->getClientOriginalName(),
        ]);
    }

    private function deleteStoredFile(string $relativePath): void
    {
        $full = public_path($relativePath);
        if (is_file($full)) {
            @unlink($full);
        }
    }
}
