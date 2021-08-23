<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBinaryPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_plan', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id')
                ->index('user_id');
            $table->char('direction', 1);
            $table->string('sponsor_id', 50);
            $table->nestedSet();
            $table
                ->dateTime('enrolled_at')
                ->nullable();
            $table
                ->foreign('user_id', 'user_id_ibfk_1')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
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
        Schema::drop('binary_plan');
    }
}
