<?php


namespace App\Console\Commands\HotFixCommands;

use App\PaymentMethod;
use App\PaymentMethodType;
use App\UserType;
use Illuminate\Console\Command;
use DB;
use App\User;

class EWalletOnAllUsers extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:EWalletOnAllUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This makes sure every user has an ewallet payment method';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $usersWithEWallet =  PaymentMethod::select(['userID'])->where('pay_method_type', '=', PaymentMethodType::TYPE_E_WALET)
            ->where('is_restricted', '=', 0)
            ->where(function ($q) {
                $q->where('is_deleted', '=', 0)->orWhereNull('is_deleted');
            })
            ->get();

        $userIdsWithEWallet = array_column($usersWithEWallet->toArray(), 'userID');

        unset($usersWithEWallet);

        $users = User::select(['id'])
            ->where('usertype', UserType::TYPE_DISTRIBUTOR)
            ->whereNotIn('account_status', [User::ACC_STATUS_SUSPENDED, User::ACC_STATUS_TERMINATED])
            ->get();

        $users = $users->filter(function($user) use ($userIdsWithEWallet) {
            return !in_array($user->id, $userIdsWithEWallet);
        });

        unset($userIdsWithEWallet);

        $userIds = array_column($users->toArray(), 'id');

        unset($users);

        foreach ($userIds as $userId) {
            PaymentMethod::addNewCustomPaymentMethod([
                'userID' => $userId,
                'created_at' => \utill::getCurrentDateTime(),
                'updated_at' => \utill::getCurrentDateTime(),
                'pay_method_type' => PaymentMethodType::TYPE_E_WALET
            ]);
        }
    }


}
