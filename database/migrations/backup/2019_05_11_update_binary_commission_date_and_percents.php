<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BinaryCommissionDateAndPercents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            DROP FUNCTION IF EXISTS apply_limitations_by_rank(integer, numeric, character varying);
        ');

        DB::statement('
            DROP FUNCTION IF EXISTS get_binary_commission_percent(integer, character varying);
        ');

        DB::statement('
CREATE OR REPLACE FUNCTION public.get_binary_commission_percent(
	rank_value integer,
	pack_id integer,
	user_id integer)
    RETURNS numeric
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE
	pack_percent numeric := 0;
	rank_percent numeric := 0;
	is_expired boolean := false;
	expiration_date timestamp;
begin
	SELECT oi.created_date INTO expiration_date
	FROM orders o
	LEFT JOIN "orderItem" oi ON o.id = oi.orderid
	WHERE userid = user_id ORDER BY oi.created_date
	LIMIT 1;

	IF rank_value = 10 THEN 
		rank_percent := 0;
	ELSIF rank_value = 20 THEN
		rank_percent := 0.12;
	ELSIF rank_value = 30 THEN
		rank_percent := 0.12;
	ELSIF rank_value = 40 THEN
		rank_percent := 0.12;
	ELSIF rank_value = 50 THEN
		rank_percent := 0.15;
	ELSIF rank_value = 60 THEN
		rank_percent := 0.16;
	ELSIF rank_value = 70 THEN
		rank_percent := 0.17;
	ELSIF rank_value = 80 THEN
		rank_percent := 0.18;
	ELSIF rank_value = 90 THEN
		rank_percent := 0.19;
	ELSIF rank_value = 100 THEN
		rank_percent := 0.2;
	ELSIF rank_value = 110 THEN
		rank_percent := 0.2;
	ELSIF rank_value = 120 THEN
		rank_percent := 0.2;
	ELSIF rank_value = 130 THEN
		rank_percent := 0.2;
	ELSIF rank_value = 140 THEN
		rank_percent := 0.2;
	END IF;
	
	IF pack_id = 14 THEN 
		pack_percent := 0.12;
	ELSIF pack_id = 12 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 1 THEN
		pack_percent := 0;
	ELSIF pack_id = 13 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 16 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 17 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 19 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 18 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 15 THEN
		pack_percent := 0;
	ELSIF pack_id = 11 THEN
		pack_percent := 0;
	ELSIF pack_id = 26 THEN
		pack_percent := 0;
	ELSIF pack_id = 8 THEN
		pack_percent := 0.15;
	ELSIF pack_id = 25 THEN
		pack_percent := 0;
	ELSIF pack_id = 9 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 20 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 10 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 2 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 3 THEN
		pack_percent := 0.15;
	ELSIF pack_id = 24 THEN
		pack_percent := 0;
	ELSIF pack_id = 23 THEN
		pack_percent := 0;
	ELSIF pack_id = 4 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	ELSIF pack_id = 21 THEN
		pack_percent := 0;
	ELSIF pack_id = 5 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 6 THEN
		pack_percent := 0.15;
	ELSIF pack_id = 7 THEN
		IF (NOW() - interval \'1 year\') < expiration_date THEN
			pack_percent := 0.2;
		ELSE
			is_expired := true;
			pack_percent := 0;
		END IF;
	END IF;
	
	-- after 12 months
	IF pack_percent = 0 and rank_percent = 0 and is_expired = true THEN
		pack_percent := 0.2;
	END IF;
	
	IF pack_percent > rank_percent THEN 
		RETURN pack_percent;
	ELSE 
		RETURN rank_percent;
	END IF;
	end;
$BODY$;
        ');

        DB::statement('
CREATE OR REPLACE FUNCTION public.apply_limitations_by_rank(
	rank_value integer,
	current_payment numeric,
	product_id integer)
    RETURNS numeric
    LANGUAGE \'sql\'

    COST 100
    VOLATILE 
AS $BODY$
	SELECT CASE 
		WHEN rank_value = 10 and current_payment > 2500 and product_id = 14 THEN 2500
		WHEN rank_value = 10 and current_payment > 2500 and product_id = 12 THEN 2500
		WHEN rank_value = 10 and current_payment > 0 and product_id = 1 THEN 0
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 13 THEN 10000
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 16 THEN 10000
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 17 THEN 10000
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 19 THEN 10000
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 18 THEN 10000
		WHEN rank_value = 10 and current_payment > 0 and product_id = 15 THEN 0
		WHEN rank_value = 10 and current_payment > 0 and product_id = 11 THEN 0
		WHEN rank_value = 10 and current_payment > 0 and product_id = 26 THEN 0
		WHEN rank_value = 10 and current_payment > 3000 and product_id = 8 THEN 3000
		WHEN rank_value = 10 and current_payment > 0 and product_id = 25 THEN 0
		WHEN rank_value = 10 and current_payment > 2500 and product_id = 9 THEN 2500
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 20 THEN 10000
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 10 THEN 10000
		WHEN rank_value = 10 and current_payment > 2500 and product_id = 2 THEN 2500
		WHEN rank_value = 10 and current_payment > 3000 and product_id = 3 THEN 3000
		WHEN rank_value = 10 and current_payment > 0 and product_id = 24 THEN 0
		WHEN rank_value = 10 and current_payment > 0 and product_id = 23 THEN 0
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 4 THEN 10000
		WHEN rank_value = 10 and current_payment > 0 and product_id = 21 THEN 0
		WHEN rank_value = 10 and current_payment > 2500 and product_id = 5 THEN 2500
		WHEN rank_value = 10 and current_payment > 3000 and product_id = 6 THEN 3000
		WHEN rank_value = 10 and current_payment > 10000 and product_id = 7 THEN 10000
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
			p.id as rank_id
		FROM binary_plan bp
		JOIN users u
		ON bp.user_id = u.id
		JOIN products p
		ON u.current_product_id = p.id;
begin
	from_date := (NOW() - interval \'6 days\')::date; -- Monday current week
	to_date := NOW()::date; -- current Sunday (today)

	SELECT count(*) into commission_count FROM binary_commission
	WHERE week_ending = (NOW() + interval \'5 days\')::date;
	
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
            DROP FUNCTION IF EXISTS apply_limitations_by_rank(integer, numeric, integer);
        ');

        DB::statement('
            DROP FUNCTION IF EXISTS get_binary_commission_percent(integer, integer, integer);
        ');

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
}
