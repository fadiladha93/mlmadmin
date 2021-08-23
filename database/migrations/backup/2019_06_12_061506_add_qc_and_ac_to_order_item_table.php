<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQcAndAcToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderItem', function (Blueprint $table) {
            $table->integer('qc')->nullable();
            $table->integer('ac')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orderItem', function (Blueprint $table) {
            $table->dropColumn('qc');
            $table->dropColumn('ac');
        });
    }
}
