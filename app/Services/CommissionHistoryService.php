<?php

namespace App\Services;

use App\BinaryCommissionHistory;

/**
 * Class CommissionHistoryService
 * @package App\Services
 */
class CommissionHistoryService
{
    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getCommissionByDate($startDate, $endDate)
    {
        return BinaryCommissionHistory::where('start_date', '=', $startDate)
            ->where('end_date', '=', $endDate)
            ->first();
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getPreviousCommission($startDate, $endDate)
    {
        return BinaryCommissionHistory::where('start_date', '<', $startDate)
            ->where('end_date', '<', $endDate)
            ->orderBy('start_date', 'desc')
            ->first();
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getNextCommissions($startDate, $endDate)
    {
        return BinaryCommissionHistory::where('start_date', '>=', $startDate)
            ->where('end_date', '>=', $endDate)
            ->orderBy('start_date', 'asc')
            ->get();
    }
}
