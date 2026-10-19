<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penyata_bank_files') && ! Schema::hasColumn('penyata_bank_files', 'vendor_id')) {
            Schema::table('penyata_bank_files', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('penyata_bank_id')->index();
            });

            // Backfill vendor_id from path or uploaded_by user
            $files = DB::table('penyata_bank_files')->get();
            foreach ($files as $file) {
                $vendorId = null;

                if (! empty($file->path) && preg_match('#uploads/penyata-bank/\d+/(\d+)/#', $file->path, $matches)) {
                    $vendorId = (int) $matches[1];
                } elseif (! empty($file->uploaded_by)) {
                    $user = DB::table('users')->where('id', $file->uploaded_by)->first();
                    if ($user && ! empty($user->vendor_id)) {
                        $vendorId = (int) $user->vendor_id;
                    }
                }

                if ($vendorId) {
                    DB::table('penyata_bank_files')
                        ->where('id', $file->id)
                        ->update(['vendor_id' => $vendorId]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penyata_bank_files') && Schema::hasColumn('penyata_bank_files', 'vendor_id')) {
            Schema::table('penyata_bank_files', function (Blueprint $table) {
                $table->dropColumn('vendor_id');
            });
        }
    }
};
