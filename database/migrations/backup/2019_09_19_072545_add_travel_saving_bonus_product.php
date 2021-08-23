<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTravelSavingBonusProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('producttype')->insert(['id' => 6, 'typedesc' => 'Ticket', 'statuscode' => 1]);
        DB::table('products')->insert([
            'id' => \App\Product::ID_TRAVEL_SAVING_BONUS,
            'productname' => 'Travel Bookings',
            'producttype' => \App\ProductType::TYPE_TRAVEL_SAVING_BONUS,
            'productdesc' => 'Travel Bookings',
            'price' => '0',
            'sku' => 'TI-0003',
            'itemcode' => 'TI-0003',
            'bv' => 0,
            'cv' => 0,
            'qv' => 0,
            'num_boomerangs' => 0,
            'sponsor_boomerangs' => 0,
            'qc' => 0,
            'ac' => 0,
            'is_enabled' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('producttype')->where('id',  \App\ProductType::TYPE_TRAVEL_SAVING_BONUS)->delete();
        DB::table('products')->where('id', \App\Product::ID_TRAVEL_SAVING_BONUS)->delete();
    }
}
