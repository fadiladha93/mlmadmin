<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class UpdateRankDefinition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rank_definition', function (Blueprint $table) {
            $table->float('qc_percent')
            ->default(0);
        });

        DB::statement('UPDATE rank_definition SET qc_percent = 0.4, min_binary_count = 2 WHERE rankval = 50');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.4, min_binary_count = 3 WHERE rankval = 60');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.4, min_binary_count = 4 WHERE rankval = 70');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.3, min_binary_count = 5 WHERE rankval = 80');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.3, min_binary_count = 5 WHERE rankval = 90');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.3, min_binary_count = 5 WHERE rankval = 100');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.3, min_binary_count = 5 WHERE rankval = 110');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.2, min_binary_count = 5 WHERE rankval = 120');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.2, min_binary_count = 5 WHERE rankval = 130');
        DB::statement('UPDATE rank_definition SET qc_percent = 0.2, min_binary_count = 5 WHERE rankval = 140');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rank_definition', function (Blueprint $table) {
            $table->dropColumn('qc_percent');
        });
    }
}
