<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersSelection extends Migration
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
					WHERE rh.users_id = 16399
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
CREATE OR REPLACE FUNCTION public.get_subtree_total(
	left_key integer,
	right_key integer,
	from_date timestamp without time zone,
	to_date timestamp without time zone)
    RETURNS bigint
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$SELECT COALESCE(SUM(oi.bv), 0)
		FROM binary_plan bp
		JOIN orders o
		ON bp.user_id = o.userid
		JOIN "orderItem" oi
		ON o.id = oi.orderid
		JOIN products p
		ON p.id = oi.productid
		WHERE (o.created_dt between from_date and to_date)
			AND (o.statuscode = 1 OR o.statuscode = 6)
			AND _lft >= left_key AND _rgt <= right_key
			AND (p.producttype = 1 OR p.producttype = 2)
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
	is_active boolean := false;
	
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
					SELECT get_binary_commission_percent(buser.current_month_rank, buser.rank_id, buser.user_id) INTO percents;
					
					payment:= percents * gross_volume;
					SELECT apply_limitations_by_rank(buser.current_month_rank, payment, buser.rank_id) INTO payment;

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

					-- update current carryover values
					UPDATE users
						SET current_left_carryover = total_left - gross_volume,
						current_right_carryover = total_right - gross_volume
						WHERE id = buser.user_id;
					is_active := true;
				END IF;
			END IF;
		END IF;
		
		IF is_active = false THEN
			SELECT * FROM get_user_subtree(\'L\', buser.node_id) INTO tbl_left;
			SELECT * FROM get_user_subtree(\'R\', buser.node_id) INTO tbl_right;
			
			SELECT get_subtree_total(tbl_left.left_key, tbl_left.right_key, from_date, to_date) INTO total_left;
			SELECT get_subtree_total(tbl_right.left_key, tbl_right.right_key, from_date, to_date) INTO total_right;
			UPDATE users
				SET current_left_carryover = current_left_carryover + total_left,
					current_right_carryover = current_right_carryover + total_right
				WHERE id = buser.user_id;
		END IF;
   END LOOP;
end;$BODY$;
        ');

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
		WHERE (o.created_dt between from_date and to_date)
			AND (o.statuscode = 1 OR o.statuscode = 6)
			AND _lft >= left_key AND _rgt <= right_key;
$BODY$;
        ');
    }
}
