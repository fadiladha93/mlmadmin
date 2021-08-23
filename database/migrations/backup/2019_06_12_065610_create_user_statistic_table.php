<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_statistic', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('current_month_qc')
                ->nullable();
            $table->integer('user_id');
            $table
                ->foreign('user_id', 'user_id_prmfk_1')
                ->references('id')
                ->on('users')
                ->nullable()
                ->onUpdate('cascade')
                ->onDelete('cascade')
            ;
        });

        DB::statement('INSERT INTO user_statistic (user_id)
          SELECT u.id from users as u
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_statistic');
    }
}
