<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Add indicies for better performance.
 *
 * Class AddIndexes
 */
class CreateVibeCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vibe_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');

            $table->string('rider_id')->nullable();
            $table->string('rider_name')->nullable();

            $table->string('driver_id')->nullable();
            $table->string('driver_name')->nullable();

            $table->timestamp('ride_date');
            $table->string('ride_id');
            $table->decimal('ride_commission');
            $table->decimal('direct_payout');
            $table->timestamp('calculation_date');
            $table->timestamp('paid_date');
            $table->string('status')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vibe_commissions');
    }
}
