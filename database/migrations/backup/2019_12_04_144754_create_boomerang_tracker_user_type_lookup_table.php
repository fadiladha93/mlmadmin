<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoomerangTrackerUserTypeLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boomerang_tracker_user_type_lookup', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('user_type');
        });

        DB::table('boomerang_tracker_user_type_lookup')->insert([
            [
                'user_type' => 'igo'
            ],
            [
                'user_type' => 'vibe-rider'
            ],
            [
                'user_type' => 'vibe-driver'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boomerang_tracker_user_type_lookup');
    }
}
