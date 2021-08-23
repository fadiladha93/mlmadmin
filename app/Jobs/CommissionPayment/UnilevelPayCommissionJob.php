<?php

namespace App\Jobs\CommissionPayment;

use App\EwalletTransaction;
use App\Models\UnilevelCommission;

/**
 * Class UnilevelPayCommissionJob
 * @package App\Jobs\CommissionPayment
 */
class UnilevelPayCommissionJob extends AbstractPayCommissionJob
{
    /**
     * @return mixed
     */
    public function getCommissionsForPay()
    {
        return UnilevelCommission::where('status', \App\Services\UnilevelCommission::POSTED_STATUS)
            ->whereDate('end_date', '=', $this->endDate)
            ->get()
            ->groupBy('user_id');
    }

    /**
     * @return string
     */
    public function getCommissionType()
    {
        return EwalletTransaction::TYPE_UNILEVEL_COMMISSION;
    }
}
