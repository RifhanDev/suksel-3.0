<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('predefined_remarks')) {
            Schema::create('predefined_remarks', function (Blueprint $table) {
                $table->id();
                $table->string('type')->nullable()->index();
                $table->text('remark');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('predefined_remarks') && DB::table('predefined_remarks')->count() === 0) {
            $now = now();
            DB::table('predefined_remarks')->insert([
                [
                    'type' => 'user_rejection',
                    'remark' => 'Maklumat tidak lengkap / tidak sah',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'type' => 'user_rejection',
                    'remark' => 'Emel bukan emel rasmi agensi',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'type' => 'user_rejection',
                    'remark' => 'Permohonan digandakan',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'type' => 'user_rejection',
                    'remark' => 'Tidak lagi bertugas di agensi berkenaan',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'type' => 'user_rejection',
                    'remark' => 'Lain-lain',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('predefined_remarks');
    }
};
