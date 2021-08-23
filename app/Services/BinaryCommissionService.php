<?php

namespace App\Services;

use App\BinaryCommissionHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\BinaryCommission;
use Illuminate\Support\Facades\Log;

/**
 * Class BinaryCommissionService
 * @package App\Services
 */
class BinaryCommissionService
{
    const COMMISSION_TYPE = 'BC';

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param $isRecalculate
     */
    public function calculateCommission(Carbon $startDate, Carbon $endDate, bool $isRecalculate)
    {
        Log::info('Calling binary commissions command on DB', [get_called_class(),
                                                                'startDate' => $startDate,
                                                                'endDate' => $endDate,
                                                                'isRecalculate' => (int)$isRecalculate]); // int because bool might not log right if false
        DB::statement(
            sprintf("SELECT public.calculate_binary_commission('%s', '%s', '%s')",
                $startDate,
                $endDate,
                (int) $isRecalculate
            )
        );

        Log::info('Binary commissions done.', [get_called_class()]);
    }

    /**
     * @param Carbon $endDate
     */
    public function payCommission(Carbon $endDate)
    {
        DB::statement(
            sprintf("SELECT public.pay_binary_commission('%s')", $endDate)
        );
    }

    /**
     * @param null $date
     * @return Collection
     */
    public function getUnpaidCommissions($date = null)
    {
        $qb = DB::table('binary_commission')
            ->select(['week_ending'])
            ->where('status', '<>', BinaryCommission::PAID_STATUS)
            ->groupBy('week_ending');

        if ($date) {
            $qb->where('week_ending', '>=', $date);
        }

        return $qb->get();
    }

    /**
     * @param $date
     * @return bool
     */
    public function isPaidCommission($date)
    {
        $qb = DB::table('binary_commission')
            ->select(['week_ending'])
            ->where('status',BinaryCommission::PAID_STATUS)
            ->where('week_ending', '=', $date)
            ->groupBy('week_ending');

        return $qb->get()->isNotEmpty();
    }

    /**
     * @param BinaryCommissionHistory $commission
     */
    public function clearCommissionHistory(BinaryCommissionHistory $commission)
    {
        DB::statement('DELETE FROM bc_carryover_history WHERE bc_history_id = :id', [
            'id' => $commission->id
        ]);

        DB::table('bc_history')->delete($commission->id);
    }

    /**
     * @param $date
     * @return Model|Builder|object|null
     */
    public function getCommissionSummery($date)
    {
        return DB::table('binary_commission')
            ->select([
                DB::raw('count(user_id) as tsa_count'),
                DB::raw('SUM(amount_earned) as total'),
                DB::raw('MAX(amount_earned) as max_value')
            ])
            ->where('week_ending', '=', $date)
            ->first();
    }

    /**
     * @param $date
     * @return Model|Builder|object|null
     */
    public function getMaxPayoutForCommission($date)
    {
        return BinaryCommission::with('user')
            ->where('week_ending', '=', $date)
            ->orderBy('amount_earned', 'DESC')
            ->first();
    }

    /**
     * @param $date
     * @return Model|Builder|object|null
     */
    public function getCommissionByDate($date)
    {
        $qb = DB::table('binary_commission')
            ->select(['week_ending', 'status'])
            ->where('week_ending', '=', $date)
            ->groupBy(['week_ending', 'status']);

        return $qb->first();
    }
}
