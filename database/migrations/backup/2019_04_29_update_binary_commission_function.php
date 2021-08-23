<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BinaryCommissionFunction extends Migration
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
						NOW()::date
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
}
