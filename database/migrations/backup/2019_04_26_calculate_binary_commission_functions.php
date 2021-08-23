<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BinaryCommissionFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createGetUserSubtreeFunction();
        $this->createIsBinaryTreeUserFunction();
        $this->createGetActiveSubtreeUsersFunction();
        $this->createSubtreeTotalFunction();
        $this->createBinaryCommissionPercentFunction();
        $this->createApplyLimitationsByRankFunction();

        $this->createBinaryCommissionCalculationFunction();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
            DROP FUNCTION IF EXISTS calculate_binary_commission();
            DROP FUNCTION IF EXISTS get_user_subtree(character varying, integer);
            DROP FUNCTION IF EXISTS apply_limitations_by_rank(integer, numeric, character varying);
            DROP FUNCTION IF EXISTS get_active_subtree_users(integer, integer);
            DROP FUNCTION IF EXISTS get_binary_commission_percent(integer, character varying);
            DROP FUNCTION IF EXISTS get_subtree_total(integer, integer, timestamp without time zone, timestamp without time zone);
            DROP FUNCTION IF EXISTS is_binary_commission_user(bigint);
        ');
    }

    private function createBinaryCommissionCalculationFunction()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.calculate_binary_commission(
	)
    RETURNS void
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE
	from_date timestamp;
	to_date timestamp;
	buser RECORD;
	tbl_left RECORD;
	tbl_right RECORD;
	gross_volume numeric :=0;
	total_left numeric :=0;
	total_right numeric :=0;
	payment numeric := 0;
	percents numeric := 0;
	active_left numeric := 0;
	active_right numeric := 0;
	commission_count bigint;
	
	binary_users CURSOR
		FOR SELECT 
			bp.id as node_id,
			bp.user_id as user_id,
			COALESCE(u.current_left_carryover, 0) as left_carryover,
			COALESCE(u.current_right_carryover, 0) as right_carryover,
			u.current_product_id,
			u.current_month_rank,
			p.itemcode
		FROM binary_plan bp
		JOIN users u
		ON bp.user_id = u.id
		JOIN products p
		ON u.current_product_id = p.id;
