<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCalculateFsbDatewiseApprove extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        create or replace function calculate_fsb_approve() returns void
  language plpgsql
as
$$
DECLARE 
 transac   RECORD;
 first_sponsor RECORD;
 sponsor RECORD;
 commis RECORD;
 pay_levels integer;
 sponsee_count integer;
 counter integer :=1;
 commission_ratio double precision :=0;
 commission_amount double precision :=0;
 last_monday timestamp;
 sp_id varchar(100);
 prod_sku varchar(100);
 memo text;
 processed_week timestamp;
 estimated_balance_for_summary double precision :=0;
 week_sum_rec integer;
 user_ob double precision :=0;
 

 cur_transacs CURSOR
	FOR SELECT * FROM commission_temp_post;
 
 cur_commissions CURSOR
 FOR SELECT user_id, SUM(amount) AS amount
 FROM commission 
 WHERE status=0
 GROUP BY user_id;

 BEGIN

	SELECT date_trunc(\'week\', now())::date  - interval \'1 day\' into processed_week;
   -- Open the cursor cur_transacs
   OPEN cur_transacs;    
   LOOP
		-- fetch row into the transac
	  	FETCH cur_transacs INTO transac;		
		-- exit when no more row to fetch
		EXIT WHEN NOT FOUND;
	  
  		INSERT INTO commission (order_id,transaction_id,transaction_date, amount, user_id, level,status,memo,initiated_user_id,report_type,processed_date)
  		VALUES (transac.order_id,transac.transaction_id,transac.transaction_date, transac.amount, transac.user_id, transac.level,transac.status,transac.memo,transac.initiated_user_id,transac.report_type,transac.processed_date);					
		--update transaction to processed=true to avoid duplicate payment
		UPDATE orders SET processed=TRUE WHERE id=transac.order_id;					
    -- transaction is eligible for FSB commission if the package purchased is above StandBy package only	  
     
   END LOOP;
   -- Close the cursor cur_transacs
   CLOSE cur_transacs;   
   -- Open the cursor cur_commissions for commissions 
   -- which are earned for this week   
   OPEN cur_commissions;
 
   LOOP
      -- fetch row into the commission
      FETCH cur_commissions INTO commis;

      -- exit when no more row to fetch
      EXIT WHEN NOT FOUND;
	  
	   select coalesce(estimated_balance,0) as estimated_balance into user_ob from "users" where id=commis.user_id;
	  
	  
	   -- Insert into ewallet table to log the transaction as Deposit
	  INSERT INTO ewallet_transactions(user_id, opening_balance, closing_balance, amount, type,created_at,csv_generated,commission_type) 
	  VALUES (commis.user_id, user_ob, user_ob+commis.amount, commis.amount, \'DEPOSIT\',processed_week,0,\'FSB\');
	  
	  
	  UPDATE "users" SET estimated_balance = user_ob + commis.amount 
	  WHERE id=commis.user_id;		

		-- insert to get the total per user per commission type.In this type commission type is always 1
		INSERT INTO "week_detail" (week_ending, comm_type, status, total, user_id) 
		VALUES (processed_week, 1, \'PAID\',commis.amount ,commis.user_id);

		-- insert to get the total per user, if no record already available, insert, otherwise update
		select count(*) into week_sum_rec from week_summary where user_id=commis.user_id and week_ending=processed_week;
		if week_sum_rec>0 then
				update "week_summary" set total=total+commis.amount where user_id=commis.user_id and week_ending=processed_week;
		else
				INSERT INTO "week_summary" (week_ending, total, user_id) 
				VALUES (processed_week, commis.amount, commis.user_id);
		end if;		
	  
	  UPDATE commission SET status=1 WHERE user_id=commis.user_id;
	  
   END LOOP;
   CLOSE cur_commissions;  
   --empty the date fields since after approved, dates no longer needed 
   update commission_dates set start_date=null,end_date=null where type=\'post\';
   
   --After approving the commission, data in the post table are no longer needed,
   truncate commission_temp_post;
END;
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
        DB::statement('create or replace function calculate_fsb_approve() returns void
  language plpgsql
as
$$
DECLARE 
 transac   RECORD;
 first_sponsor RECORD;
 sponsor RECORD;
 commis RECORD;
 pay_levels integer;
 sponsee_count integer;
 counter integer :=1;
 commission_ratio double precision :=0;
 commission_amount double precision :=0;
 last_monday timestamp;
 sp_id varchar(100);
 prod_sku varchar(100);
 memo text;
 processed_week timestamp;
 estimated_balance_for_summary double precision :=0;
 week_sum_rec integer;
 user_ob double precision :=0;
 

 cur_transacs CURSOR
	FOR SELECT * FROM commission_temp_post;
 
 cur_commissions CURSOR
 FOR SELECT user_id, SUM(amount) AS amount
 FROM commission 
 WHERE status=0
 GROUP BY user_id;

 BEGIN

	SELECT  now()::date into processed_week;
   -- Open the cursor cur_transacs
   OPEN cur_transacs;    
   LOOP
		-- fetch row into the transac
	  	FETCH cur_transacs INTO transac;		
		-- exit when no more row to fetch
		EXIT WHEN NOT FOUND;
	  
  		INSERT INTO commission (order_id,transaction_id,transaction_date, amount, user_id, level,status,memo,initiated_user_id,report_type,processed_date)
  		VALUES (transac.order_id,transac.transaction_id,transac.transaction_date, transac.amount, transac.user_id, transac.level,transac.status,transac.memo,transac.initiated_user_id,transac.report_type,transac.processed_date);					
		--update transaction to processed=true to avoid duplicate payment
		UPDATE orders SET processed=TRUE WHERE id=transac.order_id;					
    -- transaction is eligible for FSB commission if the package purchased is above StandBy package only	  
     
   END LOOP;
   -- Close the cursor cur_transacs
   CLOSE cur_transacs;   
   -- Open the cursor cur_commissions for commissions 
   -- which are earned for this week   
   OPEN cur_commissions;
 
   LOOP
      -- fetch row into the commission
      FETCH cur_commissions INTO commis;

      -- exit when no more row to fetch
      EXIT WHEN NOT FOUND;
	  
	   select coalesce(estimated_balance,0) as estimated_balance into user_ob from "users" where id=commis.user_id;
	  
	  
	   -- Insert into ewallet table to log the transaction as Deposit
	  INSERT INTO ewallet_transactions(user_id, opening_balance, closing_balance, amount, type,created_at,csv_generated,commission_type) 
	  VALUES (commis.user_id, user_ob, user_ob+commis.amount, commis.amount, \'DEPOSIT\',processed_week,0,\'FSB\');
	  
	  
	  UPDATE "users" SET estimated_balance = user_ob + commis.amount 
	  WHERE id=commis.user_id;		

		-- insert to get the total per user per commission type.In this type commission type is always 1
		INSERT INTO "week_detail" (week_ending, comm_type, status, total, user_id) 
		VALUES (processed_week, 1, \'PAID\',commis.amount ,commis.user_id);

		-- insert to get the total per user, if no record already available, insert, otherwise update
		select count(*) into week_sum_rec from week_summary where user_id=commis.user_id and week_ending=processed_week;
		if week_sum_rec>0 then
				update "week_summary" set total=total+commis.amount where user_id=commis.user_id and week_ending=processed_week;
		else
				INSERT INTO "week_summary" (week_ending, total, user_id) 
				VALUES (processed_week, commis.amount, commis.user_id);
		end if;		
	  
	  UPDATE commission SET status=1 WHERE user_id=commis.user_id;
	  
   END LOOP;
   CLOSE cur_commissions;  
   --empty the date fields since after approved, dates no longer needed 
   update commission_dates set start_date=null,end_date=null where type=\'post\';
   
   --After approving the commission, data in the post table are no longer needed,
   truncate commission_temp_post;
END;
$$;');
    }
}
