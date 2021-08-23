<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaymentMethodTypeNameFieldSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vorderuserspaymentmethods;");

        Schema::table('payment_method_type', function (Blueprint $table) {
            $table->string('pay_method_name', 128)->change();
        });

        DB::statement("
            CREATE VIEW public.vorderuserspaymentmethods AS
            SELECT o.statuscode,
            o.id AS order_id,
            o.ordersubtotal,
            o.ordertotal,
            o.orderbv,
            o.ordercv,
            o.orderqc,
            o.orderac,
            o.payment_methods_id,
            o.shipping_address_id,
            o.created_dt,
            u.distid,
            pmt.pay_method_name
            FROM (((public.orders o
            JOIN public.users u ON ((o.userid = u.id)))
            LEFT JOIN public.payment_methods pm ON ((o.payment_methods_id = pm.id)))
            LEFT JOIN public.payment_method_type pmt ON ((pm.pay_method_type = pmt.id)));
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vorderuserspaymentmethods;");

        Schema::table('payment_method_type', function (Blueprint $table) {
            $table->string('pay_method_name', 20)->change();
        });

        DB::statement("
            CREATE VIEW public.vorderuserspaymentmethods AS
            SELECT o.statuscode,
            o.id AS order_id,
            o.ordersubtotal,
            o.ordertotal,
            o.orderbv,
            o.ordercv,
            o.orderqc,
            o.orderac,
            o.payment_methods_id,
            o.shipping_address_id,
            o.created_dt,
            u.distid,
            pmt.pay_method_name
            FROM (((public.orders o
            JOIN public.users u ON ((o.userid = u.id)))
            LEFT JOIN public.payment_methods pm ON ((o.payment_methods_id = pm.id)))
            LEFT JOIN public.payment_method_type pmt ON ((pm.pay_method_type = pmt.id)));
        ");
    }
}
