<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCvEnrolmentTreeTsa extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        
        DB::statement("CREATE TYPE public.distributor_5 AS
(
	level integer,
	id bigint,
	current_month_pqv bigint,
	distid character varying,
        current_month_cv bigint
);");
        DB::statement("DROP FUNCTION IF EXISTS enrolment_tree_tsa(character varying);");
        DB::statement("CREATE OR REPLACE FUNCTION public.enrolment_tree_tsa(
	dist_id character varying)
    RETURNS SETOF distributor_5 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS \$BODY$
            BEGIN
            RETURN QUERY WITH RECURSIVE distributors AS (
            SELECT 0 as level,id,current_month_pqv,distid,current_month_cv
            FROM users
            WHERE distid = dist_id
            UNION
            SELECT d.level+1,sp.id,sp.current_month_pqv,sp.distid,sp.current_month_cv
            FROM users sp
            INNER JOIN distributors d ON d.distid = sp.sponsorid
            ) SELECT
             *
            FROM
            distributors;
            END
            \$BODY$;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("DROP FUNCTION IF EXISTS enrolment_tree_tsa(character varying);");
        DB::statement("DROP TYPE public.distributor_5;");
        DB::statement("CREATE OR REPLACE FUNCTION public.enrolment_tree_tsa(
	dist_id character varying)
    RETURNS SETOF distributor_3 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS \$BODY$
            BEGIN
            RETURN QUERY WITH RECURSIVE distributors AS (
            SELECT 0 as level,id,current_month_pqv,distid
            FROM users
            WHERE distid = dist_id
            UNION
            SELECT d.level+1,sp.id,sp.current_month_pqv,sp.distid
            FROM users sp
            INNER JOIN distributors d ON d.distid = sp.sponsorid
            ) SELECT
             *
            FROM
            distributors;
            END
            \$BODY$;");
    }

}
