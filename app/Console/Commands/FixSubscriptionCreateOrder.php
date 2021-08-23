<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class FixSubscriptionCreateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:FixSubscriptionCreateOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('subscription_history')
            ->join('users', 'users.id', '=', 'subscription_history.user_id')
            ->select('users.id as user_id', 'subscription_history.id as subscription_history_id', 'users.distid', 'users.original_subscription_date', 'subscription_history.payment_method_id')
            ->whereDate('subscription_history.attempted_date', '2019-10-19')
            ->where('subscription_history.response', 'Subscription exceptions - Undefined variable: orderDate')
            ->get();
        foreach ($users as $user) {
            $userDetail = \App\User::find($user->user_id);
            $payment_method = \App\PaymentMethod::getById($user->payment_method_id);
            $product = \App\SubscriptionHistory::getSubscriptionProduct($user->user_id);
            // Check user has product for payment.
            if ($product) {
                $orderBV = $product->bv;
                $orderQV = $product->qv;
                $orderCV = $product->cv;
                $orderSubtotal = $product->price;
                $orderTotal = $product->price;
                $numberOfBoomerangs = $product->num_boomerangs;
                $user_attempt_count = $userDetail->subscription_attempts + 1;
                echo $userDetail->distid . " -------- " . $user->user_id . " -------- \n";
                try {
                    if ($payment_method->pay_method_type == \App\PaymentMethodType::TYPE_E_WALET) {
                        $orderId = \App\Order::addNew($user->user_id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, $user->payment_method_id, null, null, '2019-10-19', null);
                        \App\OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                        \App\EwalletTransaction::addPurchase($user->user_id, \App\EwalletTransaction::MONTHLY_SUBSCRIPTION, (-$product->price), $orderId);
                        \App\BoomerangInv::addToInventory($user->user_id, $numberOfBoomerangs);
                        // - Check gflag and Reached maximum payment attempt.
                        if ($userDetail->gflag == 1 || $user_attempt_count >= 2) {
                            //- SET SRO and iDECIED accounts ACTIVATE.
                            \App\Helper::reActivateIdecideUser($user->user_id);
                            \App\Helper::reActivateSaveOnUser($user->user_id, $product->id, $userDetail->distid, 'Monthly subscription payment success');
                            $this->make_activate($user->user_id, 1);
                        }
                        DB::table('subscription_history')->where('id', $user->subscription_history_id)->update(['response' => 'Subscription exceptions - Undefined variable: orderDate - done']);
                    } else {
                        $orderId = \App\Order::addNew($user->user_id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, 'MANUAL#20191019', $user->payment_method_id, null, null, '2019-10-19', null);
                        \App\OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                        \App\BoomerangInv::addToInventory($user->user_id, $numberOfBoomerangs);
                        // - Check gflag and Reached maximum payment attempt.
                        if ($userDetail->gflag == 1 || $user_attempt_count >= 2) {
                            //- SET SRO and iDECIED accounts ACTIVATE.
                            \App\Helper::reActivateIdecideUser($user->user_id);
                            \App\Helper::reActivateSaveOnUser($user->user_id, $product->id, $userDetail->distid, 'Monthly subscription payment success');
                            $this->make_activate($user->user_id, 1);
                        }
                        DB::table('subscription_history')->where('id', $user->subscription_history_id)->update(['response' => 'Subscription exceptions - Undefined variable: orderDate - done']);
                    }
                } catch (\Exception $ex) {
                    echo "\n\n";
                    echo $ex->getMessage();
                    die;
                }
            }
        }
    }

    public function make_activate($user_id, $type = 1)
    {
        $user = \App\User::where('id', $user_id);
        if ($type == 1) {
            $user->update(['is_sites_deactivate' => 0]);
        } elseif ($type == 2) {
            $user->update(['is_deactivate' => 0]);
        }
    }
}
