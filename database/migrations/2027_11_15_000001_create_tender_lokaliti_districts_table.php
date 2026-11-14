<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The tender PK is INT UNSIGNED in the restored legacy database.
        // Repair an empty table left by the first failed local migration attempt,
        // which used BIGINT UNSIGNED before the deployed column type was checked.
        if (Schema::hasTable('tender_lokaliti_districts')) {
            $tenderIdColumn = DB::selectOne("SHOW COLUMNS FROM tender_lokaliti_districts LIKE 'tender_id'");
            if ($tenderIdColumn && strtolower($tenderIdColumn->Type) === 'bigint unsigned') {
                if (DB::table('tender_lokaliti_districts')->exists()) {
                    DB::statement('ALTER TABLE tender_lokaliti_districts MODIFY tender_id INT UNSIGNED NOT NULL');
                } else {
                    Schema::dropIfExists('tender_lokaliti_districts');
                }
            }
        }

        if (! Schema::hasTable('tender_lokaliti_districts')) {
            Schema::create('tender_lokaliti_districts', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('tender_id');
                $table->unsignedInteger('district_id');
                $table->timestamps();

                $table->unique(['tender_id', 'district_id']);
                $table->index('district_id');
            });
        }

        $foreignKey = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME = ? LIMIT 1',
            ['tender_lokaliti_districts', 'tender_id', 'tenders']
        );
        if (! $foreignKey) {
            Schema::table('tender_lokaliti_districts', function (Blueprint $table) {
                $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
            });
        }

        // Preserve the single project-area selection previously saved by the
        // Tender/Sebut Harga form. Keep district_id itself untouched because it
        // also remains an existing vendor-eligibility rule. Exclude the two
        // separate direct-procurement workflows.
        if (Schema::hasTable('ref_lokalitis')
            && Schema::hasTable('tenders')
            && Schema::hasColumn('tenders', 'lokaliti_id')
            && Schema::hasColumn('tenders', 'zon_lokasi')
            && Schema::hasColumn('tenders', 'district_id')
            && Schema::hasColumn('tenders', 'type')) {
            $daerahTerpilihId = DB::table('ref_lokalitis')
                ->where('name', 'Daerah Terpilih')
                ->value('id');

            if ($daerahTerpilihId) {
                $timestamp = now();
                DB::table('tenders')
                    ->where('zon_lokasi', 1)
                    ->where('lokaliti_id', $daerahTerpilihId)
                    ->whereNotNull('district_id')
                    ->whereIn('district_id', range(1, 9))
                    ->whereNotIn('type', ['pembelian_terus', 'lantikan_terus'])
                    ->orderBy('id')
                    ->chunkById(500, function ($tenders) use ($timestamp) {
                        DB::table('tender_lokaliti_districts')->insertOrIgnore(
                            $tenders->map(fn ($tender) => [
                                'tender_id' => $tender->id,
                                'district_id' => $tender->district_id,
                                'created_at' => $timestamp,
                                'updated_at' => $timestamp,
                            ])->all()
                        );
                    });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_lokaliti_districts');
    }
};
