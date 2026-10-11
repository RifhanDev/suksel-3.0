<?php

use Database\Seeders\Ref\KaedahDokumenSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ref_kaedah_dokumens')) {
            Schema::create('ref_kaedah_dokumens', function (Blueprint $table) {
                $table->id();
                $table->string('code', 32)->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('attention')->nullable();
                $table->boolean('active')->default(true);
                $table->boolean('skips_to_penyediaan_iklan')->default(false);
                $table->timestamps();
            });
        }

        (new KaedahDokumenSeeder())->run();

        if (! Schema::hasTable('tenders') || Schema::hasColumn('tenders', 'kaedah_dokumen_id')) {
            return;
        }

        Schema::table('tenders', function (Blueprint $table) {
            $table->unsignedBigInteger('kaedah_dokumen_id')->nullable()->after('only_advertise');
            $table->foreign('kaedah_dokumen_id')
                ->references('id')
                ->on('ref_kaedah_dokumens')
                ->nullOnDelete();
        });

        if (Schema::hasColumn('tenders', 'kaedah_dokumen')) {
            $idsByCode = DB::table('ref_kaedah_dokumens')->pluck('id', 'code');

            foreach ($idsByCode as $code => $id) {
                DB::table('tenders')
                    ->where('kaedah_dokumen', $code)
                    ->update(['kaedah_dokumen_id' => $id]);
            }

            Schema::table('tenders', function (Blueprint $table) {
                $table->dropColumn('kaedah_dokumen');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tenders') && Schema::hasColumn('tenders', 'kaedah_dokumen_id')) {
            Schema::table('tenders', function (Blueprint $table) {
                $table->dropForeign(['kaedah_dokumen_id']);
                $table->dropColumn('kaedah_dokumen_id');
            });
        }

        Schema::dropIfExists('ref_kaedah_dokumens');
    }
};
