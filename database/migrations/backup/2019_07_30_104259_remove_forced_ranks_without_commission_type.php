<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class RemoveForcedRanksWithoutCommissionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE force_rank SET commission_type=\'UC\' WHERE commission_type is NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('UPDATE force_rank SET commission_type=NULL WHERE user_distid in (\'A1637504\',\'A1357703\',\'TSA9834283\',\'TSA0707550\',\'TSA5138270\')');
    }
}
