<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixBinarySubtreeActiveFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_active_subtree_users(
	left_key integer,
	right_key integer,
	user_activity_date timestamp without time zone,
	root_distid character varying)
    RETURNS bigint
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$
            SELECT COUNT(*)
                FROM binary_plan bp
                JOIN users u
                ON bp.user_id = u.id
                JOIN (
                    SELECT * 
                    FROM user_activity_history 
                    WHERE created_at = DATE(user_activity_date)
                    ) h
                ON bp.user_id = h.user_id
                WHERE _lft >= left_key
                AND _rgt <= right_key
                AND h.is_active = true
				AND u.sponsorid = root_distid;
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
CREATE OR REPLACE FUNCTION public.get_active_subtree_users(
	left_key integer,
	right_key integer,
	user_activity_date timestamp without time zone,
	root_distid character varying)
    RETURNS bigint
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$
            SELECT COUNT(*)
                FROM binary_plan bp
                JOIN users u
                ON bp.user_id = u.id
                JOIN (
                    SELECT * 
                    FROM user_activity_history 
                    WHERE created_at = DATE(user_activity_date)
                    ) h
                ON bp.user_id = h.user_id
                WHERE _lft >= left_key
                AND _rgt <= right_key
                AND (h.is_bc_active = true OR h.is_activate = true)
				AND u.sponsorid = root_distid
                ;
        $BODY$;
        ');
    }
}
