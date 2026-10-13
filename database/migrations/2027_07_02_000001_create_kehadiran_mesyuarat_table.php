<?php

use App\Support\SchemaCompat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Larian sebelum ini gagal pada ALTER yang menambah foreign key, jadi
        // jadual boleh wujud tanpa constraint. Guard "sudah wujud" semata-mata
        // akan melangkaunya dan menanda migration selesai atas skema cacat.
        SchemaCompat::dropIfIncomplete('kehadiran_mesyuarat');

        if (Schema::hasTable('kehadiran_mesyuarat')) {
            return;
        }

        Schema::create('kehadiran_mesyuarat', function (Blueprint $table) {
            $table->id();
            // Lebar mesti sepadan dengan lajur yang dirujuk: `tenders.id` ialah
            // INT UNSIGNED pada pangkalan data yang dipulihkan daripada 2.0,
            // BIGINT UNSIGNED pada yang dibina dari kosong.
            SchemaCompat::referenceColumn($table, 'tender_id', 'tenders');
            SchemaCompat::referenceColumn($table, 'penyediaan_mesyuarat_id', 'penyediaan_mesyuarat');
            SchemaCompat::referenceColumn($table, 'jawatankuasa_id', 'jawatankuasas');
            $table->boolean('hadir')->default(false);
            $table->boolean('untuk_kelulusan')->default(false);
            $table->timestamps();

            $table->foreign('tender_id')->references('id')->on('tenders')->cascadeOnDelete();
            $table->foreign('penyediaan_mesyuarat_id')->references('id')->on('penyediaan_mesyuarat')->cascadeOnDelete();
            $table->foreign('jawatankuasa_id')->references('id')->on('jawatankuasas')->cascadeOnDelete();
            $table->unique(
                ['penyediaan_mesyuarat_id', 'jawatankuasa_id'],
                'kehadiran_mesyuarat_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran_mesyuarat');
    }
};
