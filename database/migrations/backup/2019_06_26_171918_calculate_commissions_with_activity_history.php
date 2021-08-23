<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalculateCommissionsWithActivityHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP FUNCTION IF EXISTS get_active_subtree_users(left_key integer, right_key integer)');
        DB::statement('DROP FUNCTION IF EXISTS is_binary_commission_user(user_id bigint)');

        DB::statement('
        CREATE OR REPLACE FUNCTION public.get_active_subtree_users(
            left_key integer,
            right_key integer,
            user_activity_date timestamp)
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
                ;
        $BODY$;
        ');

        DB::statement('
CREATE OR REPLACE FUNCTION public.is_binary_commission_user(
	user_id bigint,
	user_activity_date timestamp)
    RETURNS boolean
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$SELECT CASE WHEN user_count<1 THEN FALSE ELSE TRUE END
		FROM
			(
				SELECT COUNT(*) as user_count
			 	FROM users u
			 	JOIN (
                    SELECT * 
                    FROM user_activity_history 
                    WHERE created_at = DATE(user_activity_date)
                    ) h
                ON u.id = h.user_id
			 	WHERE 
					u.id=user_id
					AND (h.is_bc_active = true OR h.is_activate = true)
			) as u  
	$BODY$;
        ');

        DB::statement('
        CREATE OR REPLACE FUNCTION public.calculate_binary_commission(from_date timestamp, to_date timestamp, is_recalculate boolean)
    RETURNS void
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$
DECLARE
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
	user_left_carryover numeric := 0;
	user_right_carryover numeric := 0;
	current_bc_commission RECORD;
	has_unpaid_commission boolean := false;
	
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
	SELECT * FROM get_current_processed_binary_commission(from_date, to_date) INTO current_bc_commission;
	
	-- delete unpaid commission
	has_unpaid_commission := has_unpaid_commission(current_bc_commission.end_date);
	
	IF has_unpaid_commission THEN
	    DELETE FROM binary_commission WHERE week_ending = current_bc_commission.end_date;
	END IF;

	OPEN binary_users;       
    LOOP
		FETCH binary_users INTO buser;
		EXIT WHEN NOT FOUND;

        -- get left and right current carryover
		SELECT * FROM get_user_current_carryover(buser.user_id) INTO current_carryover;

		user_left_carryover := COALESCE(current_carryover.left_value, 0);
		user_right_carryover := COALESCE(current_carryover.right_value, 0);

		-- get left and right subtree key borders
		SELECT * FROM get_user_subtree(\'L\', buser.node_id) INTO tbl_left;
		SELECT * FROM get_user_subtree(\'R\', buser.node_id) INTO tbl_right;
			
		-- check subtrees
		SELECT get_active_subtree_users(tbl_left.left_key, tbl_left.right_key, to_date) INTO active_left;
		SELECT get_active_subtree_users(tbl_right.left_key, tbl_right.right_key, to_date) INTO active_right;
			
		-- calculate total left and total right amount
		SELECT get_subtree_total(tbl_left.left_key, tbl_left.right_key, from_date, to_date) INTO total_left;
		SELECT get_subtree_total(tbl_right.left_key, tbl_right.right_key, from_date, to_date) INTO total_right;
	
		t_left_carryover := total_left;
		t_right_carryover := total_right;

		IF is_binary_commission_user(buser.user_id, to_date) THEN
			IF active_left > 0 and active_right > 0 THEN

				total_left:=total_left + user_left_carryover;
				total_right:=total_right + user_right_carryover;

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
					
					IF (is_recalculate IS FALSE) OR (is_recalculate IS TRUE AND has_unpaid_commission) THEN
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
                            user_left_carryover,
                            user_right_carryover,
                            total_left,
                            total_right,
                            gross_volume,
                            percents,
                            payment,
                            to_date
                        );
                    END IF;

					t_left_carryover := total_left - gross_volume - user_left_carryover;
					t_right_carryover := total_right - gross_volume - user_right_carryover;
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
			user_right_carryover + t_right_carryover,
			user_left_carryover + t_left_carryover,
			current_bc_commission.id
		);
   END LOOP;
   
   UPDATE bc_carryover_history
        SET left_carryover = 1000000
        WHERE left_carryover > 1000000;

   UPDATE bc_carryover_history AS ch
        SET right_carryover = 1000000
        WHERE right_carryover > 1000000;
end;$BODY$;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP FUNCTION IF EXISTS get_active_subtree_users(left_key integer, right_key integer, user_activity_date timestamp)');
        DB::statement('DROP FUNCTION IF EXISTS is_binary_commission_user(user_id bigint, user_activity_date timestamp)');

        DB::statement('create or replace function get_active_subtree_users(left_key integer, right_key integer, timestamp) returns bigint
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

        DB::statement('
        CREATE OR REPLACE FUNCTION public.calculate_binary_commission(from_date timestamp, to_date timestamp, is_recalculate boolean)
    RETURNS void
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$
DECLARE
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
	user_left_carryover numeric := 0;
	user_right_carryover numeric := 0;
	current_bc_commission RECORD;
	has_unpaid_commission boolean := false;
	
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
	SELECT * FROM get_current_processed_binary_commission(from_date, to_date) INTO current_bc_commission;
	
	-- delete unpaid commission
	has_unpaid_commission := has_unpaid_commission(current_bc_commission.end_date);
	
	IF has_unpaid_commission THEN
	    DELETE FROM binary_commission WHERE week_ending = current_bc_commission.end_date;
	END IF;

	OPEN binary_users;       
    LOOP
		FETCH binary_users INTO buser;
		EXIT WHEN NOT FOUND;

        -- get left and right current carryover
		SELECT * FROM get_user_current_carryover(buser.user_id) INTO current_carryover;

		user_left_carryover := COALESCE(current_carryover.left_value, 0);
		user_right_carryover := COALESCE(current_carryover.right_value, 0);

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

				total_left:=total_left + user_left_carryover;
				total_right:=total_right + user_right_carryover;

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
					
					IF (is_recalculate IS FALSE) OR (is_recalculate IS TRUE AND has_unpaid_commission) THEN
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
                            user_left_carryover,
                            user_right_carryover,
                            total_left,
                            total_right,
                            gross_volume,
                            percents,
                            payment,
                            to_date
                        );
                    END IF;

					t_left_carryover := total_left - gross_volume - user_left_carryover;
					t_right_carryover := total_right - gross_volume - user_right_carryover;
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
			user_right_carryover + t_right_carryover,
			user_left_carryover + t_left_carryover,
			current_bc_commission.id
		);
   END LOOP;
   
   UPDATE bc_carryover_history
        SET left_carryover = 1000000
        WHERE left_carryover > 1000000;

   UPDATE bc_carryover_history AS ch
        SET right_carryover = 1000000
        WHERE right_carryover > 1000000;
end;$BODY$;
        ');

    }
}
