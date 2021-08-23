<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFsbAndQvTsa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_qualifying_qv_tsa(
	dist_id character varying,
	rval integer)
    RETURNS SETOF sponseeqv 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$DECLARE 
 rdef RECORD;	 
 nextrdef RECORD;	
 BEGIN
 
 	SELECT * FROM rank_definition WHERE rankval=rval INTO rdef;
	SELECT * FROM rank_definition WHERE rankval=rval+10 INTO nextrdef;
 
 	RETURN QUERY
	SELECT id as user_id,firstname,lastname,CAST(CASE WHEN COALESCE(current_month_qv, 0) > nextrdef.min_qv*nextrdef.rank_limit THEN nextrdef.min_qv*nextrdef.rank_limit ELSE COALESCE(current_month_qv, 0) END as bigint) as qv_contribution,nextrdef.min_qv*nextrdef.rank_limit as min_qv,
	display_name,recognition_name,CAST(CASE WHEN COALESCE(current_month_tsa, 0) > rdef.min_tsa*rdef.rank_limit THEN round(coalesce(rdef.min_tsa*rdef.rank_limit,0),0) ELSE COALESCE(current_month_tsa, 0) END as integer) as tsa_contribution,round(coalesce(rdef.min_tsa*rdef.rank_limit,0),0) as min_tsa
	FROM users WHERE sponsorid=dist_id Order By qv_contribution DESC;
		
END;$BODY$;
        ');

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
            DROP FUNCTION IF EXISTS get_qualifying_qv_tsa(character varying, integer);
        ');

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
  WHERE orders.processed = false AND (orders.statuscode = 1 OR orders.statuscode = 6)
  ORDER BY "orderItem".productid;
        ');
    }
}
