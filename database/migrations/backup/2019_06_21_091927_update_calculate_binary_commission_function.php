<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCalculateBinaryCommissionFunction extends Migration
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
AS $BODY$
DECLARE
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
	current_carryover RECORD;
	left_carryover numeric := 0;
	right_carryover numeric := 0;
	current_bc_commission RECORD;
	
	binary_users CURSOR
		FOR SELECT 
			bp.id as node_id,
			bp.user_id as user_id,
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

    -- save commission in history
	INSERT INTO bc_history (
		start_date,
		end_date
	)
	VALUES (
		from_date,
		to_date
	);

    -- get last executed commission
	SELECT * FROM get_current_processed_binary_commission() INTO current_bc_commission;

	OPEN binary_users;       
    LOOP
		FETCH binary_users INTO buser;
		EXIT WHEN NOT FOUND;

        -- get left and right current carryover
		SELECT * FROM get_user_current_carryover(buser.user_id) INTO current_carryover;

		left_carryover := COALESCE(current_carryover.left_value, 0);
		right_carryover := COALESCE(current_carryover.right_value, 0);

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

				total_left:=total_left + left_carryover;
				total_right:=total_right + right_carryover;

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
						left_carryover,
						right_carryover,
						total_left,
						total_right,
						gross_volume,
						percents,
						payment,
						to_date
					);

					t_left_carryover := total_left - gross_volume - left_carryover;
					t_right_carryover := total_right - gross_volume - right_carryover;
				END IF;
			END IF;
		END IF;
		
		--save user carryover
		INSERT INTO bc_carryover_history (
			user_id,
			right_carryover,
			left_carryover,
			bc_history_id
		)
		VALUES (
			buser.user_id,
			right_carryover + t_right_carryover,
			left_carryover + t_left_carryover,
			current_bc_commission.id
		);

		-- update current carryover values
		UPDATE users
			SET current_left_carryover = left_carryover + t_left_carryover
				WHERE id = buser.user_id;
		UPDATE users
			SET
				current_right_carryover = right_carryover + t_right_carryover
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
CREATE OR REPLACE FUNCTION public.get_user_current_carryover(uid integer)
    RETURNS TABLE(right_value DOUBLE PRECISION, left_value DOUBLE PRECISION) 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
	RETURN QUERY
	SELECT
	    COALESCE(right_carryover, 0) as right_value,
	    COALESCE(left_carryover, 0) as left_value
	FROM
	    bc_carryover_history ch
	JOIN (
	    SELECT id
	    FROM bc_history
	    ORDER BY id DESC
	    OFFSET 1
	    LIMIT 1
	) last_commission
	ON last_commission.id = ch.bc_history_id
	WHERE ch.user_id = uid;
END;
$BODY$;
        ');

        DB::statement('
CREATE OR REPLACE FUNCTION public.get_current_processed_binary_commission()
    RETURNS TABLE(id integer, start_date timestamp, end_date timestamp) 
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$
BEGIN
	RETURN QUERY
	SELECT
        *
	FROM
	    bc_history h
	ORDER BY id DESC
	LIMIT 1;
END;
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
            DROP FUNCTION IF EXISTS get_user_current_carryover(integer);
        ');

        DB::statement('
            DROP FUNCTION IF EXISTS get_current_processed_binary_commission();
        ');
    }
}
