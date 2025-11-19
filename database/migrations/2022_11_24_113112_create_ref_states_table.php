<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_states', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("description");
            $table->integer("display_status")->default(1);
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $state_array =  array(
            [
                'name' => 'JOHOR',
                'display_status' => '1'
            ],
            [
                'name' => 'KEDAH',
                'display_status' => '1'
            ],
            [
                'name' => 'KELANTAN',
                'display_status' => '1'
            ],
            [
                'name' => 'MELAKA',
                'display_status' => '1'
            ],
            [
                'name' => 'NEGERI SEMBILAN',
                'display_status' => '1'
            ],
            [
                'name' => 'PAHANG',
                'display_status' => '1'
            ],
            [
                'name' => 'PULAU PINANG',
                'display_status' => '1'
            ],
            [
                'name' => 'PERAK',
                'display_status' => '1'
            ],
            [
                'name' => 'PERLIS',
                'display_status' => '1'
            ],
            [
                'name' => 'SABAH',
                'display_status' => '1'
            ],
            [
                'name' => 'SARAWAK',
                'display_status' => '1'
            ],
            [
                'name' => 'SELANGOR',
                'display_status' => '0'
            ],
            [
                'name' => 'TERENGGANU',
                'display_status' => '1'
            ],
            [
                'name' => 'KUALA LUMPUR',
                'display_status' => '1'
            ],
            [
                'name' => 'LABUAN',
                'display_status' => '1'
            ],
            [
                'name' => 'PUTRAJAYA',
                'display_status' => '1'
            ],
        );

        foreach ($state_array as $state)
        {
            $new_state = new App\Models\RefState();
            $new_state->description = $state['name'];
            $new_state->display_status = $state['display_status'];
            $new_state->created_by = 0;
            $new_state->updated_by = 0;
            $new_state->deleted_by = 0;
            $new_state->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_states');
    }
};
