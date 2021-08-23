<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPayoutBinaryCommissin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP FUNCTION IF EXISTS pay_binary_commission()');

        DB::statement('
CREATE OR REPLACE FUNCTION public.pay_binary_commission(to_date timestamp)
    RETURNS void
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE
	pay_datetime timestamp;
	bcommission RECORD;
	
	binary_commissions CURSOR
		FOR SELECT 
			bc.id as commission_id,
			bc.user_id,
			bc.amount_earned,
			coalesce(u.estimated_balance,0) as estimated_balance
		FROM binary_commission bc
		JOIN users u
		ON bc.user_id = u.id
		WHERE week_ending = to_date AND status = \'posted\';
begin
	pay_datetime := NOW()::date;

	OPEN binary_commissions;       
    LOOP
		FETCH binary_commissions INTO bcommission;
		EXIT WHEN NOT FOUND;	
		
		INSERT INTO ewallet_transactions(
			user_id,
			opening_balance,
			closing_balance,
			amount,
			type,
			created_at,
			csv_generated,
			commission_type
		) 
	  VALUES (
		  bcommission.user_id,
		  bcommission.estimated_balance,
		  bcommission.estimated_balance + bcommission.amount_earned,
		  bcommission.amount_earned,
		  \'DEPOSIT\',
		  pay_datetime,
		  0,
		  \'BC\'
	  );
	  
	  UPDATE "users" SET estimated_balance = bcommission.estimated_balance + bcommission.amount_earned
	  WHERE id = bcommission.user_id;	
	  
	  UPDATE binary_commission SET status = \'paid\'
	  WHERE id = bcommission.commission_id;
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
        DB::statement('DROP FUNCTION IF EXISTS pay_binary_commission(to_date timestamp)');

        DB::statement('
CREATE OR REPLACE FUNCTION public.pay_binary_commission(
	)
    RETURNS void
    LANGUAGE \'plpgsql\'

    COST 100
    VOLATILE 
AS $BODY$DECLARE
	pay_datetime timestamp;
	bcommission RECORD;
	
	binary_commissions CURSOR
		FOR SELECT 
			bc.id as commission_id,
			bc.user_id,
			bc.amount_earned,
			coalesce(u.estimated_balance,0) as estimated_balance
		FROM binary_commission bc
		JOIN users u
		ON bc.user_id = u.id
		WHERE is_processed = false;
begin
	pay_datetime := NOW()::date;

	OPEN binary_commissions;       
    LOOP
		FETCH binary_commissions INTO bcommission;
		EXIT WHEN NOT FOUND;	
		
		INSERT INTO ewallet_transactions(
			user_id,
			opening_balance,
			closing_balance,
			amount,
			type,
			created_at,
			csv_generated,
			commission_type
		) 
	  VALUES (
		  bcommission.user_id,
		  bcommission.estimated_balance,
		  bcommission.estimated_balance + bcommission.amount_earned,
		  bcommission.amount_earned,
		  \'DEPOSIT\',
		  pay_datetime,
		  0,
		  \'BC\'
	  );
	  
	  UPDATE "users" SET estimated_balance = bcommission.estimated_balance + bcommission.amount_earned
	  WHERE id = bcommission.user_id;	
	  
	  UPDATE binary_commission SET status = \'paid\'
	  WHERE id = bcommission.commission_id;
   END LOOP;
end;$BODY$;
        ');
    }
}
