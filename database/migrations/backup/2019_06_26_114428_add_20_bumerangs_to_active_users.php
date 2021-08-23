<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add20BumerangsToActiveUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('update boomerang_inv as bi set available_tot = bi.available_tot + 20 from
        (select users.id from users
           join boomerang_inv as bi on bi.userid = users.id
           where is_active = 1 ) as au
        where bi.userid = au.id;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('update boomerang_inv as bi set available_tot = bi.available_tot - 20 from
        (select users.id from users
           join boomerang_inv as bi on bi.userid = users.id
           where is_active = 1 ) as au
        where bi.userid = au.id;');
    }
}
