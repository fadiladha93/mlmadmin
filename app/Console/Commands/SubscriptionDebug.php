<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class SubscriptionDebug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SubscriptionDebug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SubscriptionDebug';

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
        $userListToRun = [];
        $orderExists = [];
        $userAlreadyAttempted = [];
        $users = DB::table('users')
            ->where('account_status', \App\User::ACC_STATUS_APPROVED)
            ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
            ->where('current_product_id', '!=', \App\Product::ID_PREMIUM_FIRST_CLASS)
            ->whereDate('next_subscription_date', '>=', '2019-10-01')
            ->whereDate('next_subscription_date', '<=', '2019-10-31')
            ->whereMonth('created_dt', '!=', '09')
//            ->where('id', 37664)
//            ->where('payment_fail_count', '>', 0)
            ->where('payment_fail_count', '=', 0)
            ->orderBy('id', 'desc')
            ->get();

        // - Processing each user.
        foreach ($users as $user) {
            //check orders
            $orders = DB::table('orders')
                ->join('orderItem', 'orders.id', '=', 'orderItem.orderid')
                ->join('products', 'orderItem.productid', '=', 'products.id')
                ->where('products.producttype', \App\ProductType::TYPE_MEMBERSHIP)
                ->whereDate('orders.created_dt', '<=', '2019-09-30')
                ->whereDate('orders.created_dt', '>=', '2019-09-01')
                ->where('orders.userid', $user->id)
                ->count();
            if ($orders > 0) {
                echo "Subscription order already exists\n";
                $orderExists[] = $user->distid;
                continue;
            } else {
                echo $this->make_response_output($user->distid . "-" . $user->id);
                //check his already attempted in sep
                $subscriptionAttempted = DB::table('subscription_history')
                    ->where('user_id', $user->id)
                    ->whereMonth('attempted_date', '09')
                    ->whereMonth('next_attempt_date', '10')
                    ->count();
                if ($subscriptionAttempted)
                    $userAlreadyAttempted[] = $user->distid;
                else
                    $userListToRun[] = $user->distid;
            }
        }
        file_put_contents('orders_not_created.json', json_encode($userListToRun));
        file_put_contents('orders_already_created.json', json_encode($orderExists));
        file_put_contents('user_already_attempted.json', json_encode($userAlreadyAttempted));
        echo "\n\nBuuum...";
    }

    private function make_response_output($data, $newline = true)
    {
        return $data . (($newline) ? "" . PHP_EOL : '');
    }

}
