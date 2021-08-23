<?php

namespace App\Console\Commands\CommissionPayment;

use App\EwalletTransaction;
use App\Services\UnilevelCommission;
use App\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use utill;

/**
 * Class AbstractPayCommissionCommand
 * @package App\Console\Commands\СommissionPayment
 */
abstract class AbstractPayCommissionCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);

        $this->info('Start doing payments for users');

        $errors = $this->processPay();

        foreach ($errors as $message) {
            $this->error($message);
        }

        $this->info('Finish');
    }

    /**
     * @return array
     */
    public function processPay()
    {
        $errors = [];
        $userCommissions = $this->getCommissionsForPay();

        foreach ($userCommissions as $userId => $commissions) {
            try {
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

            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }

        }

        return $errors;
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
