<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('update addresses set addrtype = 1 from (select userid from addresses where addrtype =\'3\'
                       EXCEPT ALL
                       select userid from addresses where addrtype =\'1\' ) as u where addresses.userid = u.userid');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
