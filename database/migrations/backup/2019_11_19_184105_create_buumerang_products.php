<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuumerangProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buumerang_products', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->bigInteger('users_id')->unsigned();
            $table->bigInteger('boomerang_tracker_id')->unsigned();
            $table->string('buumerang_product', 100);
            $table->tinyInteger('buumerang_status')->length(1)->unsigned()->default(0);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('boomerang_tracker_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buumerang_products');
    }
}
