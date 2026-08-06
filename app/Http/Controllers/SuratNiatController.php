<?php

namespace App\Http\Controllers;

use App\Services\StosBackendClient;
use App\Tender;
use App\TenderVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuratNiatController extends Controller
{
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

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function fetchPembekals(int $tender): array
    {
        try {
            $response = $this->stos->getSuratNiatPembekals($tender);
            $saved = collect($response->successful() ? $response->json('data') : []);

            if ($saved->isNotEmpty()) {
                return $saved->all();
            }
        } catch (\Throwable $e) {
            Log::warning('Surat Niat pembekal fetch failed, falling back to local candidates', ['error' => $e->getMessage()]);
        }

        return TenderVendor::where('tender_id', $tender)
            ->where('submitted', 1)
            ->with('vendor')
            ->get()
            ->map(fn ($participation) => [
                'id' => $participation->id,
                'vendor_id' => $participation->vendor_id,
                'vendor_name' => $participation->vendor?->name,
                'vendor_address' => $participation->vendor?->address,
                'diperlukan' => $participation->surat_niat_diperlukan ?? true,
                'catatan' => $participation->surat_niat_catatan,
            ])
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
