<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQcAndAcToVorderuserspaymentmethods extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("DROP VIEW IF EXISTS vorderuserspaymentmethods;");
        DB::statement("
            CREATE VIEW vorderuserspaymentmethods AS
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
            FROM orders o
            JOIN users u ON o.userid = u.id
            LEFT JOIN payment_methods pm ON o.payment_methods_id = pm.id
            LEFT JOIN payment_method_type pmt ON pm.pay_method_type = pmt.id;
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("DROP VIEW IF EXISTS vorderuserspaymentmethods;");
        DB::statement("
            CREATE VIEW vorderuserspaymentmethods AS
            SELECT o.statuscode,
            o.id AS order_id,
            o.ordersubtotal,
            o.ordertotal,
            o.orderbv,
            o.payment_methods_id,
            o.shipping_address_id,
            o.created_dt,
            u.distid,
            pmt.pay_method_name
            FROM orders o
            JOIN users u ON o.userid = u.id
            LEFT JOIN payment_methods pm ON o.payment_methods_id = pm.id
            LEFT JOIN payment_method_type pmt ON pm.pay_method_type = pmt.id;
            ");
    }

}
