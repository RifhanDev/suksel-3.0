<?php

namespace Database\Seeders;

use App\Support\TenderProcessStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeds exactly ONE tender that is purchasable right now by a Vendor —
 * for testing the FPX payment flow (Beli Dokumen Tender) end to end.
 *
 * Standalone on purpose (NOT registered in DatabaseSeeder.php): run it
 * explicitly with `php artisan db:seed --class=TenderPengiklananPembayaranSeeder`
 * so it never runs as a side effect of a full `db:seed` on a database that
 * already has real/copied data.
 *
 * Every condition below was traced directly from the actual gates a real
 * purchase goes through, not guessed:
 *   - App\Tender::canShow()        — non-invitation tender needs approver_id set.
 *   - App\Tender::scopeForPublic() — invitation must be 0/null.
 *   - App\Tender::scopeAdvertised()— advertise_start_date <= today.
 *   - App\Tender::validDocumentDate() — today must be within
 *     [document_start_date, document_stop_date].
 *   - App\Tender::canParticipate() — org unit must not be gateway-locked;
 *     no mof_codes/cidb_grades rows exist for this tender (none inserted
 *     here), so those checks pass trivially; only_selangor/district_id/
 *     district_list_rule left empty so no extra district gate applies.
 *   - TendersController::buy()     — submission_datetime must be in the
 *     future; an active 'fpx' (or 'ebpg') gateway must exist for the
 *     tender's organization_unit_id.
 *
 * Left OUT of scope deliberately: the VENDOR account's own eligibility
 * (App\Vendor::canParticipateInTenders() — approval_1_id set, completed=1,
 * registration_paid=1, valid(), not blacklisted). That's account state, not
 * tender state — this seeder can't fix a test vendor account that isn't
 * itself approved/paid/valid. Verify that separately on whichever vendor
 * account will be used to test the purchase.
 */
class TenderPengiklananPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->value('id');

        if (! $userId) {
            $this->command?->error('TenderPengiklananPembayaranSeeder: tiada user wujud — run seeder user dahulu.');
            return;
        }

        // Prefer the organization unit this session's FPX go-live work was
        // actually done against ("Pejabat SUK Selangor") — it's the one
        // confirmed to have a working FPX gateway. Fall back to any org unit
        // that has an active fpx/ebpg gateway if that name isn't found.
        $orgUnitId = DB::table('organization_units')->where('name', 'Pejabat SUK Selangor')->value('id');

        if (! $orgUnitId) {
            $orgUnitId = DB::table('gateways')
                ->whereIn('type', ['fpx', 'ebpg'])
                ->where('active', 1)
                ->value('organization_unit_id');
        }

        if (! $orgUnitId) {
            $this->command?->error('TenderPengiklananPembayaranSeeder: tiada organization_unit dengan gateway fpx/ebpg aktif ditemui. Tender tak boleh dibeli tanpa ini — cipta/aktifkan gateway dahulu.');
            return;
        }

        $hasActiveGateway = DB::table('gateways')
            ->where('organization_unit_id', $orgUnitId)
            ->whereIn('type', ['fpx', 'ebpg'])
            ->where('active', 1)
            ->exists();

        if (! $hasActiveGateway) {
            $this->command?->warn("TenderPengiklananPembayaranSeeder: organization_unit_id={$orgUnitId} TIADA gateway fpx/ebpg aktif — pembelian akan gagal (TendersController::buy()) sehingga ini dibetulkan.");
        }

        $kaedahPembelianTerus = DB::table('ref_kaedah_perolehans')->where('name', 'Pembelian Terus')->value('id');
        $kategoriBekalan      = DB::table('ref_kategori_jenis_perolehans')->where('name', 'Bekalan')->value('id');
        $detailBekalan        = $kategoriBekalan
            ? DB::table('ref_type_of_perolehans')
                ->where('ref_kategori_jenis_perolehan_id', $kategoriBekalan)
                ->where('name', 'Bekalan')
                ->value('id')
            : null;
        $jenisTender  = DB::table('ref_type_of_tenders')->where('name', 'Konvensional')->value('id');
        $jenisKontrak = DB::table('ref_type_of_contracts')->where('name', 'Bukan Kementerian')->value('id');
        $lokaliti     = DB::table('ref_lokalitis')->value('id');

        $today  = now()->startOfDay();
        $noTender = 'PT-TEST-' . $today->format('Ymd') . '-' . Str::upper(Str::random(4));

        $exists = DB::table('tenders')->where('no_tender', $noTender)->exists();
        if ($exists) {
            $this->command?->info("TenderPengiklananPembayaranSeeder: {$noTender} sudah wujud, tiada tindakan.");
            return;
        }

        $tenderId = DB::table('tenders')->insertGetId([
            'uuid'                 => (string) Str::uuid(),
            'name'                 => 'UJIAN BAYARAN - Bekalan Peralatan Pejabat',
            'ref_number'           => $noTender,
            'no_tender'            => $noTender,
            'creator_id'           => $userId,
            'officer_id'           => $userId,
            // Approved (non-null approver_id) is required by Tender::canShow()
            // for a non-invitation tender to be visible to ANY vendor.
            'approver_id'          => $userId,
            'organization_unit_id' => $orgUnitId,
            'price'                => 50.00,
            'harga_indikatif'      => 50.00,
            'anggaran_jabatan'     => 48000.00,
            'type'                 => 'tender',
            'kaedah_perolehan_id'         => $kaedahPembelianTerus,
            'kategori_perolehan_id'       => $kategoriBekalan,
            'kategori_perolehan_detail_id' => $detailBekalan,
            'jenis_tender_id'      => $jenisTender,
            'jenis_kontrak_id'     => $jenisKontrak,
            'lokaliti_id'          => $lokaliti,
            'sumber_peruntukan'    => 'mengurus',
            'terbuka_kepada'       => 'semua',

            // Not invitation-only, so scopeForPublic() includes it and
            // canShow() only needs approver_id (already set above).
            'invitation'           => 0,

            // scopeAdvertised(): advertise_start_date <= today.
            'advertise_start_date' => $today->copy()->subDay()->toDateString(),
            'advertise_stop_date'  => $today->copy()->addDays(30)->toDateString(),

            // validDocumentDate(): today must fall within this window.
            'document_start_date'  => $today->copy()->subDay()->toDateString(),
            'document_stop_date'   => $today->copy()->addDays(30)->toDateString(),

            // buy(): must be in the future.
            'submission_datetime'  => $today->copy()->addDays(35)->format('Y-m-d H:i:s'),
            'tarikh_dicipta'       => $today->toDateString(),

            'submission_location_address' => 'Pejabat SUK Selangor, Bangunan Sultan Salahuddin Abdul Aziz Shah, 40503 Shah Alam, Selangor.',
            'tender_rules'         => 'Tender ujian dijana oleh seeder untuk pengujian aliran pembayaran FPX sahaja.',

            // No mof_codes/cidb_grades rows are created for this tender (see
            // class docblock), and these stay off/empty so no extra
            // district/mof/cidb gate applies in canParticipate().
            'only_bumiputera'      => 0,
            'only_selangor'        => 0,
            'district_id'          => null,
            'district_list_rule'   => null,
            'mof_cidb_rule'        => 'or',

            // Progressed to "Selesai Penyediaan Iklan" — pengiklanan done.
            'status_process_id'    => TenderProcessStatus::PENYEDIAAN_IKLAN,
            'jawatankuasa'         => 0,
            'is_ebidding'          => 0,

            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command?->info("TenderPengiklananPembayaranSeeder: tender #{$tenderId} ({$noTender}) dicipta, sedia untuk dibeli.");
    }
}
