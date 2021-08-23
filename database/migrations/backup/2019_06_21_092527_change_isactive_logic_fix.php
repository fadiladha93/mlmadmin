<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIsactiveLogicFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_bc_active')
                ->default(0);
        });
        DB::statement('create or replace function get_active_subtree_users(left_key integer, right_key integer) returns bigint
          language sql
        as
        $$
        SELECT COUNT(*)
                FROM binary_plan bp
                JOIN users u
                ON bp.user_id = u.id
                WHERE _lft >= left_key
                AND _rgt <= right_key
                AND (u.is_bc_active = 1 OR u.is_activate = 1)
                ;
        $$;');

        DB::statement('create or replace function is_binary_commission_user(user_id bigint) returns boolean
          language sql
        as
        $$
        SELECT CASE WHEN user_count<1 THEN FALSE ELSE TRUE END
        		FROM
        			(
        				SELECT COUNT(*) as user_count
        			 	FROM users
        			 	WHERE 
        					id=user_id
        					AND (is_bc_active = 1 OR is_activate = 1)
        			) as u
        
        $$;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement('create or replace function get_active_subtree_users(left_key integer, right_key integer) returns bigint
          language sql
        as
        $$
        SELECT COUNT(*)
                FROM binary_plan bp
                JOIN users u
                ON bp.user_id = u.id
                WHERE _lft >= left_key
                AND _rgt <= right_key
                AND (u.is_active = 1 OR u.is_activate = 1)
                ;
        $$;');

        DB::statement('create or replace function is_binary_commission_user(user_id bigint) returns boolean
          language sql
        as
        $$
        SELECT CASE WHEN user_count<1 THEN FALSE ELSE TRUE END
        		FROM
        			(
        				SELECT COUNT(*) as user_count
        			 	FROM users
        			 	WHERE 
        					id=user_id
        					AND (is_active = 1 OR is_activate = 1)
        			) as u
        
        $$;
        ');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_bc_active');

        });
    }

}
