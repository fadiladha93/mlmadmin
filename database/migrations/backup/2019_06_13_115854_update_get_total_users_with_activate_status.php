<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetTotalUsersWithActivateStatus extends Migration
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
    right_key integer)
    RETURNS bigint
    LANGUAGE \'sql\'            
    COST 100
    VOLATILE 
AS $BODY$
    SELECT COUNT(*)
        FROM binary_plan bp
        JOIN users u
        ON bp.user_id = u.id
        WHERE _lft >= left_key
        AND _rgt <= right_key
        AND (u.is_active = 1 OR u.is_activate = 1)
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
CREATE OR REPLACE FUNCTION public.get_active_subtree_users(
    left_key integer,
    right_key integer)
    RETURNS bigint
    LANGUAGE \'sql\'            
    COST 100
    VOLATILE 
AS $BODY$
    SELECT COUNT(*)
        FROM binary_plan bp
        JOIN users u
        ON bp.user_id = u.id
        WHERE _lft >= left_key
        AND _rgt <= right_key
        AND u.is_active = 1                    
        ;
$BODY$;
        ');
    }
}
