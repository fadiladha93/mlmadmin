<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropTsbColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tsb_commission', function ($table) {
            $table->dropColumn('calculation_date');
            $table->dropColumn('end_date');
            $table->dropColumn('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function ($table) {
            $table->string('calculation_date');
            $table->string('end_date');
            $table->string('start_date');
        });
    }
}
