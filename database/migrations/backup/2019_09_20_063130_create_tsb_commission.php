<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsbCommission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tsb_commission', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id');
            $table->bigInteger('dist_id');
            $table->bigInteger('rank_id')->nullable();
            $table->integer('level')->nullable();
            $table->float('percent')->default(0);
            $table->float('amount')->default(0);
            $table->bigInteger('order_id');
            $table->string('calculation_date');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tsb_commission');
    }
}
