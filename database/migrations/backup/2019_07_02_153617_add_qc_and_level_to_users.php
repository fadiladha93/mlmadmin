<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQcAndLevelToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_statistic', function (Blueprint $table) {
            $table->text('current_month_qc')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('level')
                ->nullable();
        });


        DB::statement('
WITH RECURSIVE children AS (
 SELECT distid, sponsorid, id, 1 as depth
 FROM users
 WHERE distid = \'TSA5138270\'
UNION
 SELECT op.distid, op.sponsorid, op.id, depth + 1
 FROM users op
 JOIN children c ON op.sponsorid = c.distid
)

UPDATE users
            SET level = levels.depth
			FROM (
				SELECT * FROM children
            ) as levels
WHERE users.distid=levels.distid			
			;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_statistic', function (Blueprint $table) {
            $table->float('current_month_qc')->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
}
