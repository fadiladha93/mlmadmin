<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GetPreviousWeekTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE TYPE week_totals AS (total_volume_left_sum double precision, total_volume_right_sum double precision );
        ');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP  FUNCTION IF EXISTS get_previous_week_total()');
        DB::statement('DROP TYPE IF EXISTS week_totals');
    }
}
