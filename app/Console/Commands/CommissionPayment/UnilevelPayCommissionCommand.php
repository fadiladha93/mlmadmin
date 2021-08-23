<?php

namespace App\Console\Commands\CommissionPayment;

use App\EwalletTransaction;
use App\Models\UnilevelCommission;

/**
 * Class UnilevelPayCommissionCommand
 * @package App\Console\Commands\CommissionPayment
 */
class UnilevelPayCommissionCommand extends AbstractPayCommissionCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:pay:unilevel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script for paying out Unilevel commission';

    /**
     * @return mixed
     */
    public function getCommissionsForPay()
    {
        return UnilevelCommission::where('status', \App\Services\UnilevelCommission::POSTED_STATUS)->get()->groupBy('user_id');
    }

    /**
     * @return string
     */
    public function getCommissionType()
    {
        return EwalletTransaction::TYPE_UNILEVEL_COMMISSION;
    }
}
