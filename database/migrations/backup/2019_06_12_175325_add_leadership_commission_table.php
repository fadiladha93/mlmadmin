<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeadershipCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadership_commission', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id');
            $table
                ->integer('dist_id');
            $table
                ->integer('rank_id');
            $table
                ->integer('level')
                ->default(1);
            $table
                ->float('percent')
                ->default(0);
            $table
                ->float('amount')
                ->default(0);
            $table
                ->integer('order_id')
                ->nullable();
            $table
                ->dateTime('calculation_date');
            $table
                ->dateTime('start_date');
            $table
                ->dateTime('end_date');
            $table
                ->boolean('is_processed')
                ->default(0)
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('leadership_commission');
    }
}
