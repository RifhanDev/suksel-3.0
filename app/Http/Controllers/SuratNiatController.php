<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AdvancesTenderProcessStatus;
use App\Models\JawatankuasaPerolehanPemilihanPetender;
use App\Models\PerakuanJabatanPengesyoranPembekal;
use App\Models\PerakuanJabatanPengesyoranPembekalItem;
use App\Services\StosBackendClient;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuratNiatController extends Controller
{
    use AdvancesTenderProcessStatus;

    private const FAKTOR_OPTIONS = [
        'harga_tawaran' => 'Harga tawaran',
        'pembiayaan_kontrak' => 'Pembiayaan kontrak yang dicadangkan',
        'tenaga_kerja_tempatan' => 'Penyertaan tenaga kerja tempatan/Bumiputera',
        'pemindahan_teknologi' => 'Pemindahan teknologi, latihan dan guna tenaga tempatan',
        'terma_pembayaran' => 'Terma-terma pembayaran',
        'kandungan_tempatan' => 'Kandungan tempatan (bahan, pengangkutan, insurans dan lain-lain)',
    ];

    public function __construct(private StosBackendClient $stos)
    {
        // Sama seperti PenyediaanSuratNiatController::__construct(). Halaman
        // senarai sudah dilindungi; tanpa baris ini halaman butiran dan semua
        // endpoint tindakan di bawah terdedah kepada mana-mana pengguna log masuk.
        $this->menuMiddleware('LetterOfIntent:list');
    }

    public function show(int $tender)
    {
        $tenderModel = Tender::find($tender);
        $pembekals = $this->fetchPembekals($tender);

        return view('newModule.penyediaanSuratNiat.penyediaanSuratNiat', [
            'tenderId' => $tender,
            'tenderModel' => $tenderModel,
            'pembekals' => $pembekals,
            'faktorOptions' => self::FAKTOR_OPTIONS,
        ]);
    }

    public function pembekals(int $tender)
    {
        return response()->json([
            'success' => true,
            'data' => $this->fetchPembekals($tender),
        ]);
    }

    public function savePembekals(Request $request, int $tender)
    {
        try {
            $response = $this->stos->saveSuratNiatPembekals($tender, [
                'pembekal' => $request->input('pembekal', []),
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            Log::error('Surat Niat savePembekals failed', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function suratList(int $tender)
    {
        try {
            $response = $this->stos->listSuratNiat($tender);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generateSurat(Request $request, int $tender)
    {
        try {
            $payload = $request->only(['pembekal_id', 'tujuan', 'faktor', 'faktor_lain', 'tempoh_maklumbalas_hari']);
            $payload['generated_by'] = auth()->id();

            $response = $this->stos->generateSuratNiat($tender, $payload);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            Log::error('Surat Niat generate failed', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSurat(Request $request, int $id)
    {
        try {
            $payload = $request->only(['tujuan', 'faktor', 'faktor_lain', 'tempoh_maklumbalas_hari']);
            $response = $this->stos->updateSuratNiat($id, $payload);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteSurat(int $id)
    {
        try {
            $response = $this->stos->deleteSuratNiat($id);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function download(int $id)
    {
        try {
            $response = $this->stos->downloadSuratNiat($id);

            if (! $response->successful()) {
                abort(404, 'Fail surat tidak dijumpai.');
            }

            $filename = $this->extractFilename($response->header('Content-Disposition')) ?? 'Surat_Niat_' . $id . '.docx';

            return response($response->body())
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Throwable $e) {
            abort(500, 'Gagal memuat turun surat: ' . $e->getMessage());
        }
    }

    public function hantar(int $tender)
    {
        try {
            $response = $this->stos->hantarSuratNiat($tender);

            // Panggilan STOS hanya merekod surat di backend. Menaikkan status
            // proses tender adalah keadaan tempatan — tanpa ini tender kekal
            // selama-lamanya dalam senarai indexPenyediaanSuratNiat. Logik ini
            // dipindahkan dari PenyediaanSuratNiatController::hantar() yang
            // laluannya digantikan oleh route ini.
            if ($response->successful() && ($tenderModel = Tender::find($tender))) {
                $this->advanceTenderProcess(
                    $tenderModel,
                    TenderProcessStatus::PENYEDIAAN_SURAT_NIAT,
                    TenderProcessStatus::penyediaanSuratNiatListStatus()
                );
            }

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function fetchPembekals(int $tender): array
    {
        $winners = TenderVendor::query()
            ->where('tender_id', $tender)
            ->where('winner', 1)
            ->with('vendor')
            ->orderBy('id')
            ->get();

        // Fallback when winner flag belum diisi (data JP lama): Disyorkan dari Keputusan Mesyuarat.
        if ($winners->isEmpty()) {
            $winnerVendorIds = $this->winnerVendorIdsFromKeputusanMesyuarat($tender);
            if ($winnerVendorIds !== []) {
                $winners = TenderVendor::query()
                    ->where('tender_id', $tender)
                    ->whereIn('vendor_id', $winnerVendorIds)
                    ->with('vendor')
                    ->orderBy('id')
                    ->get();
            }
        }

        $savedByVendor = [];
        try {
            $response = $this->stos->getSuratNiatPembekals($tender);
            if ($response->successful()) {
                $savedByVendor = collect($response->json('data') ?? [])
                    ->keyBy(fn ($row) => (int) ($row['vendor_id'] ?? 0))
                    ->all();
            }
        } catch (\Throwable $e) {
            Log::warning('Surat Niat pembekal fetch failed, using local winners only', [
                'tender_id' => $tender,
                'error' => $e->getMessage(),
            ]);
        }

        return $winners->map(function (TenderVendor $participation) use ($savedByVendor) {
            $vendorId = (int) $participation->vendor_id;
            $saved = $savedByVendor[$vendorId] ?? null;

            return [
                'id' => $saved['id'] ?? $participation->id,
                'vendor_id' => $vendorId,
                'vendor_name' => $saved['vendor_name'] ?? $participation->vendor?->name,
                'vendor_address' => $saved['vendor_address'] ?? $participation->vendor?->address,
                'diperlukan' => array_key_exists('diperlukan', (array) $saved)
                    ? (bool) $saved['diperlukan']
                    : (bool) ($participation->surat_niat_diperlukan ?? true),
                'catatan' => $saved['catatan'] ?? $participation->surat_niat_catatan,
            ];
        })->values()->all();
    }

    /**
     * Vendor IDs selected as winners in Jawatankuasa Perolehan (Keputusan Mesyuarat).
     *
     * @return list<int>
     */
    private function winnerVendorIdsFromKeputusanMesyuarat(int $tenderId): array
    {
        $fromJp = JawatankuasaPerolehanPemilihanPetender::query()
            ->whereHas('item', fn ($q) => $q->where('tender_id', $tenderId))
            ->where('keputusan_urusetia', PerakuanJabatanPengesyoranPembekalItem::SYOR_DISYORKAN)
            ->whereNotNull('vendor_id')
            ->pluck('vendor_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($fromJp !== []) {
            return array_values(array_unique($fromJp));
        }

        $pengesyoran = PerakuanJabatanPengesyoranPembekal::query()
            ->where('tender_id', $tenderId)
            ->first();

        if (! $pengesyoran) {
            return [];
        }

        return PerakuanJabatanPengesyoranPembekalItem::query()
            ->where('pengesyoran_pembekal_id', $pengesyoran->id)
            ->where('syor_urusetia', PerakuanJabatanPengesyoranPembekalItem::SYOR_DISYORKAN)
            ->pluck('vendor_id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function extractFilename(?string $contentDisposition): ?string
    {
        if (! $contentDisposition) {
            return null;
        }

        return preg_match('/filename="?([^"]+)"?/', $contentDisposition, $matches) ? $matches[1] : null;
    }
}
