<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Restored/legacy databases can carry tenders.approver_id values whose
        // referenced user no longer exists (e.g. staff accounts deleted before
        // this FK constraint existed). Clean those up first so the FK below can
        // be added — this mirrors what ON DELETE SET NULL would have done
        // automatically had the constraint existed at the time of deletion.
        DB::statement(
            'UPDATE tenders SET approver_id = NULL '
            .'WHERE approver_id IS NOT NULL AND approver_id NOT IN (SELECT id FROM users)'
        );

        if (! $this->foreignKeyExists('tenders', 'tenders_approver_id_fk')) {
            Schema::table('tenders', function (Blueprint $table) {
                $table->foreign('approver_id', 'tenders_approver_id_fk')->references('id')->on('users')->onDelete('set null')->nullable()->after('officer_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropColumn('approver_id');
        });
    }

    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        $result = DB::selectOne(
            'SELECT COUNT(*) AS count FROM information_schema.TABLE_CONSTRAINTS '
            .'WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = \'FOREIGN KEY\'',
            [$table, $constraintName]
        );

        return $result && (int) $result->count > 0;
    }
};
