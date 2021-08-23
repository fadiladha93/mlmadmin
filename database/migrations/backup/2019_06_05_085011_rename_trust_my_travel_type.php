<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTrustMyTravelType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('payment_method_type')
            ->where('pay_method_name', '=', 'Trust my travel')
            ->update(['pay_method_name' => 'Credit Card - TMT']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('payment_method_type')
            ->where('pay_method_name', '=', 'Credit Card - TMT')
            ->update(['pay_method_name' => 'Trust my travel']);
    }
}
