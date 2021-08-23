<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePaymentTypeCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country', function (Blueprint $table){
            $table->primary('id');
        });

        Schema::create('payment_type_country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_type', 128);
            $table->integer('country_id')->nullable();
            $table
                ->foreign('country_id', 'country_id_fk')
                ->references('id')
                ->on('country')
                ->onUpdate('cascade')
            ;
            $table->timestamps();
        });

        DB::table('payment_method_type')->insert(
            [
                'id' => 8,
                'pay_method_name' => 'Trust my travel',
                'statuscode' => 1
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country', function (Blueprint $table){
            $table->dropPrimary('id');
        });

        Schema::drop('payment_type_country');

        DB::table('payment_method_type')
            ->where('pay_method_name', '=', 'Trust my travel')
            ->delete();

    }

}
