<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQcVolumeToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP view vorder_product_qv');
        DB::statement("DROP VIEW IF EXISTS vproductsproducttype;");

        Schema::table('products', function ($table) {
            $table->decimal('qc', 50)->nullable()->change();
        });
        /// restore view
        DB::statement('create or replace view vorder_product_qv as
        SELECT o.userid,
               p.id AS productid,
               p.qv,
               p.cv,
               i.orderid,
               i.id AS orderitemid,
               o.created_dt,
               p.qc
        FROM ((orders o
          JOIN "orderItem" i ON ((o.id = i.orderid)))
               JOIN products p ON ((p.id = i.productid)));');

        DB::table('products')
            ->whereIn('id', [13, 16, 17, 18, 19, 20, 33])
            ->update([
                'qc' => 1,
            ]);

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

        DB::table('products')
            ->whereIn('id', [26, 33])
            ->update([
                'qc' => 0.5,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('products')
            ->whereIn('id', [13, 16, 17, 18, 19, 20, 33])
            ->update([
                'qc' => null,
            ]);
        DB::table('products')
            ->whereIn('id', [26, 33])
            ->update([
                'qc' => null,
            ]);
    }
}
