<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('notification');
            $table->unsignedBigInteger('organization_unit_id');
            $table->unsignedBigInteger('tender_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('publish')->default(false);
            $table->boolean('featured')->default(false); // used in scopeFeatured
            $table->timestamps();

            // Foreign keys
            $table->foreign('organization_unit_id')
                ->references('id')->on('organization_units')
                ->onDelete('cascade');

            $table->foreign('tender_id')
                ->references('id')->on('tenders')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
}
