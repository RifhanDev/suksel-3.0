<?php

namespace Database\Seeders;

use App\Tender;
use App\TenderVendor;
use App\User;
use App\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Allow user #6 (vendor #5) to purchase tender #81463 for dokumen/tender testing.
 */
class VendorTender81463Seeder extends Seeder
{
    public function run(): void
    {
        $userId = 6;
        $tenderId = 81463;

        $user = User::query()->find($userId);
        $tender = Tender::query()->find($tenderId);

        if (! $user || ! $user->vendor_id) {
            $this->command?->warn("VendorTender81463Seeder: user {$userId} not found or has no vendor_id.");

            return;
        }

        if (! $tender) {
            $this->command?->warn("VendorTender81463Seeder: tender {$tenderId} not found.");

            return;
        }

        $vendorId = (int) $user->vendor_id;
        $approverId = DB::table('users')->where('id', '!=', $userId)->value('id') ?? 1;

        DB::table('vendors')->where('id', $vendorId)->update([
            'approval_1_id' => $approverId,
            'registration_paid' => 1,
            'completed' => 1,
            'state_id' => DB::raw('COALESCE(NULLIF(state_id, 0), 10)'),
            'expiry_date' => now()->addYear()->format('Y-m-d'),
        ]);

        $purchase = TenderVendor::query()
            ->where('tender_id', $tenderId)
            ->where('vendor_id', $vendorId)
            ->first();

        if ($purchase) {
            $purchase->exception = 1;
            $purchase->participate = 0;
            $purchase->save();
        } else {
            TenderVendor::query()->create([
                'tender_id' => $tenderId,
                'vendor_id' => $vendorId,
                'ref_number' => TenderVendor::generateNumber($tenderId),
                'exception' => 1,
                'participate' => 0,
                'amount' => 0,
            ]);
        }

        $this->command?->info("VendorTender81463Seeder: user {$userId} (vendor {$vendorId}) can buy tender {$tenderId}.");
    }
}
