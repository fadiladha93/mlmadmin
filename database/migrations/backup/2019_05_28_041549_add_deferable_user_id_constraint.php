<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeferableUserIdConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('binary_plan', function (Blueprint $table) {
            $table->dropUnique('binary_plan_user_id_unique');
        });

        DB::statement('ALTER TABLE binary_plan ADD CONSTRAINT binary_plan_user_id_unique UNIQUE (user_id) DEFERRABLE INITIALLY IMMEDIATE;');
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

        Schema::table('binary_plan', function (Blueprint $table) {
            $table->unique('user_id', 'binary_plan_user_id_unique');
        });
    }
}
