<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBtreeSponsorRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('TRUNCATE binary_plan');
        Schema::table('binary_plan', function (Blueprint $table) {
            $table->dropColumn('sponsor_id');
        });

        Schema::table('binary_plan', function (Blueprint $table) {
            $table->integer('sponsor_id')->after('user_id')->nullable();

            $table
                ->foreign('sponsor_id', 'sponsor_id_ibfk_1')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('TRUNCATE binary_plan');
        Schema::table('binary_plan', function (Blueprint $table) {
            $table->dropForeign('sponsor_id_ibfk_1');
            $table->dropColumn('sponsor_id');

        });

        Schema::table('binary_plan', function (Blueprint $table) {
            $table->string('sponsor_id', 50)->after('user_id');
        });
    }
}
