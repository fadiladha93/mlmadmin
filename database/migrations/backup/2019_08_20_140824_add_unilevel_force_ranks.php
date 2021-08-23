<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnilevelForceRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
INSERT INTO public.force_rank(user_distid, rank_id, commission_type)
VALUES (\'A1539418\', 21, \'UC\')
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
        DELETE FROM force_rank WHERE user_distid = \'A1539418\'
        ');
    }
}
