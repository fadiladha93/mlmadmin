<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeGetPreviousTotalVolume extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement('

CREATE OR REPLACE FUNCTION get_previous_week_total(userId integer)
  RETURNS SETOF week_totals
    LANGUAGE \'plpgsql\'
    
    COST 100
    VOLATILE 
AS $BODY$
BEGIN
    RETURN QUERY
	SELECT COALESCE(sum(total_volume_left), 0), COALESCE(sum(total_volume_right), 0)
    FROM binary_commission as bc where  bc.user_id = userId group by bc.week_ending order by bc.week_ending  DESC LIMIT 1;
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

CREATE FUNCTION get_previous_week_total()
  RETURNS SETOF week_totals
    LANGUAGE \'plpgsql\'
    
    COST 100
    VOLATILE 
AS $BODY$
BEGIN
    RETURN QUERY
	SELECT COALESCE(sum(total_volume_left), 0), COALESCE(sum(total_volume_right), 0)
    FROM binary_commission where  date_trunc(\'week\' , now()) - \'2 week\'::interval < week_ending;
END;
$BODY$;
        ');
    }
}
