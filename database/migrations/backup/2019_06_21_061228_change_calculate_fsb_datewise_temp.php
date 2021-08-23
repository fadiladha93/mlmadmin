<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCalculateFsbDatewiseTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('create or replace function calculate_fsb_datewise_temp(fromdate date, todate date) returns void
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
 orderline_count integer :=0;
 orderbv double precision :=0;
 
 cur_transacs CURSOR (d1 date,d2 date)
	FOR SELECT * FROM vorder_orderitem  as vo
	  join products as p on p.id = vo.productid
	  where p.producttype in (1,2) and date(created_dt) between d1 and d2;

 BEGIN

   TRUNCATE table "commission_temp";
   SELECT NOW()::DATE into processed_week;
   -- Open the cursor cur_transacs
   OPEN cur_transacs(fromDate,toDate);    
   LOOP
    -- fetch row into the transac
      FETCH cur_transacs INTO transac;		

-- exit when no more row to fetch
      EXIT WHEN NOT FOUND;	

	  select count(*) into orderline_count from "orderItem" where orderid=transac.orderid; 

    -- transaction is eligible for FSB commission if the package purchased is above StandBy package only	  
      IF transac.productid != 1 THEN   	
		--a counter representing the pay level at which commission being calculated
		counter:=1;				
		--go up the sponsor tree and pay each sponsor who is eligible for a commission 
		--represented by pay_levels

		--pick the sponsor id of the user
		SELECT sponsorid into sp_id from public."users" where id=transac.userid;
		  	
		LOOP 
			-- get the sponsor of the buyer, sponsor\'s sponsor and so on	

			SELECT * FROM users INTO sponsor WHERE distid=sp_id;
			-- exit the loop if there no longer a sponsor of the buyer or the sponsor
			EXIT WHEN NOT FOUND or sponsor.id is null;	

			IF sponsor.current_product_id=1 THEN
				pay_levels:=1;					
			ELSE
				-- find the count of immediate downline of the sponsor

				SELECT INTO sponsee_count get_noof_sponsees(sponsor.distid);

				SELECT INTO pay_levels get_paylevels(sponsee_count);
				
			END IF;
			IF pay_levels>=counter Then
				--find the commission ratio a sponsor is eligible based on his position
				--up on the sponsorship tree using the commission percentage settings in the commission_settings table 
				SELECT INTO commission_ratio get_commission_ratio(counter);
				--calculate the commission using the commission ratio * commissionable volume of the package purchased

-- 				if orderline_count>1 then
-- 					commission_amount := commission_ratio * (transac.orderbv-49.95*0.80);	
-- 				else
-- 					commission_amount := commission_ratio * transac.orderbv;	
-- 				end if;

				commission_amount := commission_ratio * transac.item_bv;	
				--Get the product sku from product table to append with memo
				SELECT sku into prod_sku from public."products" where id=transac.productid;
				--40% retailer bonus(Level3)for product (SKU:xxxx) purchased with order xxxxx
				memo:=(commission_ratio *100)||\'% retailer bonus (Level \'|| counter||\' ) for product (SKU: \'||prod_sku||\' ) purchased with order \'||transac.trasnactionid;

-- 				orderbv = transac.orderbv;
-- 				if orderline_count>1 then
-- 					orderbv := orderbv - 49.95*0.80;
-- 				end if;

-- 				if orderbv!=transac.bv THEN
-- 					memo:=memo||\'#IQCredit\';
-- 				end if;

				INSERT INTO commission_temp (order_id,transaction_id,transaction_date, amount, user_id, level,status,memo,initiated_user_id,report_type,processed_date)
				VALUES (transac.orderid,transac.item_id,transac.created_dt, commission_amount, sponsor.id, counter,0,memo,transac.userid,1,processed_week);

      END IF;
			
			sp_id:=sponsor.sponsorid;
			--add one to the level to which we are going to pay commission next
			counter := counter + 1 ; 
			--exit the loop if all the sponsors upto the levels denoted by pay_levels were paid out their commission
			--or there is no more sponsors up in the sponsorship tree
			--EXIT WHEN counter > pay_levels; 
			EXIT WHEN counter=7; 
				
		END LOOP ; 					
		 	 
    ELSE
	  	--As it is a StandBy package the processed status of the transaction is set to true
		--without considering this transaction in calculating commission. If it is not updated
		--the transaction will live ever in the transaction table		
		IF orderline_count<=1 Then
			UPDATE orders SET processed=TRUE WHERE id=transac.orderid;
		End If;
	END IF;	  
   END LOOP;
   -- Close the cursor cur_transacs
   CLOSE cur_transacs;         
END;
$$;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
        create or replace function calculate_fsb_datewise_temp(fromdate date, todate date) returns void
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
 orderline_count integer :=0;
 orderbv double precision :=0;
 
 cur_transacs CURSOR (d1 date,d2 date)
	FOR SELECT * FROM vorder_orderitem  where date(created_dt) between d1 and d2;

 BEGIN

   TRUNCATE table "commission_temp";
   SELECT NOW()::DATE into processed_week;
   -- Open the cursor cur_transacs
   OPEN cur_transacs(fromDate,toDate);    
   LOOP
    -- fetch row into the transac
      FETCH cur_transacs INTO transac;		

-- exit when no more row to fetch
      EXIT WHEN NOT FOUND;	

	  select count(*) into orderline_count from "orderItem" where orderid=transac.orderid; 

    -- transaction is eligible for FSB commission if the package purchased is above StandBy package only	  
      IF transac.productid != 1 THEN   	
		--a counter representing the pay level at which commission being calculated
		counter:=1;				
		--go up the sponsor tree and pay each sponsor who is eligible for a commission 
		--represented by pay_levels

		--pick the sponsor id of the user
		SELECT sponsorid into sp_id from public."users" where id=transac.userid;
		  	
		LOOP 
			-- get the sponsor of the buyer, sponsor\'s sponsor and so on	

			SELECT * FROM users INTO sponsor WHERE distid=sp_id;
			-- exit the loop if there no longer a sponsor of the buyer or the sponsor
			EXIT WHEN NOT FOUND or sponsor.id is null;	

			IF sponsor.current_product_id=1 THEN
				pay_levels:=1;					
			ELSE
				-- find the count of immediate downline of the sponsor

				SELECT INTO sponsee_count get_noof_sponsees(sponsor.distid);

				SELECT INTO pay_levels get_paylevels(sponsee_count);
				
			END IF;
			IF pay_levels>=counter Then
				--find the commission ratio a sponsor is eligible based on his position
				--up on the sponsorship tree using the commission percentage settings in the commission_settings table 
				SELECT INTO commission_ratio get_commission_ratio(counter);
				--calculate the commission using the commission ratio * commissionable volume of the package purchased

-- 				if orderline_count>1 then
-- 					commission_amount := commission_ratio * (transac.orderbv-49.95*0.80);	
-- 				else
-- 					commission_amount := commission_ratio * transac.orderbv;	
-- 				end if;

				commission_amount := commission_ratio * transac.item_bv;	
				--Get the product sku from product table to append with memo
				SELECT sku into prod_sku from public."products" where id=transac.productid;
				--40% retailer bonus(Level3)for product (SKU:xxxx) purchased with order xxxxx
				memo:=(commission_ratio *100)||\'% retailer bonus (Level \'|| counter||\' ) for product (SKU: \'||prod_sku||\' ) purchased with order \'||transac.trasnactionid;

-- 				orderbv = transac.orderbv;
-- 				if orderline_count>1 then
-- 					orderbv := orderbv - 49.95*0.80;
-- 				end if;

-- 				if orderbv!=transac.bv THEN
-- 					memo:=memo||\'#IQCredit\';
-- 				end if;

				INSERT INTO commission_temp (order_id,transaction_id,transaction_date, amount, user_id, level,status,memo,initiated_user_id,report_type,processed_date)
				VALUES (transac.orderid,transac.item_id,transac.created_dt, commission_amount, sponsor.id, counter,0,memo,transac.userid,1,processed_week);

      END IF;
			
			sp_id:=sponsor.sponsorid;
			--add one to the level to which we are going to pay commission next
			counter := counter + 1 ; 
			--exit the loop if all the sponsors upto the levels denoted by pay_levels were paid out their commission
			--or there is no more sponsors up in the sponsorship tree
			--EXIT WHEN counter > pay_levels; 
			EXIT WHEN counter=7; 
				
		END LOOP ; 					
		 	 
    ELSE
	  	--As it is a StandBy package the processed status of the transaction is set to true
		--without considering this transaction in calculating commission. If it is not updated
		--the transaction will live ever in the transaction table		
		IF orderline_count<=1 Then
			UPDATE orders SET processed=TRUE WHERE id=transac.orderid;
		End If;
	END IF;	  
   END LOOP;
   -- Close the cursor cur_transacs
   CLOSE cur_transacs;         
END;
$$;
        ');
    }
}
