<?php

namespace App;

use App\Logging\BuumLogger;
use App\Services\AchievedRankService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Log;
use utill;

class UserRankHistory extends Model {

    /**
     * {@inheritDoc}
     */
    protected $table = 'user_rank_history';

    public static function getCurrentMonthUserInfo($userId) {
        return DB::table('vuser_rank_summary')
            ->select('monthly_rank_desc', 'achieved_rank_desc', 'monthly_qv', 'monthly_rank', 'monthly_tsa','monthly_qc', 'qualified_qv')
            ->where('user_id', $userId)
            ->first();
    }

    public static function getRankMatrics($distId, $currentMonthRank) {
        $rec = DB::select("SELECT * FROM get_rank_metrice('$distId',$currentMonthRank)");
        if (count($rec) > 0)
            return $rec[0];
        else
            return null;
    }

    /**
     * @param $distId
     * @param $currentMonthRank
     * @return mixed
     */
    public static function getTopContributors($distId, $currentMonthRank)
    {
        return DB::select(
            sprintf(
                "SELECT * FROM(SELECT * FROM get_personal_sponsor_qv('$distId',$currentMonthRank))x LIMIT %d",
                AchievedRankService::TOP_LEG_COUNT
            )
        );
    }

    /**
     * @param $distId
     * @param $currentMonthRank
     * @return int
     */
    public static function getQV($distId, $currentMonthRank)
    {
        $qv = 0;
        $rec = DB::select(
            "select sum(qv_contribution) as qv_contribution from get_personal_sponsor_qv('$distId', $currentMonthRank)"
        );
        if (count($rec) > 0) {
            $qv += $rec[0]->qv_contribution;
        }

        $pqv = DB::select(sprintf(
            '
            SELECT COALESCE(SUM(o.orderqv), 0) as pqv
            FROM users u
            JOIN orders o
            ON u.id = o.userid
            WHERE o.created_dt >= \'%s\'
                AND o.created_dt <= \'%s\'
                AND (o.statuscode = 1 OR o.statuscode = 6)
                AND u.distid = \'%s\';
            ',
            Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
            Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
            $distId
        ));

        if ($pqv) {
            $qv += $pqv[0]->pqv;
        }

        return $qv;
    }

    public static function getTSA($distId, $currentMonthRank) {
        $rec = DB::select("select sum(tsa_contribution) as tsa_contribution from get_qualifying_qv_tsa('$distId',$currentMonthRank)");
        if (count($rec) > 0)
            return number_format($rec[0]->tsa_contribution);
        else
            return 0;
    }

    public static function getCurrentMonthlyRec($userId) {
        return DB::table('vmonthly_rank_for_widget')
                        ->where('user_id', $userId)
                        ->first();
    }

    public static function getPreviousMonthlyRec($userId) {
        return DB::table('vmonthly_rank_for_widget_prev')
                        ->where('user_id', $userId)
                        ->first();
    }

    // as cron job
    public static function calculateDownlineQV(Carbon $fromDate, Carbon $toDate) {
        set_time_limit(0);

        Log::info('Running calculateDownlineQv() on DB', [get_called_class(), 'fromDate' => $fromDate, 'toDate' => $toDate]);
        (new BuumLogger('Running calculateDownlineQv() on DB', BuumLogger::LOG_TYPE_INFO))->toSlack();
        DB::selectFromWriteConnection(sprintf("select * from calculate_downline_qv_with_tsa('%s', '%s')", $fromDate, $toDate));
        Log::info('Done running calculateDownlineQv() on DB', [get_called_class()]);
        (new BuumLogger('Done running calculateDownlineQv() on DB', BuumLogger::LOG_TYPE_INFO))->toSlack();
    }

    /**
     * @param $user
     * @param $period
     * @return mixed
     */
    public static function getRankInMonth($user, $period)
    {
        return DB::table('user_rank_history')
            ->where('user_id', $user->id)
            ->whereDate('period', $period)
            ->first();
    }
}
