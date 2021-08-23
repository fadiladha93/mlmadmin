<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTextOrEmailSentFieldToBoomerangTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boomerang_tracker', function (Blueprint $table) {
            $table->boolean('text_or_email_sent')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boomerang_tracker', function (Blueprint $table) {
            $table->dropColumn('text_or_email_sent');
        });
    }
}
