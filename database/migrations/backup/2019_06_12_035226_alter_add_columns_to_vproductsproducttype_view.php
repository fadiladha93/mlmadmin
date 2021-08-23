<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnsToVproductsproducttypeView extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("DROP VIEW IF EXISTS vproductsproducttype;");
        DB::statement("CREATE OR REPLACE VIEW vproductsproducttype AS
                                        SELECT p.id,
                                           p.productname,
                                           p.producttype,
                                           p.productdesc,
                                           p.price,
                                           p.sku,
                                           p.itemcode,
                                           p.bv,
                                           p.cv,
                                           p.qv,
                                           p.qc,
                                           p.ac,
                                           p.num_boomerangs,
                                           p.sponsor_boomerangs,
                                           pt.typedesc
                                          FROM products p
                                            LEFT JOIN producttype pt ON p.producttype = pt.id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("DROP VIEW IF EXISTS vproductsproducttype;");
        DB::statement("CREATE OR REPLACE VIEW vproductsproducttype AS
                                        SELECT p.id,
                                           p.productname,
                                           p.producttype,
                                           p.productdesc,
                                           p.price,
                                           p.sku,
                                           p.itemcode,
                                           p.bv,
                                           p.cv,
                                           p.qv,
                                           p.num_boomerangs,
                                           p.sponsor_boomerangs,
                                           pt.typedesc
                                          FROM products p
                                            LEFT JOIN producttype pt ON p.producttype = pt.id;");
    }

}
