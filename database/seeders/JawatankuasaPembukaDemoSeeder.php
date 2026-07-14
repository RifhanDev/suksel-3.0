<?php

namespace Database\Seeders;

use App\Models\TenderVendorDokumenFile;
use App\Models\TenderVendorDokumenResponse;
use App\Models\TenderVendorOnlineFormStatus;
use App\Support\TenderDokumenPresenter;
use App\Support\TenderProcessStatus;
use App\Tender;
use App\TenderVendor;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Demo data for Jawatankuasa Pembuka (Dokumen Tender/Tawaran review).
 *
 * Run:
 *   php artisan db:seed --class=JawatankuasaPembukaDemoSeeder
 *
 * What it does:
 * - Uses tender #81463 if present, otherwise first tender with checklist items
 * - Sets status_process_id = 7 (PENYEDIAAN_MESYUARAT) so it appears on /index-jawatankuasa-pembuka
 * - Ensures 1–2 vendors have participate = 1
 * - Seeds sample vendor uploads, specification answers, and online-form statuses
 *
 * Then open:
 *   /index-jawatankuasa-pembuka
 *   /jawatankuasa-pembuka?tender={uuid}
 */
class JawatankuasaPembukaDemoSeeder extends Seeder
{
    public const DEFAULT_TENDER_ID = 81463;

    public function run(): void
    {
        $tender = $this->resolveTender();
        if (! $tender) {
            $this->command?->error('JawatankuasaPembukaDemoSeeder: no suitable tender found. Create tender 81463 (or any tender with checklist items) first.');

            return;
        }

        $tender->status_process_id = TenderProcessStatus::PENYEDIAAN_MESYUARAT;
        $tender->save();

        $vendorIds = $this->ensureParticipatingVendors($tender);
        if ($vendorIds === []) {
            $this->command?->error('JawatankuasaPembukaDemoSeeder: no vendors available to attach.');

            return;
        }

        $items = TenderDokumenPresenter::for($tender)->items('admin');
        if ($items === []) {
            $this->command?->warn("JawatankuasaPembukaDemoSeeder: tender {$tender->id} has no checklist items yet.");
        }

        foreach ($vendorIds as $index => $vendorId) {
            $this->seedVendorSubmissions($tender, $vendorId, $items, $index === 0);
        }

        $this->command?->info('JawatankuasaPembukaDemoSeeder: ready.');
        $this->command?->info("  Tender ID     : {$tender->id}");
        $this->command?->info("  Tender UUID   : {$tender->uuid}");
        $this->command?->info('  Status        : ' . TenderProcessStatus::PENYEDIAAN_MESYUARAT . ' (PENYEDIAAN_MESYUARAT)');
        $this->command?->info('  Vendors       : ' . implode(', ', $vendorIds));
        $this->command?->info('  Index URL     : /index-jawatankuasa-pembuka');
        $this->command?->info("  Detail URL    : /jawatankuasa-pembuka?tender={$tender->uuid}");
    }

    protected function resolveTender(): ?Tender
    {
        $preferred = Tender::query()->find(self::DEFAULT_TENDER_ID);
        if ($preferred) {
            return $preferred;
        }

        $withChecklist = DB::table('technical_checklist_items as ti')
            ->join('technical_checklist_headers as th', 'th.id', '=', 'ti.technical_checklist_header_id')
            ->orderByDesc('th.tender_id')
            ->value('th.tender_id');

        if ($withChecklist) {
            $tender = Tender::query()->find($withChecklist);
            if ($tender) {
                $this->command?->warn('Tender ' . self::DEFAULT_TENDER_ID . ' not found. Using tender #' . $tender->id . ' instead.');

                return $tender;
            }
        }

        return Tender::query()->orderByDesc('id')->first();
    }

    /**
     * @return list<int>
     */
    protected function ensureParticipatingVendors(Tender $tender): array
    {
        $vendorIds = [];

        // Preferred: user #6 → vendor (critz), then any other vendor with a user.
        $preferredUser = User::query()->find(6);
        if ($preferredUser?->vendor_id) {
            $vendorIds[] = (int) $preferredUser->vendor_id;
            $this->ensurePurchase($tender, (int) $preferredUser->vendor_id);
        }

        $extraVendorId = (int) (DB::table('vendors')->whereNotIn('id', $vendorIds ?: [0])->value('id') ?? 0);
        if ($extraVendorId > 0) {
            $vendorIds[] = $extraVendorId;
            $this->ensurePurchase($tender, $extraVendorId);
        }

        // Fallback: reuse any existing participants.
        if ($vendorIds === []) {
            $vendorIds = $tender->participants()
                ->pluck('vendor_id')
                ->map(fn($id) => (int) $id)
                ->filter()
                ->unique()
                ->values()
                ->all();

            foreach ($vendorIds as $vendorId) {
                $this->ensurePurchase($tender, $vendorId);
            }
        }

        return array_values(array_unique($vendorIds));
    }

