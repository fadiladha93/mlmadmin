<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueUserIdConstraintToBinaryTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('binary_plan', function (Blueprint $table) {
            $table->unique('user_id', 'binary_plan_user_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('binary_plan', function (Blueprint $table) {
            $table->dropUnique('binary_plan_user_id_unique');
        });
    }
}
