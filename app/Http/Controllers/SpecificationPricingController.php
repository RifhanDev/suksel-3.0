<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Services\StosBackendClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SpecificationPricingController extends Controller
{
    public function index(string $spesifikasiUuid)
    {
        $this->ensureAccess();
        $targetTechItemUuid = $this->resolveTechnicalItemUuid($spesifikasiUuid);
        $tender = $this->resolveTender($spesifikasiUuid);
        $templateData = $this->resolveSpecificationTemplate($spesifikasiUuid);

        $pricingData = null;

        try {
            $apiUrl   = $this->url('specification-pricings/' . $targetTechItemUuid);
            $response = $this->api()->get($apiUrl);

            if ($response->successful()) {
                $pricingData = $response->json('data');
            } else {
                Log::warning('SpecificationPricingController@index: API request failed', [
                    'spesifikasi_uuid' => $spesifikasiUuid,
                    'status'           => $response->status(),
                    'body'             => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('SpecificationPricingController@index: API exception', [
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback to direct DB query if API failed or returned empty data
        if (!$pricingData || empty($pricingData['items'])) {
            $pricingData = $this->buildFallbackPricingData($spesifikasiUuid);
        }

        return view('jawatankuasaSpesifikasi.spesifikasi_kewangan', compact('spesifikasiUuid', 'pricingData', 'tender', 'templateData'));
    }

    public function store(Request $request, string $spesifikasiUuid)
    {
        $this->ensureAccess();
        $targetTechItemUuid = $this->resolveTechnicalItemUuid($spesifikasiUuid);

        try {
            $response = $this->api()->post($this->url('specification-pricings/' . $targetTechItemUuid), $request->except('_token'));
            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }
        } catch (\Throwable $e) {
            Log::warning('SpecificationPricingController@store: API exception, attempting local fallback', ['error' => $e->getMessage()]);
        }

        // Local fallback store
        $result = $this->storeLocalFallback($spesifikasiUuid, $request->all());
        return response()->json($result);
    }

    public function submit(Request $request, string $spesifikasiUuid)
    {
        $this->ensureAccess();
        $targetTechItemUuid = $this->resolveTechnicalItemUuid($spesifikasiUuid);

        try {
            $response = $this->api()->post($this->url('specification-pricings/' . $targetTechItemUuid . '/submit'), $request->except('_token'));
            if ($response->successful()) {
                return response()->json($response->json(), $response->status());
            }
        } catch (\Throwable $e) {
            Log::warning('SpecificationPricingController@submit: API exception, attempting local fallback', ['error' => $e->getMessage()]);
        }

        // Local fallback submit
        $result = $this->submitLocalFallback($spesifikasiUuid);
        return response()->json($result);
    }

    private function ensureAccess(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('Admin') && !$user->can('tender:specification-management')) {
            abort(403);
        }
    }

    private function resolveTechnicalItemUuid(string $spesifikasiUuid): string
    {
        $techItem = DB::table('technical_checklist_items as tci')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->where('tci.uuid', $spesifikasiUuid)
            ->orWhere('sp.uuid', $spesifikasiUuid)
            ->orWhere('tsd.uuid', $spesifikasiUuid)
            ->select('tci.uuid')
            ->first();

        if ($techItem && $techItem->uuid) {
            return $techItem->uuid;
        }

        // Support financial_checklist_items.uuid lookup
        $fciItem = DB::table('financial_checklist_items as fci')
            ->join('financial_checklist_headers as fch', 'fch.id', '=', 'fci.financial_checklist_header_id')
            ->leftJoin('technical_checklist_headers as tch', 'tch.tender_id', '=', 'fch.tender_id')
            ->leftJoin('technical_checklist_items as tci', function ($j) {
                $j->on('tci.technical_checklist_header_id', '=', 'tch.id')
                  ->where('tci.source_type', 'specification_document');
            })
            ->where('fci.uuid', $spesifikasiUuid)
            ->select('tci.uuid')
            ->first();

        return ($fciItem && $fciItem->uuid) ? $fciItem->uuid : $spesifikasiUuid;
    }

    private function resolveTender(string $spesifikasiUuid): ?Tender
    {
        $targetUuid = $this->resolveTechnicalItemUuid($spesifikasiUuid);

        $tender = Tender::with('tenderer')
            ->join('technical_checklist_headers as tch', 'tch.tender_id', '=', 'tenders.id')
            ->join('technical_checklist_items as tci', 'tci.technical_checklist_header_id', '=', 'tch.id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
            ->select('tenders.*', 'k.name as kategori_perolehan_name')
            ->where(function ($query) use ($spesifikasiUuid, $targetUuid) {
                $query->where('tci.uuid', $spesifikasiUuid)
                    ->orWhere('tci.uuid', $targetUuid)
                    ->orWhere('sp.uuid', $spesifikasiUuid)
                    ->orWhere('tsd.uuid', $spesifikasiUuid);
            })
            ->first();

        if (!$tender) {
            $fciTender = DB::table('financial_checklist_items as fci')
                ->join('financial_checklist_headers as fch', 'fch.id', '=', 'fci.financial_checklist_header_id')
                ->where('fci.uuid', $spesifikasiUuid)
                ->pluck('fch.tender_id')
                ->first();

            if ($fciTender) {
                $tender = Tender::with('tenderer')
                    ->leftJoin('ref_kategori_jenis_perolehans as k', 'k.id', '=', 'tenders.kategori_perolehan_id')
                    ->select('tenders.*', 'k.name as kategori_perolehan_name')
                    ->where('tenders.id', $fciTender)
                    ->first();
            }
        }

        return $tender;
    }

    private function resolveSpecificationTemplate(string $spesifikasiUuid): ?object
    {
        $targetUuid = $this->resolveTechnicalItemUuid($spesifikasiUuid);

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
            ->where(function ($query) use ($spesifikasiUuid, $targetUuid) {
                $query->where('tci.uuid', $spesifikasiUuid)
                    ->orWhere('tci.uuid', $targetUuid)
                    ->orWhere('sp.uuid', $spesifikasiUuid)
                    ->orWhere('tsd.uuid', $spesifikasiUuid);
            })
            ->first();
    }

    private function buildFallbackPricingData(string $spesifikasiUuid): ?array
    {
        $targetUuid = $this->resolveTechnicalItemUuid($spesifikasiUuid);

        $techItem = DB::table('technical_checklist_items as tci')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->where(function ($query) use ($spesifikasiUuid, $targetUuid) {
                $query->where('tci.uuid', $spesifikasiUuid)
                    ->orWhere('tci.uuid', $targetUuid)
                    ->orWhere('sp.uuid', $spesifikasiUuid)
                    ->orWhere('tsd.uuid', $spesifikasiUuid);
            })
            ->select(
                'tci.id as tci_id',
                'tci.uuid as tci_uuid',
                'tsd.id as tsd_id',
                'tsd.uuid as tsd_uuid',
                'tsd.title as tsd_title',
                'tsd.item_type',
                'tsd.specification_type',
                'tsd.goods_type',
                'tsd.weighting_type',
                'tsd.physical_submission',
                'tsd.status as tsd_status'
            )
            ->first();

        if (!$techItem || !$techItem->tsd_id) {
            return null;
        }

        $specItems = DB::table('technical_specification_items')
            ->where('technical_specification_document_id', $techItem->tsd_id)
            ->orderBy('sort_order')
            ->get();

        $pricingRecord = DB::table('specification_pricings')
            ->where('technical_checklist_item_id', $techItem->tci_id)
            ->first();

        $pricingItemsBySpecItem = collect();
        if ($pricingRecord) {
            $pricingItemsBySpecItem = DB::table('specification_pricing_items')
                ->where('specification_pricing_id', $pricingRecord->id)
                ->get()
                ->keyBy('spec_item_id');
        }

        $items = [];
        foreach ($specItems as $specItem) {
            $details = DB::table('technical_specification_details')
                ->where('technical_specification_item_id', $specItem->id)
                ->pluck('description')
                ->toArray();

            $pItem = $pricingItemsBySpecItem->get($specItem->id);

            $items[] = [
                'uuid'     => $pItem?->uuid ?? (string) Str::uuid(),
                'title'    => $specItem->title,
                'kuantiti' => (float) ($specItem->quantity ?? 0),
                'uom'      => $specItem->unit ?? $specItem->uom ?? '-',
                'harga'    => (float) ($pItem?->harga ?? 0),
                'details'  => $details,
            ];
        }

        $tender = DB::table('tenders as t')
            ->join('technical_checklist_headers as tch', 'tch.tender_id', '=', 't.id')
            ->join('technical_checklist_items as tci', 'tci.technical_checklist_header_id', '=', 'tch.id')
            ->where('tci.id', $techItem->tci_id)
            ->select('t.anggaran_jabatan')
            ->first();

        return [
            'uuid'             => $pricingRecord?->uuid ?? (string) Str::uuid(),
            'anggaran_jabatan' => (float) ($pricingRecord?->anggaran_jabatan ?? $tender?->anggaran_jabatan ?? 0),
            'jumlah_harga'     => (float) ($pricingRecord?->jumlah_harga ?? 0),
            'status'           => $pricingRecord?->status ?? 'draft',
            'spec_document'    => [
                'uuid'                => $techItem->tsd_uuid,
                'title'               => $techItem->tsd_title,
                'item_type'           => $techItem->item_type,
                'specification_type'  => $techItem->specification_type,
                'goods_type'          => $techItem->goods_type,
                'weighting_type'      => $techItem->weighting_type,
                'physical_submission' => (bool) $techItem->physical_submission,
                'status'              => $techItem->tsd_status,
            ],
            'items' => $items,
        ];
    }

    private function storeLocalFallback(string $spesifikasiUuid, array $data): array
    {
        $techItem = DB::table('technical_checklist_items as tci')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->where('tci.uuid', $spesifikasiUuid)
            ->orWhere('sp.uuid', $spesifikasiUuid)
            ->orWhere('tsd.uuid', $spesifikasiUuid)
            ->select('tci.id as tci_id', 'tsd.id as tsd_id')
            ->first();

        if (!$techItem) {
            return ['success' => false, 'message' => 'Record not found.'];
        }

        try {
            DB::transaction(function () use ($techItem, $data) {
                $pricingId = DB::table('specification_pricings')
                    ->where('technical_checklist_item_id', $techItem->tci_id)
                    ->value('id');

                if (!$pricingId) {
                    $pricingId = DB::table('specification_pricings')->insertGetId([
                        'uuid'                        => (string) Str::uuid(),
                        'technical_checklist_item_id' => $techItem->tci_id,
                        'jumlah_harga'                => $data['jumlah_harga'] ?? 0,
                        'status'                      => 'draft',
                        'created_at'                  => now(),
                        'updated_at'                  => now(),
                    ]);
                } else {
                    DB::table('specification_pricings')
                        ->where('id', $pricingId)
                        ->update([
                            'jumlah_harga' => $data['jumlah_harga'] ?? 0,
                            'updated_at'   => now(),
                        ]);
                }

                if (!empty($data['items'])) {
                    foreach ($data['items'] as $itemData) {
                        if (!empty($itemData['uuid'])) {
                            DB::table('specification_pricing_items')
                                ->where('uuid', $itemData['uuid'])
                                ->where('specification_pricing_id', $pricingId)
                                ->update(['harga' => $itemData['harga'] ?? 0, 'updated_at' => now()]);
                        }
                    }
                }
            });

            return ['success' => true, 'message' => 'Berjaya disimpan.'];
        } catch (\Throwable $e) {
            Log::error('SpecificationPricingController@storeLocalFallback failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Ralat semasa menyimpan.'];
        }
    }

    private function submitLocalFallback(string $spesifikasiUuid): array
    {
        $techItem = DB::table('technical_checklist_items as tci')
            ->leftJoin('specification_pricings as sp', 'sp.technical_checklist_item_id', '=', 'tci.id')
            ->leftJoin('technical_specification_documents as tsd', 'tsd.id', '=', 'tci.specification_document_id')
            ->where('tci.uuid', $spesifikasiUuid)
            ->orWhere('sp.uuid', $spesifikasiUuid)
            ->orWhere('tsd.uuid', $spesifikasiUuid)
            ->select('tci.id as tci_id')
            ->first();

        if ($techItem) {
            DB::table('specification_pricings')
                ->where('technical_checklist_item_id', $techItem->tci_id)
                ->update(['status' => 'submitted', 'updated_at' => now()]);

            DB::table('financial_checklist_items')
                ->where('technical_item_id', $techItem->tci_id)
                ->orWhere('source_type', 'specification_document')
                ->update(['status' => 'submitted', 'updated_at' => now()]);
        } else {
            DB::table('financial_checklist_items')
                ->where('uuid', $spesifikasiUuid)
                ->orWhere('source_type', 'specification_document')
                ->update(['status' => 'submitted', 'updated_at' => now()]);
        }

        return ['success' => true, 'message' => 'Berjaya dihantar.'];
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

