<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNewRankDefinitionValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rank_definition', function (Blueprint $table) {
            $table->integer('min_qc')
                ->default(0);
        });

        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Ambassador')
            ->update([
                'rank_limit' => 0.5,
                'min_qc' => 0,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Director')
            ->update([
                'min_qc' => 0,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Senior Director')
            ->update([
                'min_qc' => 0,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Executive Director')
            ->update([
                'min_qc' => 0,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Sapphire')
            ->update([
                'min_qc' => 100,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Ruby')
            ->update([
                'min_qc' => 250,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Emerald')
            ->update([
                'min_qc' => 500,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Diamond')
            ->update([
                'min_qc' => 1200,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Blue Diamond')
            ->update([
                'min_qc' => 3000,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Black Diamond')
            ->update([
                'min_qc' => 6000,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Presidential Diamond')
            ->update([
                'min_qc' => 12000,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Crown Diamond')
            ->update([
                'min_qc' => 30000,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Double Crown Diamond')
            ->update([
                'min_qc' => 42000,
            ]);
        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Triple Crown Diamond')
            ->update([
                'min_qc' => 60000,
            ]);

        DB::statement('create type qc_rank as
            (
            rankval integer,
            rankdesc varchar(100),
            rank_qc bigint
            );');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rank_definition', function (Blueprint $table) {
            $table->dropColumn('min_qc');
            $table->dropColumn('max_qc');
        });

        DB::table('rank_definition')
            ->where('rankdesc', '=', 'Ambassador')
            ->update(['rank_limit' => 0]);
        
        DB::statement('DROP TYPE qc_rank');
    }
}
