<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Http\Controllers\Concerns\ResolvesTenderForProcess;
use App\Services\StosBackendClient;
use App\Support\TenderProcessStatus;
use App\Tender;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PenyediaanSuratSstController extends Controller
{
    use AdvancesTenderProcessStatus;
    use ResolvesTenderForProcess;

    /** Kategori perolehan 1/2 = bekalan/perkhidmatan, 3 = kerja. */
    private const KATEGORI_KERJA = 3;

    public function __construct(
        private StosBackendClient $stos,
    ) {
        $this->menuMiddleware('SST:list');
    }

    public function index()
    {

        // Tenders that have been sent on stay listed so their status is still visible.
        $pending = TenderProcessStatus::penyediaanSuratSstListStatus();
        $sent = TenderProcessStatus::PENYEDIAAN_SURAT_SST;

        $tenders = Tender::query()
            ->whereIn('status_process_id', [$pending, $sent])
            ->orderByDesc('id')
            ->get()
            ->map(function (Tender $tender) use ($sent) {
                $row = $this->mapTenderAdvertRow($tender, 'penyediaanSST');
                $row['dihantar'] = (int) $tender->status_process_id === $sent;

                return $row;
            })
            ->values()
            ->all();

        return view('newModule.penyediaanSST.index', compact('tenders'));
    }

    public function show(Request $request)
    {
        $tender = $this->resolveTenderByIdentifier($request->query('tender'));

        abort_if(! $tender, 404, 'Tender tidak ditemui.');

        return view('newModule.penyediaanSST.penyediaanSST', [
            'tender' => $tender,
            'noTender' => $tender->no_tender ?: $tender->ref_number ?: '-',
            'jenisPerolehanLabel' => $tender->isSebutHargaKaedah() ? 'Sebut Harga' : 'Tender',
            'tunjukPemilihanItem' => (int) $tender->kategori_perolehan_id !== self::KATEGORI_KERJA,
            'tempohSahLaku' => $this->tempohSahLaku($tender),
            'tempohKontrak' => $tender->tempoh_kontrak_bulan,
        ]);
    }

    /** Winning vendors plus each one's SST status. */
    public function pembekal(Tender $tender): JsonResponse
    {
        $result = $this->callStos('load pembekal sst', ['tender_id' => $tender->id],
            fn () => $this->stos->getSstPembekal($tender->id));

        if (! $result['ok']) {
            return response()->json(['message' => 'Ralat memuatkan senarai pembekal.'], $result['status']);
        }

        return response()->json(['rows' => $result['body']['data'] ?? []]);
    }

    /** Letters already issued for this tender. */
    public function senaraiSurat(Tender $tender): JsonResponse
    {
        $result = $this->callStos('load senarai surat sst', ['tender_id' => $tender->id],
            fn () => $this->stos->getSstSenaraiSurat($tender->id));

        if (! $result['ok']) {
            return response()->json(['message' => 'Ralat memuatkan senarai surat.'], $result['status']);
        }

        return response()->json(['rows' => $result['body']['data'] ?? []]);
    }

    /** Saved SST for one vendor, or null when it has not been started. */
    public function sst(Tender $tender, int $vendorId): JsonResponse
    {
        $result = $this->callStos('load sst', ['tender_id' => $tender->id, 'vendor_id' => $vendorId],
            fn () => $this->stos->getSst($tender->id, $vendorId));

        if (! $result['ok']) {
            return response()->json(['message' => 'Ralat memuatkan maklumat SST.'], $result['status']);
        }

        return response()->json(['sst' => $result['body']['data'] ?? null]);
    }

    public function simpan(Request $request): JsonResponse
    {
        $payload = $this->validatePayload($request);

        $result = $this->callStos('simpan sst', ['tender' => $payload['tender']],
            fn () => $this->stos->simpanSst($payload));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menyimpan maklumat SST.',
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message'] ?: 'Maklumat SST telah disimpan.',
            'sst' => $result['body']['data'] ?? null,
        ]);
    }

    /** Generates one vendor's letter and locks that SST. Does not advance the tender. */
    public function jana(Request $request): JsonResponse
    {
        $payload = $this->validatePayload($request);

        $result = $this->callStos('jana sst', ['tender' => $payload['tender']],
            fn () => $this->stos->hantarSst($payload));

        if (! $result['ok']) {
            return response()->json([
                'message' => $result['message'] ?: 'Ralat semasa menjana Surat Setuju Terima.',
            ], $result['status']);
        }

        return response()->json([
            'message' => $result['message'] ?: 'Surat Setuju Terima berjaya dijana.',
            'sst' => $result['body']['data'] ?? null,
        ]);
    }

    /** Ends the step — only once every winning vendor has a generated letter. */
    public function hantar(Request $request): JsonResponse
    {
        $request->validate(['tender' => 'required|string']);
        $tender = $this->resolveTenderByIdentifier($request->input('tender'));

        if (! $tender) {
            return response()->json(['message' => 'Tender tidak ditemui.'], 404);
        }

        $result = $this->callStos('semak pembekal sst', ['tender_id' => $tender->id],
            fn () => $this->stos->getSstPembekal($tender->id));

        if (! $result['ok']) {
            return response()->json(['message' => 'Ralat memuatkan senarai pembekal.'], $result['status']);
        }

        $pembekal = $result['body']['data'] ?? [];

        if (empty($pembekal)) {
            return response()->json(['message' => 'Tiada pembekal berjaya direkodkan bagi tender ini.'], 422);
        }

        $belumJana = collect($pembekal)->filter(fn (array $row) => ($row['status'] ?? null) !== 'submitted');

        if ($belumJana->isNotEmpty()) {
            return response()->json([
                'message' => 'Sila jana Surat Setuju Terima bagi semua pembekal terlebih dahulu (' . $belumJana->count() . ' belum dijana).',
            ], 422);
        }

        if (! $this->advanceTenderProcess(
            $tender,
            TenderProcessStatus::PENYEDIAAN_SURAT_SST,
            TenderProcessStatus::penyediaanSuratSstListStatus()
        )) {
            return response()->json(['message' => 'Tender belum sedia untuk penyediaan surat SST.'], 422);
        }

        return response()->json(['message' => 'Penyediaan Surat Setuju Terima berjaya dihantar.']);
    }

    /** Content branches on kategori perolehan. Includes Lampiran A. */
    public function suratSetujuTerima(Request $request, Tender $tender, int $vendorId)
    {
        return $this->dokumen($request, $tender, $vendorId, 'surat_setuju_terima', 'Surat Setuju Terima');
    }

    /** Lampiran B — signed and returned by the vendor together with the SST. */
    public function suratAkuanPembidaBerjaya(Request $request, Tender $tender, int $vendorId)
    {
        return $this->dokumen($request, $tender, $vendorId, 'surat_akuan_pembida_berjaya', 'Surat Akuan Pembida Berjaya');
    }

    /** Lampiran C — sworn declaration signed by the vendor before a commissioner for oaths. */
    public function suratAkuanSumpahSyarikat(Request $request, Tender $tender, int $vendorId)
    {
        return $this->dokumen($request, $tender, $vendorId, 'surat_akuan_sumpah_syarikat', 'Surat Akuan Sumpah Syarikat');
    }

    /**
     * Renders one letter. ?format=word converts the same print view into a Word document,
     * so both formats always carry identical content.
     */
    private function dokumen(Request $request, Tender $tender, int $vendorId, string $view, string $title)
    {
        $data = $this->dokumenData($tender, $vendorId);
        $rendered = view("newModule.penyediaanSST.pdf.{$view}", $data);

        if ($request->query('format') !== 'word') {
            return $rendered;
        }

        $filename = $title . ' - ' . str_replace(['/', '\\'], '-', $data['noTender']);

        return response(\App\Support\PrintViewToWord::convert($rendered->render(), $title))
            ->header('Content-Type', 'application/vnd.ms-word; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '.doc"');
    }

    /** Shared view data for every generated SST document. */
    private function dokumenData(Tender $tender, int $vendorId): array
    {
        $sstResult = $this->callStos('load sst (dokumen)', ['tender_id' => $tender->id, 'vendor_id' => $vendorId],
            fn () => $this->stos->getSst($tender->id, $vendorId));

        abort_if(! $sstResult['ok'], 502, 'Ralat memuatkan maklumat SST.');

        $sst = $sstResult['body']['data'] ?? null;

        abort_if(! $sst, 404, 'Surat Setuju Terima belum disediakan.');

        $pembekalResult = $this->callStos('load pembekal (dokumen)', ['tender_id' => $tender->id],
            fn () => $this->stos->getSstPembekal($tender->id));

        $pembekal = collect($pembekalResult['ok'] ? ($pembekalResult['body']['data'] ?? []) : [])
            ->firstWhere('vendor_id', $vendorId) ?? [];

        // Letter is dated when it was signed; falls back to today for an unsigned preview.
        $tarikhSurat = $sst['signed_at'] ? Carbon::parse($sst['signed_at']) : now();

        $kategoriPerolehan = $this->kategoriPerolehanSlug($tender);
        $taxRate = (float) ($sst['tax_rate'] ?? 0);

        return [
            'tender' => $tender,
            'noTender' => $tender->no_tender ?: $tender->ref_number ?: '-',
            'jenisPerolehanLabel' => $tender->isSebutHargaKaedah() ? 'Sebut Harga' : 'Tender',
            'kategoriPerolehan' => $kategoriPerolehan,
            'agensiPelaksana' => $tender->tenderer?->name ?? '-',
            'sst' => $sst,
            'pembekal' => $pembekal,
            'tarikhSurat' => $this->formatTarikhMalay($tarikhSurat),
            'penandatangan' => '-',
            'jawatanPenandatangan' => '-',
            'taxClause' => $this->taxClauseVariant($taxRate, $pembekal, $kategoriPerolehan),
            'taxRateFormatted' => rtrim(rtrim(number_format($taxRate, 2), '0'), '.'),
            'taxLabel' => $kategoriPerolehan === 'kerja' ? 'cukai jualan' : 'cukai perkhidmatan/cukai jualan',
            'lampiranA' => $this->lampiranAValues($tender, $sst, $pembekal),
            'clause4Documents' => $this->clause4Documents($sst, $kategoriPerolehan),
            'clause5Documents' => $this->clause5Documents($sst, $kategoriPerolehan),
            'protege' => $this->protegeVariant($sst),
            'amounts' => [
                'bond' => $this->amountInWords((float) ($sst['bond_value'] ?? 0)),
                // No insurance value is captured anywhere yet, so it prints as zero.
                'insurance' => $this->amountInWords(0),
                'contract' => $this->amountInWords((float) ($sst['total_amount'] ?? 0)),
            ],
            // Repeated in the running header on every page.
            'documentNo' => $sst['document_no'] ?: '...................................',
            'rujukanKami' => $sst['file_reference_no'] ?: '',
            'tempohKontrak' => $tender->tempoh_kontrak_bulan ? $tender->tempoh_kontrak_bulan . ' Bulan' : '',
        ];
    }

    /**
     * Contract details printed in Lampiran A. Anything with no source yet comes back as an
     * empty string so the view prints a fill-in rule instead.
     */
    private function lampiranAValues(Tender $tender, array $sst, array $pembekal): array
    {
        $offer = (float) ($sst['offer_price'] ?? 0);
        $taxRate = (float) ($sst['tax_rate'] ?? 0);
        $total = (float) ($sst['total_amount'] ?? 0);
        $bondPercentage = $sst['bond_percentage'] === null ? null : (float) $sst['bond_percentage'];

        return [
            'tajuk' => mb_strtoupper($tender->name ?? ''),

            'no_pendaftaran' => $pembekal['registration'] ?? '',
            'tempoh_sah_laku_pendaftaran' => $this->formatTarikhRingkas($pembekal['ssm_expiry'] ?? null),

            'mof_no' => $pembekal['mof_ref_no'] ?? '',
            'mof_tempoh' => $this->formatJulatTarikh($pembekal['mof_start_date'] ?? null, $pembekal['mof_end_date'] ?? null),
            'mof_kod_bidang' => '',
            'mof_taraf' => array_key_exists('mof_bumi', $pembekal)
                ? ($pembekal['mof_bumi'] ? 'Bumiputera' : 'Bukan Bumiputera')
                : '',
            'mof_tempoh_bumiputera' => '',

            'cukai_no' => $pembekal['gst_no'] ?? '',
            'cukai_tarikh_kuat_kuasa' => '',

            'harga_tawaran' => number_format($offer, 2),
            'cukai_jualan' => number_format($offer * $taxRate / 100, 2),
            'fi_eperolehan' => '',
            'harga_kontrak' => number_format($total, 2),
            'tempoh_kontrak' => $tender->tempoh_kontrak_bulan ? $tender->tempoh_kontrak_bulan . ' Bulan' : '',
            'tarikh_mula' => $this->formatTarikhRingkas($sst['effective_date'] ?? null),
            'tarikh_tamat' => $this->formatTarikhRingkas($sst['end_date'] ?? null),

            'bon_kadar' => $bondPercentage === null ? '' : rtrim(rtrim(number_format($bondPercentage, 2), '0'), '.') . '%',
            'bon_formula' => $bondPercentage === null
                ? '2.5% atau 5% daripada nilai kontrak setahun'
                : rtrim(rtrim(number_format($bondPercentage, 2), '0'), '.') . '% daripada nilai kontrak setahun',
            'bon_nilai' => $sst['bond_value'] === null ? '' : number_format((float) $sst['bond_value'], 2),

            'nilai_polisi' => number_format(0, 2),
            'lad_kadar_sehari' => '',

            'protege_tertakluk' => ! empty($sst['protege_rtw']) ? 'YA' : 'TIDAK',
            // Not calculated yet — the basis for the participant count is still open.
            'protege_peserta' => '',

            'icp_nilai_bon' => '',
            'icp_kadar_bon' => '',

            'pentadbir_nama' => $pembekal['pegawai'] ?? '',
            'pentadbir_telefon' => $pembekal['tel_bimbit'] ?? ($pembekal['tel'] ?? ''),
            'pentadbir_emel' => $pembekal['email'] ?? '',
        ];
    }

    private function formatTarikhRingkas(?string $date): string
    {
        return $date ? Carbon::parse($date)->format('d/m/Y') : '';
    }

    private function formatJulatTarikh(?string $from, ?string $to): string
    {
        if (! $from && ! $to) {
            return '';
        }

        return trim($this->formatTarikhRingkas($from) . ' - ' . $this->formatTarikhRingkas($to), ' -');
    }

    /** Amount as words and figures, matching perkataanWang() on the Penyediaan SST form. */
    private function amountInWords(float $amount): array
    {
        $formatter = new \NumberFormatter('ms', \NumberFormatter::SPELLOUT);
        $ringgit = (int) floor($amount);
        $sen = (int) round(($amount - $ringgit) * 100);

        // Rounding can carry the sen up to a whole ringgit.
        if ($sen === 100) {
            $ringgit++;
            $sen = 0;
        }

        $words = $formatter->format($ringgit);

        if ($sen > 0) {
            $words .= ' Dan ' . $formatter->format($sen) . ' Sen';
        }

        return [
            'words' => mb_strtoupper($words),
            'figure' => number_format($amount, 2),
        ];
    }

    /**
     * Documents listed under paragraph 4, in order. Bond and insurance appear only when the
     * SST requires them, so the remaining items re-letter themselves. Works contracts carry
     * a different paragraph 4 altogether and list nothing.
     */
    private function clause4Documents(array $sst, string $kategoriPerolehan): array
    {
        if ($kategoriPerolehan === 'kerja') {
            return [];
        }

        return array_values(array_filter([
            ! empty($sst['performance_bond']) ? 'bond' : null,
            ! empty($sst['insurance']) ? 'insurance' : null,
            'perkeso',
            'kwsp',
        ]));
    }

    /** Contract value at or below which the PROTEGE-RTW programme is encouraged, not required. */
    private const PROTEGE_THRESHOLD = 200000;

    /**
     * Whether PROTEGE-RTW is required, merely encouraged, or does not apply at all. The
     * contract value separates the first two. Where the resulting paragraph sits differs
     * by category, so this deliberately says nothing about numbering.
     */
    private function protegeVariant(array $sst): string
    {
        if (empty($sst['protege_rtw'])) {
            return 'none';
        }

        return (float) ($sst['total_amount'] ?? 0) > self::PROTEGE_THRESHOLD
            ? 'above_threshold'
            : 'below_threshold';
    }

    /**
     * Documents listed under paragraph 5 of a works contract, which carries its own list
     * covering two separate insurance policies. Supply and services list theirs under
     * paragraph 4 instead, so this returns nothing for them.
     */
    private function clause5Documents(array $sst, string $kategoriPerolehan): array
    {
        if ($kategoriPerolehan !== 'kerja') {
            return [];
        }

        $hasInsurance = ! empty($sst['insurance']);

        return array_values(array_filter([
            ! empty($sst['performance_bond']) ? 'bond' : null,
            $hasInsurance ? 'public_liability_insurance' : null,
            $hasInsurance ? 'works_insurance' : null,
            'perkeso',
            'kwsp',
        ]));
    }

    /**
     * Which wording paragraph 3 of the SST letter uses. Works contracts always take the
     * registered wording; supply and services depend on the vendor's JKDM (CBP) number.
     */
    private function taxClauseVariant(float $taxRate, array $pembekal, string $kategoriPerolehan): string
    {
        if ($taxRate <= 0) {
            return 'exempt';
        }

        if ($kategoriPerolehan === 'kerja') {
            return 'registered';
        }

        return trim((string) ($pembekal['gst_no'] ?? '')) !== '' ? 'registered' : 'unregistered';
    }

    private function kategoriPerolehanSlug(Tender $tender): string
    {
        return match ((int) $tender->kategori_perolehan_id) {
            1 => 'perkhidmatan',
            2 => 'bekalan',
            self::KATEGORI_KERJA => 'kerja',
            default => 'bekalan',
        };
    }

    private function formatTarikhMalay(Carbon $date): string
    {
        $bulanMs = ['', 'Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'];

        return $date->day . ' ' . $bulanMs[$date->month] . ' ' . $date->year;
    }

    /** Uploads land on suksel's public disk; only the metadata travels to the API. */
    public function muatNaikDokumen(Request $request, Tender $tender): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("uploads/sst/{$tender->id}", $storedName, 'public');

        return response()->json([
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    private function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'tender' => 'required|string',
            'vendor_id' => 'required|integer',
            'file_reference_no' => 'nullable|string|max:120',
            'title' => 'nullable|string|max:2000',
            'offer_price' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|in:0,6,8,10',
            'insurance' => 'nullable|boolean',
            'online_verification' => 'nullable|boolean',
            'protege_rtw' => 'nullable|boolean',
            'contract_duration' => 'nullable|integer|min:0',
            'effective_date' => 'nullable|date',
            'agreement_required' => 'nullable|boolean',
            'documents' => 'nullable|array',
            'documents.*.document_name' => 'nullable|string|max:255',
            'documents.*.original_name' => 'nullable|string|max:255',
        ]);

        return array_merge($validated, ['acting_user_id' => Auth::id()]);
    }

    /** Only lives in the Penyediaan Iklan meta — never mirrored onto tenders. */
    private function tempohSahLaku(Tender $tender): array
    {
        $record = \App\Models\PenyediaanIklan::query()->where('tender_id', $tender->id)->first();
        $iklan = ($record && is_array($record->meta)) ? ($record->meta['iklan'] ?? []) : [];

        return [
            'tempoh' => $iklan['tempoh_sah_laku'] ?? null,
            'tamat' => $iklan['sah_laku_tamat'] ?? null,
        ];
    }

    private function callStos(string $action, array $context, \Closure $request): array
    {
        try {
            $response = $request();
            $body = $response->json() ?? [];

            if ($response->successful()) {
                return ['ok' => true, 'body' => $body, 'status' => 200, 'message' => $body['message'] ?? ''];
            }

            Log::error('Backend API error', array_merge($context, [
                'action' => $action,
                'status' => $response->status(),
                'body' => $response->body(),
            ]));

            return [
                'ok' => false,
                'body' => $body,
                'status' => in_array($response->status(), [404, 422], true) ? $response->status() : 502,
                'message' => $body['message'] ?? '',
            ];
        } catch (\Throwable $e) {
            Log::error('Backend API unreachable', array_merge($context, [
                'action' => $action,
                'error' => $e->getMessage(),
            ]));

            return ['ok' => false, 'body' => [], 'status' => 502, 'message' => ''];
        }
    }

    private function mapTenderAdvertRow(Tender $tender, string $routeName): array
    {
        return [
            'uuid' => $tender->uuid,
            'no_tender' => $tender->no_tender ?: $tender->ref_number ?: '-',
            'name' => $tender->name ?: '-',
            'tarikh_jual' => $tender->advertise_start_date
                ? \Carbon\Carbon::parse($tender->advertise_start_date)->format('d/m/Y')
                : '-',
            'tarikh_tutup' => $tender->advertise_stop_date
                ? \Carbon\Carbon::parse($tender->advertise_stop_date)->format('d/m/Y')
                : '-',
            'harga' => number_format((float) ($tender->price ?? 0), 2),
            'show_url' => route($routeName, ['tender' => $tender->uuid]),
        ];
    }
}
