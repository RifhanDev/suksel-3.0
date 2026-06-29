<?php

namespace App\Services;

use App\Models\TenderVendorFormPayload;
use App\Tender;
use Illuminate\Support\Str;

class VendorFormPayloadService
{
    public function get(Tender $tender, int $vendorId, string $formKey): ?array
    {
        $record = TenderVendorFormPayload::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where('form_key', $formKey)
            ->first();

        return $record?->payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function save(
        Tender $tender,
        int $vendorId,
        string $formKey,
        array $payload,
        string $status = 'draft'
    ): TenderVendorFormPayload {
        $record = TenderVendorFormPayload::query()->firstOrNew([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'form_key' => $formKey,
        ]);

        if (! $record->exists) {
            $record->uuid = (string) Str::uuid();
        }

        $record->fill([
            'payload' => $payload,
            'status' => $status,
            'submitted_at' => $status === 'submitted' ? now() : $record->submitted_at,
            'updated_by' => auth()->id(),
        ])->save();

        return $record;
    }
}
