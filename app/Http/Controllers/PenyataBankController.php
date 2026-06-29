<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesTenderFormAccess;
use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenyataBankController extends Controller
{
    use HandlesTenderFormAccess;

    public function index(string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);

        $apiPath = 'penyata-banks/' . $tenderUuid;
        $response = $this->isVendorFormMode()
            ? $this->api()->get($this->stosUrlWithVendor($apiPath))
            : $this->api()->get($this->url($apiPath));

        $penyataData = null;

        if ($response->successful()) {
            $penyataData = $response->json('data');
        } else {
            Log::warning('PenyataBankController@index: API request failed', [
                'tender_uuid' => $tenderUuid,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);
        }

        if ($this->isVendorFormMode() && empty($penyataData)) {
            $penyataData = $this->loadVendorFormPayload($tender, 'penyata_bank');
        }

        return view('jawatankuasaSpesifikasi.penyata_bank', array_merge([
            'tender' => $tender,
            'penyataData' => $penyataData,
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

        $response = $this->api()->post($this->url('penyata-banks/' . $tenderUuid), $payload);

        if ($this->isVendorFormMode()) {
            $this->persistVendorFormPayload($tender, 'penyata_bank', $payload);
            $this->trackVendorFormSubmitted($tender, 'penyata_bank', [
                'text' => 'Penyata bank disimpan',
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

        $response = $this->api()->post($this->url('penyata-banks/' . $tenderUuid . '/submit'), $payload);

        if ($response->successful() && $this->isVendorFormMode()) {
            $this->trackVendorFormSubmitted($tender, 'penyata_bank', [
                'text' => 'Penyata bank dihantar',
            ]);
        }

        return response()->json($response->json(), $response->status());
    }

    public function uploadFile(Request $request, string $tenderUuid)
    {
        $tender = $this->findTender($tenderUuid);
        $this->ensureTenderFormAccess($tender);
        $this->ensureFormEditable();

        $extra = $request->except(['_token', 'file']);
        if ($this->isVendorFormMode()) {
            $extra['vendor_id'] = $this->vendorId();
        }

        $response = $this->api()->attach(
            'file',
            $request->file('file')->get(),
            $request->file('file')->getClientOriginalName()
        )->post($this->url('penyata-banks/' . $tenderUuid . '/files'), $extra);

        return response()->json($response->json(), $response->status());
    }

    public function deleteFile(string $fileUuid)
    {
        $user = auth()->user();
        if (! $user->hasRole('Admin') && ! $user->can('tender:specification-management') && ! $user->vendor_id) {
            abort(403);
        }
        $this->ensureFormEditable();

        $response = $this->api()->delete($this->url('penyata-bank-files/' . $fileUuid));

        return response()->json($response->json(), $response->status());
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
