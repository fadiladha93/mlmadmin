<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActiveUserCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.is_binary_commission_user(
	user_id bigint)
    RETURNS boolean
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$SELECT CASE WHEN user_count<1 THEN FALSE ELSE TRUE END
		FROM
			(
				SELECT COUNT(*) as user_count
			 	FROM users
			 	WHERE 
					id=user_id
					AND (
							(
								current_month_qv>=100 AND account_status NOT IN (\'TERMINATED\', \'SUSPENDED\')
							)
							OR
							(
								current_product_id=14
							)
						)
			) as u  
	$BODY$;
        ');

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
		AND 
		(
			(
				current_month_qv >= 100
				AND
				account_status NOT IN (\'TERMINATED\', \'SUSPENDED\')
			)
			OR
			(
				current_product_id=14
			)
		);
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
CREATE OR REPLACE FUNCTION public.is_binary_commission_user(
	user_id bigint)
    RETURNS boolean
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$SELECT CASE WHEN user_count<1 THEN FALSE ELSE TRUE END
		FROM
			(
				SELECT COUNT(*) as user_count
			 	FROM users
			 	WHERE id=user_id
					AND current_month_qv>=100
					AND account_status NOT IN (\'TERMINATED\', \'SUSPENDED\')
			) as u  
	$BODY$;
        ');

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
		WHERE _lft >= left_key AND _rgt <= right_key
			AND current_month_qv >= 100
			AND account_status NOT IN (\'TERMINATED\', \'SUSPENDED\');
$BODY$;
        ');
    }
}
