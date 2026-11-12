<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
                if (Schema::hasTable('complaints')) {
Schema::table('complaints', function (Blueprint $table) {
            if (! Schema::hasColumn('complaints', 'admin_reply')) {
            $table->text('admin_reply')->nullable()->after('content');
            }
            if (! Schema::hasColumn('complaints', 'replied_by')) {
            $table->unsignedBigInteger('replied_by')->nullable()->after('admin_reply');
            }
            if (! Schema::hasColumn('complaints', 'replied_at')) {
            $table->timestamp('replied_at')->nullable()->after('replied_by');
            }
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['admin_reply', 'replied_by', 'replied_at']);
        });
    }
};
