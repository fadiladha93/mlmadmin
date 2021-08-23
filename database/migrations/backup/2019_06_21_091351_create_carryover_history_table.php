<?php

use App\BinaryCommissionCarryoverHistory;
use App\BinaryCommissionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarryoverHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bc_history', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
        });

        Schema::create('bc_carryover_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->float('right_carryover', 8, 2);
            $table->float('left_carryover', 8, 2);
            $table->integer('bc_history_id')->unsigned();
            $table->foreign('bc_history_id')->references('id')->on('bc_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bc_carryover_history');
        Schema::drop('bc_history');
    }
}
