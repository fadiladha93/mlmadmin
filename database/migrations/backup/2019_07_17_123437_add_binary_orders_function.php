<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBinaryOrdersFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_binary_orders(
	dist_id character varying,
	direction character varying,
	from_date timestamp without time zone,
	to_date timestamp without time zone)
    RETURNS TABLE(order_id bigint , order_item bigint, cv integer, productname character varying, producttype integer, created_dt timestamp without time zone, user_id integer) 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
DECLARE
	tbl_subtree RECORD;
	buser_id integer;
begin
   SELECT bp.id INTO buser_id
   FROM binary_plan bp
		JOIN users u
		ON bp.user_id = u.id
   where distid = dist_id;

		-- get left and right subtree key borders
		SELECT * FROM get_user_subtree(direction, buser_id) INTO tbl_subtree;
	
		RETURN QUERY
		SELECT o.id as order, oi.id as order_item_id, oi.cv, p.productname, p.producttype, o.created_dt, o.userid
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
            AND _lft >= tbl_subtree.left_key AND _rgt <= tbl_subtree.right_key
            AND (p.producttype = 1 OR p.producttype = 2)
			AND oi.cv <> 0
            ;
end;$BODY$;
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
DROP FUNCTION get_binary_orders(character varying,character varying,timestamp without time zone,timestamp without time zone)
        ');
    }
}
