<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateRankMetriceFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TYPE rankmetrice ADD ATTRIBUTE rankid int;');
        DB::statement('
            --
            -- Name: get_rank_metrice(character varying, integer); Type: FUNCTION; Schema: public; Owner: cbdevL2n
            --
            
            CREATE OR REPLACE FUNCTION public.get_rank_metrice(dist_id character varying, rval integer) RETURNS public.rankmetrice
                LANGUAGE plpgsql
                AS $$DECLARE 
             
             rdef RECORD;
             nextrdef RECORD;
             rmetrice rankmetrice;
             max_qv bigint;
             
                 
             BEGIN
             
                SELECT * FROM rank_definition WHERE rankval=rval INTO rdef;
                SELECT * FROM rank_definition WHERE rankval=rval+10 INTO nextrdef;
                
                SELECT sum(CASE WHEN COALESCE(current_month_qv, 0) > nextrdef.min_qv*nextrdef.rank_limit THEN nextrdef.min_qv*nextrdef.rank_limit ELSE COALESCE(current_month_qv, 0) END) as rankqv
                ,rdef.rankdesc as rankdesc, nextrdef.min_qv as nextlevel_rankqv, nextrdef.rankdesc as nextlevel_rankdesc,nextrdef.min_qv*nextrdef.rank_limit as min_qv,
                nextrdef.rank_limit*100 as nextlevel_percentage, rdef.id as rankid
                FROM users WHERE sponsorid=dist_id
                INTO rmetrice;
                
                RETURN rmetrice;
                
            END;$$;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TYPE rankmetrice DROP ATTRIBUTE IF EXISTS rankid;');
        DB::statement('
            --
            -- Name: get_rank_metrice(character varying, integer); Type: FUNCTION; Schema: public; Owner: cbdevL2n
            --
            
            CREATE OR REPLACE FUNCTION public.get_rank_metrice(dist_id character varying, rval integer) RETURNS public.rankmetrice
                LANGUAGE plpgsql
                AS $$DECLARE 
             
             rdef RECORD;
             nextrdef RECORD;
             rmetrice rankmetrice;
             max_qv bigint;
             
                 
             BEGIN
             
                SELECT * FROM rank_definition WHERE rankval=rval INTO rdef;
                SELECT * FROM rank_definition WHERE rankval=rval+10 INTO nextrdef;
                
                SELECT sum(CASE WHEN COALESCE(current_month_qv, 0) > nextrdef.min_qv*nextrdef.rank_limit THEN nextrdef.min_qv*nextrdef.rank_limit ELSE COALESCE(current_month_qv, 0) END) as rankqv
                ,rdef.rankdesc as rankdesc, nextrdef.min_qv as nextlevel_rankqv, nextrdef.rankdesc as nextlevel_rankdesc,nextrdef.min_qv*nextrdef.rank_limit as min_qv,
                nextrdef.rank_limit*100 as nextlevel_percentage
                FROM users WHERE sponsorid=dist_id
                INTO rmetrice;
                
                RETURN rmetrice;
                
            END;$$;
        ');
    }
}
