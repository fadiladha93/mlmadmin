<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixProductQC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('update products set qc=0 where qc is null ');
        DB::statement('alter table user_statistic alter column current_month_qc type numeric using current_month_qc::numeric;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('update products set qc=null where qc=0 ');
        DB::statement('alter table user_statistic alter column current_month_qc type numeric(8,2) using current_month_qc::numeric(8,2);');
    }
}
