<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_status', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('commission_type');
            $table
                ->dateTime('end_date');
            $table
                ->string('status')
                ->default('queued');
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
        Schema::drop('commission_status');
    }
}
