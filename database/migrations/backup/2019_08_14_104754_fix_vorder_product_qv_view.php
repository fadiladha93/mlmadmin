<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixVorderProductQvView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vorder_product_qv;");

        DB::statement("CREATE OR REPLACE VIEW vorder_product_qv AS
            SELECT o.userid,
                p.id AS productid,
                i.qv,
                i.cv,
                i.orderid,
                i.id AS orderitemid,
                i.created_dt,
                i.qc,
                o.userid AS order_userid
            FROM ((public.orders o
                JOIN public.\"orderItem\" i ON ((o.id = i.orderid)))
                JOIN public.products p ON ((p.id = i.productid)));");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vorder_product_qv;");

        DB::statement("CREATE OR REPLACE VIEW vorder_product_qv AS
            SELECT o.userid,
                p.id AS productid,
                p.qv,
                p.cv,
                i.orderid,
                i.id AS orderitemid,
                o.created_dt,
                p.qc,
                o.userid AS order_userid
            FROM ((public.orders o
                JOIN public.\"orderItem\" i ON ((o.id = i.orderid)))
                JOIN public.products p ON ((p.id = i.productid)));");
    }
}
