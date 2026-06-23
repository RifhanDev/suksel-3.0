<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecificationPricingController extends Controller
{
    public function index(string $spesifikasiUuid)
    {
        $this->ensureAccess();
        $tender = $this->resolveTender($spesifikasiUuid);
        $templateData = $this->resolveSpecificationTemplate($spesifikasiUuid);

        $apiUrl   = $this->url('specification-pricings/' . $spesifikasiUuid);
        $response = $this->api()->get($apiUrl);

        $pricingData = null;

        if ($response->successful()) {
            $pricingData = $response->json('data');
        } else {
            Log::warning('SpecificationPricingController@index: API request failed', [
                'spesifikasi_uuid' => $spesifikasiUuid,
                'status'           => $response->status(),
                'body'             => $response->body(),
            ]);
        }

        return view('jawatankuasaSpesifikasi.spesifikasi_kewangan', compact('spesifikasiUuid', 'pricingData', 'tender', 'templateData'));
    }

    public function store(Request $request, string $spesifikasiUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('specification-pricings/' . $spesifikasiUuid), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    public function submit(Request $request, string $spesifikasiUuid)
    {
        $this->ensureAccess();

        $response = $this->api()->post($this->url('specification-pricings/' . $spesifikasiUuid . '/submit'), $request->except('_token'));

        return response()->json($response->json(), $response->status());
    }

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function resolveTender(string $spesifikasiUuid): ?Tender
    {
        return Tender::with('tenderer')
            ->join('technical_checklist_headers as tch', 'tch.tender_id', '=', 'tenders.id')
            ->join('technical_checklist_items as tci', 'tci.technical_checklist_header_id', '=', 'tch.id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where(function ($query) use ($spesifikasiUuid) {
                $query->where('tci.uuid', $spesifikasiUuid)
                    ->orWhere('sp.uuid', $spesifikasiUuid);
            })
            ->first();
    }

    private function resolveSpecificationTemplate(string $spesifikasiUuid): ?object
    {
        return DB::table('technical_checklist_items as tci')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->select([
                'tci.title as checklist_title',
                'tci.score as checklist_score',
                'tci.status as checklist_status',
                'tsd.uuid as document_uuid',
                'tsd.title as document_title',
                'tsd.item_type',
                'tsd.specification_type',
                'tsd.goods_type',
                'tsd.weighting_type',
                'tsd.physical_submission',
                'tsd.status as document_status',
                'tsd.total_score',
            ])
            ->where(function ($query) use ($spesifikasiUuid) {
                $query->where('tci.uuid', $spesifikasiUuid)
                    ->orWhere('sp.uuid', $spesifikasiUuid);
            })
            ->first();
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
