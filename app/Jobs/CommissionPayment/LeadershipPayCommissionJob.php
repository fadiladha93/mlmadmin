<?php

namespace App\Jobs\CommissionPayment;

use App\EwalletTransaction;
use App\Models\LeadershipCommission;

/**
 * Class LeadershipPayCommissionJob
 * @package App\Jobs\CommissionPayment
 */
class LeadershipPayCommissionJob extends AbstractPayCommissionJob
{
    /**
     * @return mixed
     */
    public function getCommissionsForPay()
    {
        return LeadershipCommission::where('status', \App\Services\LeadershipCommission::POSTED_STATUS)
            ->whereDate('end_date', '=', $this->endDate)
            ->get()
            ->groupBy('user_id');
    }

    /**
     * @return string
     */
    public function getCommissionType()
    {
        return EwalletTransaction::TYPE_LEADERSHIP_COMMISSION;
    }
}
