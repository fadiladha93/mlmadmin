<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWeekEnding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
	rank_val integer;
	is_active boolean := false;
	t_left_carryover numeric := 0;
	t_right_carryover numeric := 0;
	
	binary_users CURSOR
		FOR SELECT 
			bp.id as node_id,
			bp.user_id as user_id,
			COALESCE(u.current_left_carryover, 0) as left_carryover,
			COALESCE(u.current_right_carryover, 0) as right_carryover,
			u.current_product_id,
			u.current_month_rank,
			p.id as rank_id
		FROM binary_plan bp
		JOIN users u
		ON bp.user_id = u.id
		JOIN products p
		ON u.current_product_id = p.id;
begin
	from_date := (NOW() - interval \'6 days\')::date; -- Monday current week
	to_date := NOW()::date; -- current Sunday (today)

	OPEN binary_users;       
    LOOP
		FETCH binary_users INTO buser;
		EXIT WHEN NOT FOUND;	
		
		-- get left and right subtree key borders
		SELECT * FROM get_user_subtree(\'L\', buser.node_id) INTO tbl_left;
		SELECT * FROM get_user_subtree(\'R\', buser.node_id) INTO tbl_right;
			
		-- check subtrees
		SELECT get_active_subtree_users(tbl_left.left_key, tbl_left.right_key) INTO active_left;
		SELECT get_active_subtree_users(tbl_right.left_key, tbl_right.right_key) INTO active_right;
			
		-- calculate total left and total right amount
		SELECT get_subtree_total(tbl_left.left_key, tbl_left.right_key, from_date, to_date) INTO total_left;
		SELECT get_subtree_total(tbl_right.left_key, tbl_right.right_key, from_date, to_date) INTO total_right;
	
		t_left_carryover := total_left;
		t_right_carryover := total_right;

		IF is_binary_commission_user(buser.user_id) THEN
			IF active_left > 0 and active_right > 0 THEN
				total_left:=total_left + buser.left_carryover;
				total_right:=total_right + buser.right_carryover;

				IF total_left >= 500 and total_right >= 500 THEN
					-- calculate gross volume
					IF total_left > total_right THEN
						gross_volume:=total_right;
					ELSE
						gross_volume:=total_left;
					END IF;

					SELECT COALESCE(MAX(rankval), 0) INTO rank_val FROM rank_history rh
					JOIN rank_definition rd
					ON rh.lifetime_rank = rd.rankval
					WHERE rh.users_id = buser.user_id
					AND created_dt >= date_trunc(\'month\', now()::date) - interval \'1 month\';
					
					IF rank_val = 0 THEN
						rank_val := buser.current_month_rank;
					END IF;

					-- calculate commission percent and payment
					SELECT get_binary_commission_percent(rank_val, buser.rank_id, buser.user_id) INTO percents;
					
					payment:= percents * gross_volume;
					SELECT apply_limitations_by_rank(rank_val, payment, buser.rank_id) INTO payment;
					
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

					t_left_carryover := total_left - gross_volume - buser.left_carryover;
					t_right_carryover := total_right - gross_volume - buser.right_carryover;
				END IF;
			END IF;
		END IF;
		
		-- update current carryover values
		UPDATE users
			SET current_left_carryover = current_left_carryover + t_left_carryover
				WHERE id = buser.user_id;
		UPDATE users
			SET
				current_right_carryover = current_right_carryover + t_right_carryover
				WHERE id = buser.user_id;
   END LOOP;
   
   UPDATE users
			SET current_left_carryover = 1000000
				WHERE current_left_carryover > 1000000;
	UPDATE users
			SET current_right_carryover = 1000000
				WHERE current_right_carryover > 1000000;
end;$BODY$;
        ');

        DB::statement('
            UPDATE binary_commission SET week_ending = \'2019-05-12\' WHERE week_ending = \'2019-05-17\'
        ');

        DB::statement('
            UPDATE binary_commission SET week_ending = \'2019-05-19\' WHERE week_ending = \'2019-05-24\'
        ');

        DB::statement('
            UPDATE binary_commission SET week_ending = \'2019-05-26\' WHERE week_ending = \'2019-05-31\'
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
		AND u.is_active = 1
		;
$BODY$;
        ');

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
					AND is_active = 1
			) as u  
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
	rank_val integer;
	is_active boolean := false;
	t_left_carryover numeric := 0;
	t_right_carryover numeric := 0;
	
	binary_users CURSOR
		FOR SELECT 
			bp.id as node_id,
			bp.user_id as user_id,
			COALESCE(u.current_left_carryover, 0) as left_carryover,
			COALESCE(u.current_right_carryover, 0) as right_carryover,
			u.current_product_id,
			u.current_month_rank,
			p.id as rank_id
		FROM binary_plan bp
		JOIN users u
		ON bp.user_id = u.id
		JOIN products p
		ON u.current_product_id = p.id;
begin
	from_date := (NOW() - interval \'6 days\')::date; -- Monday current week
	to_date := NOW()::date; -- current Sunday (today)

	OPEN binary_users;       
    LOOP
		FETCH binary_users INTO buser;
		EXIT WHEN NOT FOUND;	
		
		-- get left and right subtree key borders
		SELECT * FROM get_user_subtree(\'L\', buser.node_id) INTO tbl_left;
		SELECT * FROM get_user_subtree(\'R\', buser.node_id) INTO tbl_right;
			
		-- check subtrees
		SELECT get_active_subtree_users(tbl_left.left_key, tbl_left.right_key) INTO active_left;
		SELECT get_active_subtree_users(tbl_right.left_key, tbl_right.right_key) INTO active_right;
			
		-- calculate total left and total right amount
		SELECT get_subtree_total(tbl_left.left_key, tbl_left.right_key, from_date, to_date) INTO total_left;
		SELECT get_subtree_total(tbl_right.left_key, tbl_right.right_key, from_date, to_date) INTO total_right;
	
		t_left_carryover := total_left;
		t_right_carryover := total_right;

		IF is_binary_commission_user(buser.user_id) THEN
			IF active_left > 0 and active_right > 0 THEN
				total_left:=total_left + buser.left_carryover;
				total_right:=total_right + buser.right_carryover;

				IF total_left >= 500 and total_right >= 500 THEN
					-- calculate gross volume
					IF total_left > total_right THEN
						gross_volume:=total_right;
					ELSE
						gross_volume:=total_left;
					END IF;

					SELECT COALESCE(MAX(rankval), 0) INTO rank_val FROM rank_history rh
					JOIN rank_definition rd
					ON rh.lifetime_rank = rd.rankval
					WHERE rh.users_id = buser.user_id
					AND created_dt >= date_trunc(\'month\', now()::date) - interval \'1 month\';
					
					IF rank_val = 0 THEN
						rank_val := buser.current_month_rank;
					END IF;

					-- calculate commission percent and payment
					SELECT get_binary_commission_percent(rank_val, buser.rank_id, buser.user_id) INTO percents;
					
					payment:= percents * gross_volume;
					SELECT apply_limitations_by_rank(rank_val, payment, buser.rank_id) INTO payment;
					
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
						(NOW() + interval \'5 days\')::date
					);

					t_left_carryover := total_left - gross_volume - buser.left_carryover;
					t_right_carryover := total_right - gross_volume - buser.right_carryover;
				END IF;
			END IF;
		END IF;
		
		-- update current carryover values
		UPDATE users
			SET current_left_carryover = current_left_carryover + t_left_carryover
				WHERE id = buser.user_id;
		UPDATE users
			SET
				current_right_carryover = current_right_carryover + t_right_carryover
				WHERE id = buser.user_id;
   END LOOP;
   
   UPDATE users
			SET current_left_carryover = 1000000
				WHERE current_left_carryover > 1000000;
	UPDATE users
			SET current_right_carryover = 1000000
				WHERE current_right_carryover > 1000000;
end;$BODY$;
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

        DB::statement('
            UPDATE binary_commission SET week_ending = \'2019-05-17\' WHERE week_ending = \'2019-05-12\'
        ');

        DB::statement('
            UPDATE binary_commission SET week_ending = \'2019-05-24\' WHERE week_ending = \'2019-05-19\'
        ');

        DB::statement('
            UPDATE binary_commission SET week_ending = \'2019-05-31\' WHERE week_ending = \'2019-05-26\'
        ');

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
					AND is_active = 1
					AND account_status NOT IN (\'TERMINATED\', \'SUSPENDED\')
			) as u  
	$BODY$;
        ');
    }
}
