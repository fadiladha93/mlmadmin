<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIsBinaryCommissionUserFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
					AND (is_active = 1 OR is_activate = 1)
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
}
