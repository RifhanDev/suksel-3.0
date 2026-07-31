<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWangKosPrimaAndPeruntukanSementaraToTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->decimal('wang_kos_prima', 15, 2)->nullable()->after('anggaran_jabatan');
            $table->decimal('wang_peruntukan_sementara', 15, 2)->nullable()->after('wang_kos_prima');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['wang_kos_prima', 'wang_peruntukan_sementara']);
        });
    }
}
