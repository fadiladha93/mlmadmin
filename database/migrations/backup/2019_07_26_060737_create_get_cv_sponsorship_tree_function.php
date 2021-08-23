<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetCvSponsorshipTreeFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_cv_volume_sponsorship_tree(from_date timestamp, to_date timestamp, u_distid varchar)
    RETURNS TABLE(cv_volume bigint)
    LANGUAGE \'plpgsql\'

    COST 100
        VOLATILE 
        ROWS 1000
    AS $BODY$
    BEGIN
	    RETURN QUERY
	    SELECT SUM(oi.cv) as cv_volume
        FROM get_distributors_tree(u_distid) u
        JOIN orders o
        ON u.id = o.userid
        JOIN "orderItem" oi
        ON o.id = oi.orderid
        WHERE o.created_dt >= from_date
        AND o.created_dt <= to_date
        AND (o.statuscode = 1 OR o.statuscode = 6)
        AND u.distid <> u_distid;
    END;
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
        DB::statement('DROP FUNCTION IF EXISTS get_cv_volume_sponsorship_tree(from_date timestamp, to_date timestamp, distid varchar);');
    }
}
