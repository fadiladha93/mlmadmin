<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUserStatusCheckForFsbCalculation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE VIEW public.vorder_orderitem AS
 SELECT orders.trasnactionid,
    orders.userid,
    orders.created_dt,
    "orderItem".bv AS item_bv,
    "orderItem".cv,
    "orderItem".id AS item_id,
    "orderItem".qv,
    "orderItem".orderid,
    "orderItem".quantity,
    "orderItem".itemprice,
    "orderItem".productid,
    orders.processed,
    orders.statuscode
   FROM orders
     JOIN "orderItem" ON orders.id = "orderItem".orderid
     JOIN users u ON orders.userid = u.id
  WHERE orders.processed = false AND (orders.statuscode = 1 OR orders.statuscode = 6)
  ORDER BY "orderItem".productid;        
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
CREATE OR REPLACE VIEW public.vorder_orderitem AS
 SELECT orders.trasnactionid,
    orders.userid,
    orders.created_dt,
    "orderItem".bv AS item_bv,
    "orderItem".cv,
    "orderItem".id AS item_id,
    "orderItem".qv,
    "orderItem".orderid,
    "orderItem".quantity,
    "orderItem".itemprice,
    "orderItem".productid,
    orders.processed,
    orders.statuscode
   FROM orders
     JOIN "orderItem" ON orders.id = "orderItem".orderid
     JOIN users u ON orders.userid = u.id
  WHERE orders.processed = false AND (orders.statuscode = 1 OR orders.statuscode = 6) AND (u.current_month_qv >= 100 AND (u.account_status::text <> ALL (ARRAY[\'TERMINATED\'::character varying, \'SUSPENDED\'::character varying]::text[])) OR u.current_product_id = 14)
  ORDER BY "orderItem".productid;        
        ');
    }
}
