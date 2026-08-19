<?php

namespace Tests\Feature;

use App\Tender;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProcurementFlowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_bidding_flow_returns_to_perakuan_then_back_to_jawatankuasa(): void
    {
        $this->skipIfWorkflowSchemaMissing();

        $agencyUser = $this->createAgencyUser();
        $tender = $this->createTender(statusProcessId: 2);

        // Step 1: Perakuan Jabatan submit -> Jawatankuasa Perolehan (status 3).
        $this->actingAs($agencyUser)
            ->post(route('perakuanjabatan.pengesyoranPembekal.hantar', ['tender' => $tender->id]), [
                'catatan' => 'Layak',
                'sahkan_petender_layak' => '1',
            ])
            ->assertOk();

        $tender->refresh();
        $this->assertSame(3, (int) $tender->status_process_id);

        // Step 2: Jawatankuasa select "Bidaan" and submit Kertas Keputusan.
        DB::table('jawatankuasa_perolehan_pemilihan_headers')->insert([
            'tender_id' => $tender->id,
            'keputusan_mesyuarat' => 'Pengesyoran Pembekal',
            'kaedah_memuktamadkan_pembekal' => 'Bidaan',
            'pemilihan_berdasarkan' => '1 item',
            'loi_loa_disediakan_oleh' => 'Urusetia atau Setiausaha Sebut Harga',
            'bil_mesyuarat' => '1/2026',
            'no_kod' => 'JP-001',
            'sahkan_layak_bidaan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($agencyUser)
            ->post(route('jawatankuasa.perolehan.kertas_keputusan.hantar'), [
                'tender' => $tender->id,
                'dengan_syarat' => '0',
                'pengesyoran_catatan' => 'Disyorkan',
                'justifikasi_pemilihan_pembekal' => 'Harga dalam lingkungan harga indikatif jabatan',
                'keputusan' => 'Lulus',
            ])
            ->assertOk();

        $tender->refresh();
        $this->assertSame(4, (int) $tender->status_process_id);
        $this->assertSame(1, (int) $tender->is_ebidding);
        $this->assertSame(1, (int) $tender->ebidding_process_stage_id);

        // Prepare vendor bidding prerequisites and move to vendor stage.
        $pemilihanItemId = DB::table('jawatankuasa_perolehan_pemilihan_items')->insertGetId([
            'tender_id' => $tender->id,
            'sort_order' => 1,
            'perihal_item' => 'Item A',
            'jenis_item' => 'Perkhidmatan',
            'unit_ukuran' => 'Unit',
            'jenis_harga' => 'Biasa',
            'dibatalkan' => 'Tidak',
            'pembekal_dipilih' => 1,
            'kuantiti' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $vendorUser = $this->createVendorUser();
        DB::table('tender_vendors')->insert([
            'transaction_id' => 1,
            'vendor_id' => $vendorUser->vendor_id,
            'tender_id' => $tender->id,
            'amount' => 1500.00,
            'price' => 1500.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ebidding_jadual_bidaans')->insert([
            'tender_id' => $tender->id,
            'tarikh_bidaan_mula' => Carbon::now()->subHour()->toDateString(),
            'masa_bidaan_mula' => Carbon::now()->subHour()->format('H:i'),
            'tarikh_bidaan_tamat' => Carbon::now()->addHour()->toDateString(),
            'masa_bidaan_tamat' => Carbon::now()->addHour()->format('H:i'),
            'started_at' => now(),
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Tender::query()->where('id', $tender->id)->update([
            'ebidding_process_stage_id' => 2,
            'is_ebidding' => 1,
        ]);

        // Step 3 + 4: Vendor submit new bid -> back to Perakuan Jabatan (status 2).
        $this->actingAs($vendorUser)
            ->post(route('eBidding.vendorBidaan.hantar', ['id' => $tender->id]), [
                'items' => [
                    [
                        'pemilihan_item_id' => $pemilihanItemId,
                        'bid_price' => 1200.00,
                    ],
                ],
            ])
            ->assertOk();

        $tender->refresh();
        $this->assertSame(2, (int) $tender->status_process_id);
        $this->assertSame(3, (int) $tender->ebidding_process_stage_id);

        // Step 5: Perakuan Jabatan submit again -> Jawatankuasa Perolehan (status 3).
        $this->actingAs($agencyUser)
            ->post(route('perakuanjabatan.pengesyoranPembekal.hantar', ['tender' => $tender->id]), [
                'catatan' => 'Pusingan semula',
                'sahkan_petender_layak' => '1',
            ])
            ->assertOk();

        $tender->refresh();
        $this->assertSame(3, (int) $tender->status_process_id);
    }

    public function test_non_bidding_flow_stops_without_enabling_ebidding(): void
    {
        $this->skipIfWorkflowSchemaMissing();

        $agencyUser = $this->createAgencyUser();
        $tender = $this->createTender(statusProcessId: 3);

        DB::table('jawatankuasa_perolehan_pemilihan_headers')->insert([
            'tender_id' => $tender->id,
            'keputusan_mesyuarat' => 'Pengesyoran Pembekal',
            'kaedah_memuktamadkan_pembekal' => 'Pemilihan Terus',
            'pemilihan_berdasarkan' => '1 item',
            'loi_loa_disediakan_oleh' => 'Urusetia atau Setiausaha Sebut Harga',
            'bil_mesyuarat' => '2/2026',
            'no_kod' => 'JP-002',
            'sahkan_layak_bidaan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($agencyUser)
            ->post(route('jawatankuasa.perolehan.kertas_keputusan.hantar'), [
                'tender' => $tender->id,
                'dengan_syarat' => '0',
                'pengesyoran_catatan' => 'Disyorkan',
                'justifikasi_pemilihan_pembekal' => 'Harga dalam lingkungan harga indikatif jabatan',
                'keputusan' => 'Lulus',
            ])
            ->assertOk();

        $tender->refresh();
        $this->assertSame(4, (int) $tender->status_process_id);
        $this->assertSame(0, (int) $tender->is_ebidding);
        $this->assertNull($tender->ebidding_process_stage_id);
    }

    private function createAgencyUser(): User
    {
        $orgId = DB::table('organization_units')->insertGetId([
            'name' => 'Unit Ujian',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'username' => 'agency_' . uniqid(),
            'email' => 'agency_' . uniqid() . '@example.test',
            'name' => 'Agency User',
            'password' => bcrypt('password'),
            'organization_unit_id' => $orgId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::query()->findOrFail($userId);
    }

    private function createVendorUser(): User
    {
        $orgId = DB::table('organization_units')->insertGetId([
            'name' => 'Unit Vendor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $vendorId = DB::table('vendors')->insertGetId([
            'registration' => 'REG-' . uniqid(),
            'name' => 'Vendor Test',
            'officer_name' => 'Officer',
            'officer_designation' => 'Manager',
            'officer_email' => 'vendor_officer_' . uniqid() . '@example.test',
            'officer_tel' => '0123456789',
            'organization_unit_id' => $orgId,
            'expiry_date' => '2099-12-31',
            'blacklisted_until' => '1970-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'username' => 'vendor_' . uniqid(),
            'email' => 'vendor_user_' . uniqid() . '@example.test',
            'name' => 'Vendor User',
            'password' => bcrypt('password'),
            'vendor_id' => $vendorId,
            'organization_unit_id' => $orgId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $role = DB::table('roles')->where('name', 'Vendor')->first();
        $roleId = $role ? (int) $role->id : DB::table('roles')->insertGetId([
            'name' => 'Vendor',
            'guard_name' => 'web',
            'display_name' => 'Vendor',
            'description' => 'Vendor role for testing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleUserExists = DB::table('role_user')
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->exists();
        if (!$roleUserExists) {
            DB::table('role_user')->insert([
                'user_id' => $userId,
                'role_id' => $roleId,
            ]);
        }

        return User::query()->findOrFail($userId);
    }

    private function createTender(int $statusProcessId): Tender
    {
        $orgId = DB::table('organization_units')->insertGetId([
            'name' => 'Unit Tender',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tenderId = DB::table('tenders')->insertGetId([
            'name' => 'Tender Ujian Flow',
            'organization_unit_id' => $orgId,
            'status_process_id' => $statusProcessId,
            'is_ebidding' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Tender::query()->findOrFail($tenderId);
    }

    private function skipIfWorkflowSchemaMissing(): void
    {
        $hasTenderColumns = Schema::hasColumns('tenders', [
            'status_process_id',
            'is_ebidding',
            'ebidding_process_stage_id',
        ]);

        $requiredTables = [
            'jawatankuasa_perolehan_pemilihan_headers',
            'jawatankuasa_perolehan_pemilihan_items',
            'ebidding_jadual_bidaans',
            'ebidding_vendor_bid_items',
            'role_user',
            'roles',
            'vendors',
            'tender_vendors',
        ];

        $missingTable = collect($requiredTables)->first(function (string $table): bool {
            return !Schema::hasTable($table);
        });

        if (!$hasTenderColumns || $missingTable) {
            $this->markTestSkipped(
                'Workflow schema not available in current DB. Run latest migrations before executing this test.'
            );
        }
    }
}
