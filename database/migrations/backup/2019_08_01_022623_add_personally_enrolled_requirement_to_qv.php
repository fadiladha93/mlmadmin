<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonallyEnrolledRequirementToQv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_rank_by_percentage(
	dist_id character varying,
	rankdefid bigint,
	ranklimit numeric,
	qualifyqv bigint,
	active_left numeric,
	active_right numeric
	)
    RETURNS rank
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE 
 	cur_rank rank;
 	rank_qv bigint;
	startdate_of_month timestamp;
    enddate_of_month timestamp;
	pqv numeric;
 BEGIN
    --RAISE NOTICE \'QUALIFY QV %\',qualifyqv;
	--RAISE NOTICE \'RANK LIMIT %\',ranklimit;
	
	SELECT sum(
		CASE WHEN COALESCE(current_month_qv, 0) > qualifyqv*ranklimit 
			THEN qualifyqv*ranklimit 
		ELSE COALESCE(current_month_qv, 0) 
		END) INTO rank_qv
	FROM users WHERE sponsorid = dist_id;
	
	--RAISE NOTICE \'RANK QV %\',rank_qv;
	
	-- first date of month
    SELECT date_trunc(\'month\', NOW())::DATE into startdate_of_month;
    -- last date of month
    SELECT (date_trunc(\'month\', NOW())::DATE + interval \'1 month - 1 millisecond\')::DATE into enddate_of_month;
	
	SELECT COALESCE(SUM(o.orderqv), 0) into pqv
        FROM users u
        JOIN orders o
        ON u.id = o.userid
        WHERE o.created_dt >= startdate_of_month
            AND o.created_dt <= enddate_of_month
            AND (o.statuscode = 1 OR o.statuscode = 6)
            AND u.distid = dist_id;

	rank_qv = rank_qv + pqv;

	SELECT rankval,rankdesc,rank_qv 
	FROM rank_definition INTO cur_rank  
	WHERE
		rank_qv >= min_qv
		AND id = rankdefid
		AND active_left >= min_binary_count
		AND active_right >= min_binary_count
		LIMIT 1;
	
	--RAISE NOTICE \'RANK VALUE %\',cur_rank.rankval;
	
	RETURN cur_rank;
		
END;$BODY$;
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
CREATE OR REPLACE FUNCTION public.get_rank_by_percentage(
	dist_id character varying,
	rankdefid bigint,
	ranklimit numeric,
	qualifyqv bigint,
	active_left numeric,
	active_right numeric
	)
    RETURNS rank
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE 
 	cur_rank rank;
 	rank_qv bigint;
	startdate_of_month timestamp;
    enddate_of_month timestamp;
	pqv numeric;
 BEGIN
    --RAISE NOTICE \'QUALIFY QV %\',qualifyqv;
	--RAISE NOTICE \'RANK LIMIT %\',ranklimit;
	
	SELECT sum(
		CASE WHEN COALESCE(current_month_qv, 0) > qualifyqv*ranklimit 
			THEN qualifyqv*ranklimit 
		ELSE COALESCE(current_month_qv, 0) 
		END) INTO rank_qv
	FROM users WHERE sponsorid = dist_id;
	
	--RAISE NOTICE \'RANK QV %\',rank_qv;
	
	-- first date of month
    SELECT date_trunc(\'month\', NOW())::DATE into startdate_of_month;
    -- last date of month
    SELECT (date_trunc(\'month\', NOW())::DATE + interval \'1 month - 1 millisecond\')::DATE into enddate_of_month;
	
	SELECT COALESCE(SUM(o.orderqv), 0) into pqv
        FROM users u
        JOIN orders o
        ON u.id = o.userid
        WHERE o.created_dt >= startdate_of_month
            AND o.created_dt <= enddate_of_month
            AND (o.statuscode = 1 OR o.statuscode = 6)
            AND u.distid = dist_id;

	rank_qv = rank_qv + pqv;

	SELECT rankval,rankdesc,rank_qv 
	FROM rank_definition INTO cur_rank  
	WHERE
		rank_qv >= min_qv
		AND id = rankdefid
		-- AND active_left >= min_binary_count
		-- AND active_right >= min_binary_count
		LIMIT 1;
	
	--RAISE NOTICE \'RANK VALUE %\',cur_rank.rankval;
	
	RETURN cur_rank;
		
END;$BODY$;
        ');
    }
}
