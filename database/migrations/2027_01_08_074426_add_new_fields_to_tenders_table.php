<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenders', function (Blueprint $table) 
        {
            $table->unsignedBigInteger('kaedah_perolehan_id')->nullable()->after('type');
            $table->unsignedBigInteger('kategori_perolehan_id')->nullable()->after('kaedah_perolehan_id');
            $table->unsignedBigInteger('kategori_perolehan_detail_id')->nullable()->after('kategori_perolehan_id');
            $table->unsignedBigInteger('jenis_tender_id')->nullable()->after('kategori_perolehan_detail_id');
            $table->unsignedBigInteger('jenis_kontrak_id')->nullable()->after('jenis_tender_id');
            $table->unsignedBigInteger('lokaliti_id')->nullable()->after('jenis_kontrak_id');

            // Text fields
            $table->string('no_tender')->nullable()->after('ref_number');
            $table->string('no_kontrak')->nullable()->after('no_tender');
            $table->text('sumber_lain_text')->nullable()->after('tender_rules');

            // Decimal fields for pricing
            $table->decimal('harga_indikatif', 15, 2)->nullable()->after('price');
            $table->decimal('anggaran_jabatan', 15, 2)->nullable()->after('harga_indikatif');

            // Date field
            $table->date('tarikh_dicipta')->nullable()->after('submission_datetime');

            // Enum and string fields
            $table->enum('sumber_peruntukan', ['pembangunan', 'mengurus', 'lain'])->nullable()->after('type');
            $table->enum('terbuka_kepada', ['bumiputera', 'semua'])->default('semua')->after('only_bumiputera');

            // Integer fields for periods
            $table->integer('tempoh_siap_val')->nullable()->after('sumber_lain_text');
            $table->string('tempoh_siap_unit')->nullable()->after('tempoh_siap_val');
            $table->integer('tempoh_kontrak_bulan')->nullable()->after('tempoh_siap_unit');

            // Boolean fields
            $table->boolean('zon_lokasi')->default(false)->after('district_list_rule');
            $table->boolean('jawatankuasa')->default(false)->after('zon_lokasi');
            $table->boolean('lawatan_tapak')->default(false)->after('jawatankuasa');
            $table->boolean('penilaian_fizikal')->default(false)->after('lawatan_tapak');

            // Add foreign key constraints
            $table->foreign('kaedah_perolehan_id')->references('id')->on('ref_kaedah_perolehans')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('kategori_perolehan_id')->references('id')->on('ref_kategori_jenis_perolehans')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('kategori_perolehan_detail_id')->references('id')->on('ref_type_of_perolehans')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('jenis_tender_id')->references('id')->on('ref_type_of_tenders')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('jenis_kontrak_id')->references('id')->on('ref_type_of_contracts')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('lokaliti_id')->references('id')->on('ref_lokalitis')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenders', function (Blueprint $table)
        {
            $table->dropForeign(['kaedah_perolehan_id']);
            $table->dropForeign(['kategori_perolehan_id']);
            $table->dropForeign(['kategori_perolehan_detail_id']);
            $table->dropForeign(['jenis_tender_id']);
            $table->dropForeign(['jenis_kontrak_id']);
            $table->dropForeign(['lokaliti_id']);

            $table->dropColumn(
            [
                'kaedah_perolehan_id',
                'kategori_perolehan_id',
                'kategori_perolehan_detail_id',
                'jenis_tender_id',
                'jenis_kontrak_id',
                'lokaliti_id',
                'no_tender',
                'no_kontrak',
                'sumber_lain_text',
                'harga_indikatif',
                'anggaran_jabatan',
                'tarikh_dicipta',
                'sumber_peruntukan',
                'terbuka_kepada',
                'tempoh_siap_val',
                'tempoh_siap_unit',
                'tempoh_kontrak_bulan',
                'zon_lokasi',
                'jawatankuasa',
                'lawatan_tapak',
                'penilaian_fizikal',
            ]);
        });
    }
}
