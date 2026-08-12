<?php

namespace App\Services;

use App\Models\TenderTeknikalKerjaEvaluation;
use App\Models\TenderTeknikalKerjaLampiran;
use App\Models\TenderVendorDokumenResponse;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Read-side helpers for the kerja Penilaian Teknikal page (single page: Lulus/Tidak Lulus +
 * Lampiran). Submission now goes through the STOS backend — see hantarTeknikalKerja().
 */
class TeknikalKerjaEvaluationService
{
    /** Frozen vendor list for this stage — keeps vendors eliminated here, excludes earlier cuts. */
    public function loadShortlistedVendors(Tender $tender): Collection
    {
        return TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('participate', 1)
            ->where(function ($query) {
                $query->where('cancel_fg', 0)
                    ->orWhere('eliminated_process_id', TenderProcessStatus::PENILAIAN_TEKNIKAL);
            })
            ->orderBy('id')
            ->get(['id', 'vendor_id', 'kod_pembekal', 'harga_tawaran'])
            ->values();
    }

    /** Vendor's tender price — prefers tender_vendors.harga_tawaran, falls back to specification offers. */
    public function resolveHargaTawaran(Tender $tender, TenderVendor $vendor): ?float
    {
        if (filled($vendor->harga_tawaran) && (float) $vendor->harga_tawaran > 0) {
            return (float) $vendor->harga_tawaran;
        }

        $responses = TenderVendorDokumenResponse::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendor->vendor_id)
            ->where('response_type', 'specification')
            ->get();

        $service = app(VendorDokumenResponseService::class);
        $total = 0.0;
        $hasPrices = false;

        foreach ($responses as $response) {
            $payload = $service->normalizeSpecificationPayload(
                is_array($response->payload) ? $response->payload : null
            );
            $itemPrices = $payload['item_prices'] ?? [];
            if ($itemPrices === []) {
                continue;
            }
            if (collect($itemPrices)->contains(fn ($v) => trim((string) $v) !== '')) {
                $hasPrices = true;
            }
            $total += $service->calculateSpecificationTotal(
                $tender,
                $itemPrices,
                $response->section
            );
        }

        return $hasPrices ? $total : null;
    }

    /** Lulus/Tidak Lulus decisions for a tender, keyed by vendor_id. */
    public function loadEvaluations(Tender $tender): array
    {
        return TenderTeknikalKerjaEvaluation::query()
            ->where('tender_id', $tender->id)
            ->get()
            ->keyBy('vendor_id')
            ->all();
    }

    // Lampiran Penilaian Teknikal

    public function loadLampiran(Tender $tender): Collection
    {
        return TenderTeknikalKerjaLampiran::query()
            ->where('tender_id', $tender->id)
            ->orderBy('id')
            ->get();
    }

    public function uploadLampiran(Tender $tender, UploadedFile $file, ?string $displayName): TenderTeknikalKerjaLampiran
    {
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $directory = "uploads/penilaian-teknikal-kerja/{$tender->id}/lampiran";
        $path = $file->storeAs($directory, $storedName, 'public');

        return TenderTeknikalKerjaLampiran::query()->create([
            'uuid' => (string) Str::uuid(),
            'tender_id' => $tender->id,
            'display_name' => filled($displayName) ? $displayName : $file->getClientOriginalName(),
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);
    }

    public function renameLampiran(TenderTeknikalKerjaLampiran $lampiran, string $displayName): void
    {
        $lampiran->update([
            'display_name' => $displayName,
            'updated_by' => Auth::id(),
        ]);
    }

    public function deleteLampiran(TenderTeknikalKerjaLampiran $lampiran): void
    {
        if ($lampiran->path && Storage::disk('public')->exists($lampiran->path)) {
            Storage::disk('public')->delete($lampiran->path);
        }

        $lampiran->delete();
    }
}
