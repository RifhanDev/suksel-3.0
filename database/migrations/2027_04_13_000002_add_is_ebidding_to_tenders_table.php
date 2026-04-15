<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            if (!Schema::hasColumn('tenders', 'is_ebidding')) {
                $table->boolean('is_ebidding')->default(false)->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenders', function (Blueprint $table) {
            if (Schema::hasColumn('tenders', 'is_ebidding')) {
                $table->dropIndex(['is_ebidding']);
                $table->dropColumn('is_ebidding');
            }
        });
    }
};
