<?php

use Illuminate\Database\Migrations\Migration;

class AddReactivationProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\ProductType::insert(
            [
                'id' => 7,
                'typedesc' => 'Fee',
                'statuscode' => 1,
            ]
        );
        \App\Product::insert(
            [
                'id' => 50,
                'productname' => 'Subscription Reactivation Fee',
                'producttype' => 7,
                'productdesc' => 'Subscription Reactivation Fee',
                'price' => 4.95,
                'sku' => 'FE-0001',
                'itemcode' => 'FE-0001',
                'bv' => 0,
                'cv' => 0,
                'qv' => 0,
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
        \App\ProductType::where('id', 7)->delete();
        \App\Product::where('id', 50)->delete();
    }
}
