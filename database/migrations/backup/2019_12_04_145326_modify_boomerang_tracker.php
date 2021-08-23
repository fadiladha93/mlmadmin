<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBoomerangTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boomerang_tracker', function(Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedInteger('user_type')->default(1)
                ->index()
                ->comment('See boomerang_tracker_user_type_lookup');

            $table->boolean('seen')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boomerang_tracker', function(Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('user_type');
            $table->dropColumn('seen');
        });
    }
}
