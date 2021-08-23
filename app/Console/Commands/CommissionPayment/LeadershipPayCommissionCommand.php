<?php

namespace App\Console\Commands\CommissionPayment;

use App\EwalletTransaction;
use App\Models\LeadershipCommission;

/**
 * Class LeadershipPayCommissionCommand
 * @package App\Console\Commands\CommissionPayment
 */
class LeadershipPayCommissionCommand extends AbstractPayCommissionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:pay:leadership';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script for paying out Leadership commission';

    /**
     * @return mixed
     */
    public function getCommissionsForPay()
    {
        return LeadershipCommission::where('status', \App\Services\LeadershipCommission::POSTED_STATUS)->get()->groupBy('user_id');
    }

    /**
     * @return string
     */
    public function getCommissionType()
    {
        return EwalletTransaction::TYPE_LEADERSHIP_COMMISSION;
    }
}
