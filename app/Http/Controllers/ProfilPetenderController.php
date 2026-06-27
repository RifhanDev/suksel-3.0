<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfilPetenderController extends Controller
{
    use HandlesTenderFormAccess;

    public function index(string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);

        $apiPath = 'profil-petenders/' . $tenderUuid;
        $response = $this->isVendorFormMode()
            ? $this->api()->get($this->stosUrlWithVendor($apiPath))
            : $this->api()->get($this->url($apiPath));

        $profilData = null;

        if ($response->successful()) {
            $profilData = $response->json('data');
        } else {
            Log::warning('ProfilPetenderController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        if ($this->isVendorFormMode() && empty($profilData)) {
            $profilData = $this->loadVendorFormPayload($tender, 'profil_petender');
        }

        $aj = (float) ($tender->anggaran_jabatan ?? 0);
        $automatikRowsBerbayar   = $this->calcAutomatikRowsBerbayar($aj);
        $automatikRowsDibenarkan = $this->calcAutomatikRowsDibenarkan($aj);

        return view('jawatankuasaSpesifikasi.profil_petender', array_merge([
            'tender' => $tender,
            'profilData' => $profilData,
            'automatikRowsBerbayar' => $automatikRowsBerbayar,
            'automatikRowsDibenarkan' => $automatikRowsDibenarkan,
            'showVendorForm' => $this->isVendorFormMode() || $this->isFormViewOnly(),
            'showScoringConfig' => ! $this->isVendorFormMode() && ! $this->isFormViewOnly(),
        ], $this->formViewVars($tender)));
    }

    public function store(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $payload = $request->except('_token');
        if ($this->isVendorFormMode()) {
            $payload['vendor_id'] = $this->vendorId();
        }

        $response = $this->api()->post($this->url('profil-petenders/' . $tenderUuid), $payload);

        if ($this->isVendorFormMode()) {
            $this->persistVendorFormPayload($tender, 'profil_petender', $payload);
            $this->trackVendorFormSubmitted($tender, 'profil_petender', [
                'text' => 'Profil petender disimpan',
            ]);

            if (! $response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Disimpan secara tempatan.',
                    'warning' => 'Penyegerakan STOS gagal.',
                ]);
            }

            return response()->json($response->json(), $response->status());
        }

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $payload = $request->except('_token');
        if ($this->isVendorFormMode()) {
            $payload['vendor_id'] = $this->vendorId();
        }

        $response = $this->api()->post($this->url('profil-petenders/' . $tenderUuid . '/submit'), $payload);

        if ($response->successful() && $this->isVendorFormMode()) {
            $this->persistVendorFormPayload($tender, 'profil_petender', $payload);
            $this->trackVendorFormSubmitted($tender, 'profil_petender', [
                'text' => 'Profil petender dihantar',
            ]);
        }

        return response()->json($response->json(), $response->status());
    }

    private function calcAutomatikRowsBerbayar(float $aj): array
    {
        if ($aj > 50 && $aj <= 500000) {
            $hingga1 = round((0.015 * $aj) - 0.01, 2);
        } elseif ($aj > 500000) {
            $hingga1 = round((0.030 * $aj) - 0.01, 2);
        } else {
            $hingga1 = 0.00;
        }

        $dari2 = round($hingga1 + 0.01, 2);

        return [
            ['dari' => 0.00,   'hingga' => $hingga1, 'skema' => '0'],
            ['dari' => $dari2, 'hingga' => null,      'skema' => '10'],
        ];
    }

    private function calcAutomatikRowsDibenarkan(float $aj): array
    {
        if ($aj > 50 && $aj <= 500000) {
            $hingga1 = round((0.015 * $aj) - 0.01, 2);
        } elseif ($aj > 500000) {
            $hingga1 = round((0.030 * $aj) - 0.01, 2);
        } else {
            $hingga1 = 0.00;
        }

        $dari2 = round($hingga1 + 0.01, 2);

        return [
            ['dari' => 0.00,   'hingga' => $hingga1, 'skema' => '0'],
            ['dari' => $dari2, 'hingga' => null,      'skema' => '10'],
        ];
    }

    private function findTender(string $tenderUuid): Tender
    {
        return Tender::with('tenderer')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where('tenders.uuid', $tenderUuid)
            ->firstOrFail();
    }

    private function api()
    {
        return StosBackendClient::http();
    }

    private function url(string $path): string
    {
        return StosBackendClient::apiUrl($path);
    }
}
