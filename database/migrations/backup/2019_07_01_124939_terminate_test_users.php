<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TerminateTestUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA0515101'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA8515150'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA0615168'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA5621259'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA7621279'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA2221306'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA7721409'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA1524159'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA9526500'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA6907442'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA9595971'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA7351189'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA5629542'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA0629722'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA4029729'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA8929758'");
        DB::statement("UPDATE users set account_status = 'TERMINATED' where distid ='TSA5129899'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA0515101'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA8515150'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA0615168'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA5621259'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA7621279'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA2221306'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA7721409'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA1524159'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA9526500'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA6907442'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA9595971'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA7351189'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA5629542'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA0629722'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA4029729'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA8929758'");
        DB::statement("UPDATE users set account_status = 'APPROVED' where distid ='TSA5129899'");
    }
}
