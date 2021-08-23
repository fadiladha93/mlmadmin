<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedAtDateTimezone extends Migration
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
AS $BODY$
	SELECT COALESCE(SUM(o.orderbv), 0)
		FROM binary_plan bp
		JOIN orders o
		ON bp.user_id = o.userid
		WHERE ((TO_TIMESTAMP(CONCAT(o.created_date, \' \' , o.created_time),\'YYYY-MM-DD HH24:MI:SS\') + interval \'-5 hours\')::DATE between from_date and to_date)
			AND (o.statuscode = 1 OR o.statuscode = 6)
			AND _lft >= left_key AND _rgt <= right_key;
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
AS $BODY$
	SELECT COALESCE(SUM(o.orderbv), 0)
		FROM binary_plan bp
		JOIN orders o
		ON bp.user_id = o.userid
		WHERE (o.created_date between from_date and to_date)
			AND (o.statuscode = 1 OR o.statuscode = 6)
			AND _lft >= left_key AND _rgt <= right_key;
$BODY$;
        ');
    }
}