    protected function ensurePurchase(Tender $tender, int $vendorId): void
    {
        $purchase = TenderVendor::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->first();

        if ($purchase) {
            $purchase->participate = 1;
            $purchase->exception = 0;
            $purchase->save();

            return;
        }

        TenderVendor::query()->create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'ref_number' => TenderVendor::generateNumber($tender->id),
            'exception' => 0,
            'participate' => 1,
            'amount' => $tender->price ?? 0,
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    protected function seedVendorSubmissions(Tender $tender, int $vendorId, array $items, bool $fullySubmitted): void
    {
        foreach ($items as $offset => $item) {
            $uuid = (string) ($item['uuid'] ?? '');
            $action = (string) ($item['action'] ?? '');
            $section = (string) ($item['section'] ?? $item['source'] ?? 'technical');

            if ($uuid === '') {
                continue;
            }

            // First vendor: submit almost everything. Second vendor: leave some pending for demo.
            $shouldSubmit = $fullySubmitted || ($offset % 2 === 0);
            if (! $shouldSubmit) {
                continue;
            }

            if (in_array($action, ['vendor_upload', 'download_upload'], true)) {
                $this->seedUpload($tender, $vendorId, $uuid, $section, (string) ($item['title'] ?? 'Dokumen'));
                continue;
            }

            if ($action === 'view_specification') {
                $this->seedSpecification($tender, $vendorId, $uuid, $section);
                continue;
            }

            if ($action === 'key_in') {
                $this->seedKeyIn($tender, $vendorId, $uuid, $section);
                continue;
            }

            if ($action === 'online_form') {
                $formKey = $item['admin_content']['form']['form_key'] ?? null;
                if ($formKey) {
                    $this->seedOnlineFormStatus($tender, $vendorId, (string) $formKey, (string) ($item['title'] ?? $formKey));
                }
            }
        }
    }

    protected function seedUpload(Tender $tender, int $vendorId, string $itemUuid, string $section, string $title): void
    {
        $exists = TenderVendorDokumenFile::query()
            ->where('tender_id', $tender->id)
            ->where('vendor_id', $vendorId)
            ->where('checklist_item_uuid', $itemUuid)
            ->exists();

        if ($exists) {
            return;
        }

        $safeTitle = Str::slug(Str::limit($title, 40, '')) ?: 'dokumen';
        $storedName = $safeTitle . '-demo.pdf';
        $directory = "uploads/tender-dokumen/{$tender->id}/{$vendorId}/{$itemUuid}";
        $path = "{$directory}/{$storedName}";

        Storage::disk('public')->makeDirectory($directory);
        if (! Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put(
                $path,
                "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF\nDemo upload for {$title}\n"
            );
        }

        TenderVendorDokumenFile::query()->create([
            'uuid' => (string) Str::uuid(),
            'tender_id' => $tender->id,
            'vendor_id' => $vendorId,
            'checklist_item_uuid' => $itemUuid,
            'section' => $section ?: 'technical',
            'original_name' => $storedName,
            'stored_name' => $storedName,
            'path' => $path,
            'mime_type' => 'application/pdf',
            'size' => Storage::disk('public')->size($path) ?: 128,
            'uploaded_by' => User::query()->where('vendor_id', $vendorId)->value('id'),
        ]);
    }

    protected function seedSpecification(Tender $tender, int $vendorId, string $itemUuid, string $section): void
    {
        TenderVendorDokumenResponse::query()->updateOrCreate(
            [
                'tender_id' => $tender->id,
                'vendor_id' => $vendorId,
                'checklist_item_uuid' => $itemUuid,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'section' => $section ?: 'technical',
                'response_type' => 'specification',
                'payload' => [
                    'item_prices' => ['1000.00'],
                    'details' => [
                        ['pematuhan' => 'Ya', 'cadangan' => 'Mengikut spesifikasi'],
                    ],
                ],
                'status' => 'submitted',
                'updated_by' => User::query()->where('vendor_id', $vendorId)->value('id'),
            ]
        );
    }

    protected function seedKeyIn(Tender $tender, int $vendorId, string $itemUuid, string $section): void
    {
        TenderVendorDokumenResponse::query()->updateOrCreate(
            [
                'tender_id' => $tender->id,
                'vendor_id' => $vendorId,
                'checklist_item_uuid' => $itemUuid,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'section' => $section ?: 'technical',
                'response_type' => 'key_in',
                'payload' => [
                    'value' => 'Demo jawapan petender untuk semakan Jawatankuasa Pembuka.',
                ],
                'status' => 'submitted',
                'updated_by' => User::query()->where('vendor_id', $vendorId)->value('id'),
            ]
        );
    }

    protected function seedOnlineFormStatus(Tender $tender, int $vendorId, string $formKey, string $title): void
    {
        TenderVendorOnlineFormStatus::query()->updateOrCreate(
            [
                'tender_id' => $tender->id,
                'vendor_id' => $vendorId,
                'form_key' => $formKey,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'status' => 'submitted',
                'summary' => [
                    'text' => "Demo: {$title} telah dihantar",
                ],
                'submitted_at' => now(),
                'updated_by' => User::query()->where('vendor_id', $vendorId)->value('id'),
            ]
        );
    }
}
