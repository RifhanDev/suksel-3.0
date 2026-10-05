<?php

use Database\Seeders\Ref\TypeOfContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('ref_type_of_contracts')) {
            return;
        }

        (new TypeOfContract())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action required on rollback
    }
};
