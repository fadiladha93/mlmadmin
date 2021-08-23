<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRankPercentForBinaryCommission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
	result_percent numeric := 0;
	is_expired boolean := false;
	expiration_date timestamp;
	purchase_date timestamp;
	is_founder boolean := false;
begin
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
		pack_percent := 0.2;
	ELSIF pack_id = 16 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 17 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 19 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 18 THEN
		pack_percent := 0.2;
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
		pack_percent := 0.2;
	ELSIF pack_id = 10 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 2 THEN
		pack_percent := 0.1;
	ELSIF pack_id = 3 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 24 THEN
		pack_percent := 0;
	ELSIF pack_id = 23 THEN
		pack_percent := 0;
	ELSIF pack_id = 4 THEN
		pack_percent := 0.15;
	ELSIF pack_id = 21 THEN
		pack_percent := 0;
	ELSIF pack_id = 5 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 6 THEN
		pack_percent := 0.15;
	ELSIF pack_id = 7 THEN
		pack_percent := 0.2;
	END IF;
	
	SELECT oi.created_dt INTO purchase_date
	FROM orders o
	LEFT JOIN "orderItem" oi ON o.id = oi.orderid
	WHERE userid = user_id AND oi.productid = 4
	ORDER BY oi.created_dt DESC
	LIMIT 1;
	    
	-- if user bought First Class pack before 01 August 23:59:59
	SELECT founder INTO is_founder
	FROM users
	WHERE id = user_id
	LIMIT 1;
	    
	IF is_founder AND NOW() < (purchase_date + interval \'1 year\') THEN
	    pack_percent := 0.2;
	END IF;
	
	IF pack_percent > rank_percent THEN 
		result_percent := pack_percent;
	ELSE 
		result_percent := rank_percent;
	END IF;

    -- forced ranks A1637504,A1357703,TSA9834283,TSA0707550,TSA5138270
	IF user_id = 6956 THEN 
    --	rank_id = 25    
		result_percent := 0.2;
	ELSIF user_id = 17073 then
	 --	rank_id = 25   
		result_percent := 0.2;
	ELSIF user_id = 17459 then
	 --	rank_id = 27   
		result_percent := 0.2;
	ELSIF user_id = 9279 then
	 --	rank_id = 27   
		result_percent := 0.2;
	ELSIF user_id = 16399 then
	 --	rank_id = 27   
		result_percent := 0.2;
	END IF;

	RETURN result_percent;
	end;
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
	result_percent numeric := 0;
	is_expired boolean := false;
	expiration_date timestamp;
	purchase_date timestamp;
	is_founder boolean := false;
begin
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
		pack_percent := 0.2;
	ELSIF pack_id = 16 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 17 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 19 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 18 THEN
		pack_percent := 0.2;
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
		pack_percent := 0.2;
	ELSIF pack_id = 10 THEN
		pack_percent := 0.2;
	ELSIF pack_id = 2 THEN
		pack_percent := 0.1;
	ELSIF pack_id = 3 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 24 THEN
		pack_percent := 0;
	ELSIF pack_id = 23 THEN
		pack_percent := 0;
	ELSIF pack_id = 4 THEN
		pack_percent := 0.15;
		
		SELECT oi.created_dt INTO purchase_date
	    FROM orders o
	    LEFT JOIN "orderItem" oi ON o.id = oi.orderid
	    WHERE userid = user_id AND oi.productid = 4
		ORDER BY oi.created_dt DESC
	    LIMIT 1;
	    
	    -- if user bought First Class pack before 01 August 23:59:59
	    SELECT founder INTO is_founder
	    FROM users
	    WHERE id = user_id
	    LIMIT 1;
	    
	    IF is_founder AND NOW() < (purchase_date + interval \'1 year\') THEN
	        pack_percent := 0.2;
	    END IF;
	    
	ELSIF pack_id = 21 THEN
		pack_percent := 0;
	ELSIF pack_id = 5 THEN
		pack_percent := 0.12;
	ELSIF pack_id = 6 THEN
		pack_percent := 0.15;
	ELSIF pack_id = 7 THEN
		pack_percent := 0.2;
	END IF;
	
	IF pack_percent > rank_percent THEN 
		result_percent := pack_percent;
	ELSE 
		result_percent := rank_percent;
	END IF;

    -- forced ranks A1637504,A1357703,TSA9834283,TSA0707550,TSA5138270
	IF user_id = 6956 THEN 
    --	rank_id = 25    
		result_percent := 0.2;
	ELSIF user_id = 17073 then
	 --	rank_id = 25   
		result_percent := 0.2;
	ELSIF user_id = 17459 then
	 --	rank_id = 27   
		result_percent := 0.2;
	ELSIF user_id = 9279 then
	 --	rank_id = 27   
		result_percent := 0.2;
	ELSIF user_id = 16399 then
	 --	rank_id = 27   
		result_percent := 0.2;
	END IF;

	RETURN result_percent;
	end;
$BODY$;
        ');
    }
}
