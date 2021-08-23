<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQcToVuserRankSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('drop view vuser_rank_summary');
        DB::statement('create view vuser_rank_summary as
        SELECT h.user_id,
               h.monthly_rank,
               rd.rankdesc  AS monthly_rank_desc,
               h.period,
               h.monthly_qv,
               h.qualified_qv,
               sq.achieved_rank,
               rd1.rankdesc AS achieved_rank_desc,
               h.monthly_tsa,
               h.monthly_qc,
               h.qualified_qc
        FROM (((rank_definition rd
          JOIN user_rank_history h ON ((rd.rankval = h.monthly_rank)))
          JOIN (SELECT rank_history.users_id,
                       max(rank_history.lifetime_rank) AS achieved_rank
                FROM rank_history
                GROUP BY rank_history.users_id) sq ON ((sq.users_id = h.user_id)))
               JOIN rank_definition rd1 ON ((rd1.rankval = sq.achieved_rank)))
        WHERE (h.period =
               (date_trunc(\'month\'::text, ((\'now\'::text)::date)::timestamp with time zone) + \'1 mon -1 days\'::interval));
               ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop view vuser_rank_summary');
        DB::statement('create view vuser_rank_summary as
        SELECT h.user_id,
               h.monthly_rank,
               rd.rankdesc  AS monthly_rank_desc,
               h.period,
               h.monthly_qv,
               h.qualified_qv,
               sq.achieved_rank,
               rd1.rankdesc AS achieved_rank_desc,
               h.monthly_tsa
        FROM (((rank_definition rdp
          JOIN user_rank_history h ON ((rd.rankval = h.monthly_rank)))
          JOIN (SELECT rank_history.users_id,
                       max(rank_history.lifetime_rank) AS achieved_rank
                FROM rank_history
                GROUP BY rank_history.users_id) sq ON ((sq.users_id = h.user_id)))
               JOIN rank_definition rd1 ON ((rd1.rankval = sq.achieved_rank)))
        WHERE (h.period =
               (date_trunc(\'month\'::text, ((\'now\'::text)::date)::timestamp with time zone) + \'1 mon -1 days\'::interval));
        ');
    }
}
