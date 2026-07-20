<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tender_visit_representatives')) {
            return;
        }

        Schema::create('tender_visit_representatives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('visit_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('ic_no', 32)->nullable();
            $table->string('name', 255)->nullable();
            $table->boolean('attended')->default(false);
            $table->timestamps();

            $table->index(['visit_id', 'vendor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_visit_representatives');
    }
};

