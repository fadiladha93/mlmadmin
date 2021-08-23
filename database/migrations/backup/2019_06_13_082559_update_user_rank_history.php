<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRankHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_rank_history', function (Blueprint $table) {
            $table->bigInteger('monthly_qc')
                ->nullable();
            $table->bigInteger('qualified_qc')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_rank_history', function (Blueprint $table) {
            $table->dropColumn('monthly_qc');
            $table->dropColumn('qualified_qc');
        });
    }
}
