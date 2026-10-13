<?php

use Database\Seeders\RefLokalitisSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ref_lokalitis')) {
            return;
        }

        (new RefLokalitisSeeder())->run();
    }

    public function down(): void
    {
        if (! Schema::hasTable('ref_lokalitis')) {
            return;
        }

        $names = array_column(RefLokalitisSeeder::LOKALITIS, 'name');

        $query = DB::table('ref_lokalitis')->whereIn('name', $names);

        if (Schema::hasColumn('tenders', 'lokaliti_id')) {
            $query->whereNotIn('id', function ($sub) {
                $sub->select('lokaliti_id')
                    ->from('tenders')
                    ->whereNotNull('lokaliti_id');
            });
        }

        $query->delete();
    }
};