begin
	from_date := (NOW() - interval \'12 days\')::date; -- Monday prev week
	to_date := (NOW() - interval \'5 days\')::date; -- Sunday prev week

	SELECT count(*) into commission_count FROM binary_commission
	WHERE week_ending = to_date;
	
	IF commission_count > 0 THEN
		return;
	END IF;

	OPEN binary_users;       
    LOOP
		FETCH binary_users INTO buser;
		EXIT WHEN NOT FOUND;	
		
		IF is_binary_commission_user(buser.user_id) THEN
			-- get left and right subtree key borders
			SELECT * FROM get_user_subtree(\'L\', buser.node_id) INTO tbl_left;
			SELECT * FROM get_user_subtree(\'R\', buser.node_id) INTO tbl_right;
			
			-- check subtrees
			SELECT get_active_subtree_users(tbl_left.left_key, tbl_left.right_key) INTO active_left;
			SELECT get_active_subtree_users(tbl_right.left_key, tbl_right.right_key) INTO active_right;
			
			IF active_left > 0 and active_right > 0 THEN 
				-- calculate total left and total right amount
				SELECT get_subtree_total(tbl_left.left_key, tbl_left.right_key, from_date, to_date) INTO total_left;
				SELECT get_subtree_total(tbl_right.left_key, tbl_right.right_key, from_date, to_date) INTO total_right;

				total_left:=total_left + buser.left_carryover;
				total_right:=total_right + buser.right_carryover;

				IF total_left >= 500 and total_right >= 500 THEN
					-- calculate gross volume
					IF total_left > total_right THEN
						gross_volume:=total_right;
					ELSE
						gross_volume:=total_left;
					END IF;

					-- calculate commission percent and payment
					SELECT get_binary_commission_percent(buser.current_month_rank, buser.itemcode) INTO percents;
					
					payment:= percents * gross_volume;
					SELECT apply_limitations_by_rank(buser.current_month_rank, payment, buser.itemcode) INTO payment;

					-- save data
					INSERT INTO binary_commission (
						 user_id,
						 carryover_left,
						 carryover_right,
						 total_volume_left,
						 total_volume_right,
						 gross_volume,
						 commission_percent,
						 amount_earned,
						 week_ending
					)
					VALUES (
						buser.user_id,
						buser.left_carryover,
						buser.right_carryover,
						total_left,
						total_right,
						gross_volume,
						percents,
						payment,
						to_date
					);

					-- update current carryover values
					UPDATE users
						SET current_left_carryover = total_left - gross_volume,
						current_right_carryover = total_right - gross_volume
						WHERE id = buser.user_id;
				END IF;
			END IF;
		END IF;
   END LOOP;
end;$BODY$;
        ');
    }

    private function createGetUserSubtreeFunction()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_user_subtree(
	tree_direction character varying,
	node_parent_id integer)
    RETURNS TABLE(left_key integer, right_key integer) 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
	RETURN QUERY
	SELECT _lft, _rgt
	FROM binary_plan bp
	WHERE bp.parent_id = node_parent_id AND direction = tree_direction;
END;
$BODY$;
        ');
    }

    private function createIsBinaryTreeUserFunction()
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
    }

    private function createGetActiveSubtreeUsersFunction()
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
		WHERE _lft >= left_key AND _rgt <= right_key
			AND current_month_qv >= 100
			AND account_status NOT IN (\'TERMINATED\', \'SUSPENDED\');
$BODY$;
        ');
    }

    private function createSubtreeTotalFunction()
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

    private function createBinaryCommissionPercentFunction()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.get_binary_commission_percent(
	rank_value integer,
	product_code character varying)
    RETURNS numeric
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$
	SELECT CASE 
		WHEN rank_value = 10 and product_code = \'Coach\' THEN  0.1
		WHEN rank_value = 10 and product_code = \'Business\' THEN 0.12
		WHEN rank_value = 10 and product_code = \'First\' THEN 0.2
		WHEN rank_value = 20 THEN 0.12
		WHEN rank_value = 30 THEN 0.12
		WHEN rank_value = 40 THEN 0.12
		WHEN rank_value = 50 THEN 0.15
		WHEN rank_value = 60 THEN 0.16
		WHEN rank_value = 70 THEN 0.17
		WHEN rank_value = 80 THEN 0.18
		WHEN rank_value = 90 THEN 0.19
		WHEN rank_value = 100 THEN 0.2
		WHEN rank_value = 110 THEN 0.2
		WHEN rank_value = 120 THEN 0.2
		WHEN rank_value = 130 THEN 0.2
		WHEN rank_value = 140 THEN 0.2
		ELSE 0
	END;
$BODY$;
        ');
    }

    private function createApplyLimitationsByRankFunction()
    {
        DB::statement('
CREATE OR REPLACE FUNCTION public.apply_limitations_by_rank(
	rank_value integer,
	current_payment numeric,
	product_code character varying)
    RETURNS numeric
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$
	SELECT CASE 
		WHEN rank_value = 10 and current_payment > 1000 and product_code = \'Coach\' THEN 1000
		WHEN rank_value = 10 and current_payment > 2500 and product_code = \'Business\' THEN 2500
		WHEN rank_value = 10 and current_payment > 10000 and product_code = \'First\' THEN 10000
		WHEN rank_value = 20 and current_payment > 1500 THEN 1500
		WHEN rank_value = 30 and current_payment > 2000 THEN 2000
		WHEN rank_value = 40 and current_payment > 2500 THEN 2500
		WHEN rank_value = 50 and current_payment > 3000 THEN 3000
		WHEN rank_value = 60 and current_payment > 5000 THEN 5000
		WHEN rank_value = 70 and current_payment > 10000 THEN 10000
		WHEN rank_value = 80 and current_payment > 20000 THEN 20000
		WHEN rank_value = 90 and current_payment > 30000 THEN 30000
		WHEN rank_value = 100 and current_payment > 50000 THEN 50000
		WHEN rank_value = 110 and current_payment > 100000 THEN 100000 
		WHEN rank_value = 120 and current_payment > 150000 THEN 150000
		WHEN rank_value = 130 and current_payment > 200000 THEN 200000
		WHEN rank_value = 140 and current_payment > 250000 THEN 250000
		ELSE current_payment
	END;
$BODY$;
        ');
    }
}
