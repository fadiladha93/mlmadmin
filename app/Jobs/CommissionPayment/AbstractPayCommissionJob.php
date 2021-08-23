<?php

namespace App\Jobs\CommissionPayment;

use App\EwalletTransaction;
use App\Services\UnilevelCommission;
use App\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Imtigger\LaravelJobStatus\Trackable;
use utill;

/**
 * Class AbstractPayCommissionJob
 * @package App\Jobs\CommissionPayment
 */
abstract class AbstractPayCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /** @var int  */
    public $tries = 1;

    protected $endDate;

    /**
     * Create a new job instance.
     *
     * @param $fromDate
     * @param $toDate
     */
    public function __construct($toDate)
    {
        set_time_limit(0);

        $this->endDate = $toDate;

        $this->prepareStatus();
    }

    public function handle()
    {
        $this->processPay();
    }

    public function processPay()
    {
        $userCommissions = $this->getCommissionsForPay();

        foreach ($userCommissions as $userId => $commissions) {
            $user = User::find($userId);

            if (!$user) {
                throw new Exception(sprintf('User with ID %s not found', $userId));
            }

            DB::transaction(function () use ($user, $commissions) {
                $sum = 0;

                foreach ($commissions as $commission) {
                    $sum += $commission->amount;

                    $commission->status = UnilevelCommission::PAID_STATUS;
                    $commission->save();
                }

                $openingBalance = $user->estimated_balance;
                $closingBalance = $openingBalance + $sum;

                $ew = new EwalletTransaction();
                $ew->user_id = $user->id;
                $ew->opening_balance = $openingBalance;
                $ew->closing_balance = $closingBalance;
                $ew->amount = abs($sum);
                $ew->created_at = utill::getCurrentDateTime();
                $ew->purchase_id = 0;
                $ew->type = EwalletTransaction::TYPE_DEPOSIT;
                $ew->commission_type = $this->getCommissionType();
                $ew->save();

                $user->estimated_balance = $closingBalance;
                $user->save();
            });
        }
    }

    /**
     * @return mixed
     */
    abstract public function getCommissionsForPay();

    /**
     * @return string
     */
    abstract public function getCommissionType();
}
