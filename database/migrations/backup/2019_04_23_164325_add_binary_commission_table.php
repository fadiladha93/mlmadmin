<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBinaryCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_commission', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id');
            $table
                ->float('carryover_left')
                ->default(0);
            $table
                ->float('carryover_right')
                ->default(0);
            $table
                ->float('total_volume_left')
                ->default(0);
            $table
                ->float('total_volume_right')
                ->default(0);
            $table
                ->float('gross_volume')
                ->default(0);
            $table
                ->float('commission_percent')
                ->default(0);
            $table
                ->float('amount_earned')
                ->default(0);
            $table
                ->dateTime('week_ending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('binary_commission');
    }
}
