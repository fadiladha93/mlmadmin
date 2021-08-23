<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetSubtreeTotalQuery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_subtree_total(
    left_key integer,
    right_key integer,
    from_date timestamp without time zone,
    to_date timestamp without time zone)
    RETURNS bigint
    LANGUAGE \'sql\'            
    COST 100
    VOLATILE 
AS $BODY$SELECT COALESCE(SUM(oi.cv), 0)
        FROM binary_plan bp
        JOIN orders o
        ON bp.user_id = o.userid
        JOIN "orderItem" oi
        ON o.id = oi.orderid
        JOIN products p
        ON p.id = oi.productid
        WHERE o.created_dt >= from_date
            AND o.created_dt <= to_date
            AND (o.statuscode = 1 OR o.statuscode = 6)
            AND _lft >= left_key AND _rgt <= right_key
            AND (p.producttype = 1 OR p.producttype = 2)
            ;
$BODY$;
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
CREATE OR REPLACE FUNCTION public.get_subtree_total(
    left_key integer,
    right_key integer,
    from_date timestamp without time zone,
    to_date timestamp without time zone)
    RETURNS bigint
    LANGUAGE \'sql\'            
    COST 100
    VOLATILE 
AS $BODY$SELECT COALESCE(SUM(oi.bv), 0)
        FROM binary_plan bp
        JOIN orders o
        ON bp.user_id = o.userid
        JOIN "orderItem" oi
        ON o.id = oi.orderid
        JOIN products p
        ON p.id = oi.productid
        WHERE (o.created_dt between from_date and to_date)
            AND (o.statuscode = 1 OR o.statuscode = 6)
            AND _lft >= left_key AND _rgt <= right_key
            AND (p.producttype = 1 OR p.producttype = 2)
            ;
$BODY$;
        ');
    }
}
