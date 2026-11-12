<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jawatankuasas') && ! Schema::hasColumn('jawatankuasas', 'is_current')) {
            Schema::table('jawatankuasas', function (Blueprint $table) {
                if (! Schema::hasColumn('jawatankuasas', 'is_current')) {
                $table->boolean('is_current')->default(true)->index();
                }
            });
        }

        if (! Schema::hasTable('penyediaan_mesyuarat_attendees')) {
            Schema::create('penyediaan_mesyuarat_attendees', function (Blueprint $table) {
                $table->id();
                SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
                SchemaCompat::referenceColumn($table, 'penyediaan_mesyuarat_id', 'penyediaan_mesyuarat');
                SchemaCompat::referenceColumn($table, 'jawatankuasa_id', 'jawatankuasas');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('ic_number', 32)->nullable();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('jawatan')->nullable();
                $table->string('gred', 50)->nullable();
                $table->string('p_p', 1)->default('0');
                $table->string('peranan', 1)->default('3');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
                $table->foreign('penyediaan_mesyuarat_id')->references('id')->on('penyediaan_mesyuarat')->cascadeOnDelete();
                $table->foreign('jawatankuasa_id')->references('id')->on('jawatankuasas')->cascadeOnDelete();
                $table->unique(['penyediaan_mesyuarat_id', 'jawatankuasa_id'], 'meeting_attendee_committee_unique');
            });
        }

        if (Schema::hasTable('kehadiran_mesyuarat') && ! Schema::hasColumn('kehadiran_mesyuarat', 'meeting_attendee_id')) {
            Schema::table('kehadiran_mesyuarat', function (Blueprint $table) {
                SchemaCompat::referenceColumn($table, 'meeting_attendee_id', 'penyediaan_mesyuarat_attendees', true);
                $table->foreign('meeting_attendee_id', 'attendance_meeting_attendee_fk')
                    ->references('id')->on('penyediaan_mesyuarat_attendees')->cascadeOnDelete();
            });
        }

        // Best-effort initial snapshot for previously submitted meetings. Past
        // rosters cannot be reconstructed if committee membership was overwritten.
        if (Schema::hasTable('penyediaan_mesyuarat_attendees')
            && Schema::hasTable('penyediaan_mesyuarat')
            && Schema::hasTable('jawatankuasas')
            && Schema::hasTable('kehadiran_mesyuarat')
            && Schema::hasColumn('jawatankuasas', 'is_current')) {
            DB::statement("INSERT IGNORE INTO penyediaan_mesyuarat_attendees
                (tender_id, penyediaan_mesyuarat_id, jawatankuasa_id, user_id, ic_number, name, email, jawatan, gred, p_p, peranan, is_active, created_at, updated_at)
                SELECT pm.tender_id, pm.id, j.id, j.user_id, u.ic_number, u.name, u.email, u.jawatan, u.gred, j.p_p, j.peranan, 1, NOW(), NOW()
                FROM penyediaan_mesyuarat pm
                INNER JOIN jawatankuasas j ON j.tender_id = pm.tender_id AND j.jenis_jawatankuasa = pm.jenis_jawatankuasa AND j.is_current = 1
                INNER JOIN users u ON u.id = j.user_id
                WHERE pm.status = 'Dihantar' AND j.dihantar_pemakluman_pada IS NOT NULL");

            DB::statement("UPDATE kehadiran_mesyuarat km
                INNER JOIN penyediaan_mesyuarat_attendees pma
                    ON pma.penyediaan_mesyuarat_id = km.penyediaan_mesyuarat_id
                    AND pma.jawatankuasa_id = km.jawatankuasa_id
                SET km.meeting_attendee_id = pma.id
                WHERE km.meeting_attendee_id IS NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kehadiran_mesyuarat') && Schema::hasColumn('kehadiran_mesyuarat', 'meeting_attendee_id')) {
            Schema::table('kehadiran_mesyuarat', function (Blueprint $table) {
                $table->dropForeign('attendance_meeting_attendee_fk');
                $table->dropColumn('meeting_attendee_id');
            });
        }

        Schema::dropIfExists('penyediaan_mesyuarat_attendees');

        if (Schema::hasTable('jawatankuasas') && Schema::hasColumn('jawatankuasas', 'is_current')) {
            Schema::table('jawatankuasas', function (Blueprint $table) {
                $table->dropIndex(['is_current']);
                $table->dropColumn('is_current');
            });
        }
    }
};
