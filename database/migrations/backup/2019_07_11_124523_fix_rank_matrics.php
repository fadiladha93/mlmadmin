<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRankMatrics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_rank_metrice(
	dist_id character varying,
	rval integer)
    RETURNS rankmetrice
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE 
             rdef RECORD;
             nextrdef RECORD;
             rmetrice rankmetrice;
             max_qv bigint;
                 
             BEGIN
                SELECT * FROM rank_definition WHERE rankval = rval INTO rdef;
                SELECT * FROM rank_definition WHERE rankval = rval + 10 INTO nextrdef;
                
                SELECT sum(
					CASE WHEN COALESCE(current_month_qv, 0) > nextrdef.min_qv * nextrdef.rank_limit 
						THEN nextrdef.min_qv * nextrdef.rank_limit
					ELSE COALESCE(current_month_qv, 0)
					END) as rankqv,
					rdef.rankdesc as rankdesc,
					nextrdef.min_qv as nextlevel_rankqv,
					nextrdef.rankdesc as nextlevel_rankdesc,
					nextrdef.min_qv*nextrdef.rank_limit as min_qv,
                	nextrdef.rank_limit * 100 as nextlevel_percentage,
					rdef.id as rankid,
					nextrdef.min_tsa as nextlevel_ranktsa,
					nextrdef.min_qc as nextlevel_qc,
					nextrdef.qc_percent * 100 as next_qc_percentage,
					nextrdef.min_binary_count as binary_limit
                FROM users WHERE sponsorid = dist_id
                INTO rmetrice;
                
                RETURN rmetrice;
                
            END;$BODY$;
        ');

        DB::statement('
ALTER TYPE public.rankmetrice 
ADD ATTRIBUTE nextlevel_qc integer,
ADD ATTRIBUTE next_qc_percentage integer,
ADD ATTRIBUTE binary_limit integer;
        ');

        DB::statement('UPDATE products SET qc = 1 WHERE id IN (26, 15)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
ALTER TYPE public.rankmetrice 
DROP ATTRIBUTE IF EXISTS nextlevel_qc, 
DROP ATTRIBUTE IF EXISTS next_qc_percentage,
DROP ATTRIBUTE IF EXISTS binary_limit;
        ');

        DB::statement('
       CREATE OR REPLACE FUNCTION public.get_rank_metrice(
	dist_id character varying,
	rval integer)
    RETURNS rankmetrice
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE 
             
             rdef RECORD;
             nextrdef RECORD;
             rmetrice rankmetrice;
             max_qv bigint;
             
                 
             BEGIN
             
                SELECT * FROM rank_definition WHERE rankval=rval INTO rdef;
                SELECT * FROM rank_definition WHERE rankval=rval+10 INTO nextrdef;
                
                SELECT sum(CASE WHEN COALESCE(current_month_qv, 0) > nextrdef.min_qv*nextrdef.rank_limit THEN nextrdef.min_qv*nextrdef.rank_limit ELSE COALESCE(current_month_qv, 0) END) as rankqv
                ,rdef.rankdesc as rankdesc, nextrdef.min_qv as nextlevel_rankqv, nextrdef.rankdesc as nextlevel_rankdesc,nextrdef.min_qv*nextrdef.rank_limit as min_qv,
                nextrdef.rank_limit*100 as nextlevel_percentage, rdef.id as rankid,nextrdef.min_tsa as nextlevel_ranktsa
                FROM users WHERE sponsorid=dist_id
                INTO rmetrice;
                
                RETURN rmetrice;
                
            END;$BODY$;
        ');

        DB::statement('
        UPDATE products SET qc = 0.5 WHERE id = 26
        ');

        DB::statement('
        UPDATE products SET qc = 0 WHERE id = 15
        ');
    }
}
