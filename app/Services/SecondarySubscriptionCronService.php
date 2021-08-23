<?php

namespace App\Services;

use App\Address;
use App\BoomerangInv;
use App\EwalletTransaction;
use App\FraudResponse;
use App\Helper;
use App\helpers\CurrencyConverter;
use App\Models\OrderConversion;
use App\Order;
use App\OrderItem;
use App\PaymentMethod;
use App\PaymentMethodType;
use App\Product;
use App\SubscriptionHistory;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MyMail;
use tokenexAPI;

class SecondarySubscriptionCronService
{
    private function getUsersToRun()
    {
        $results = DB::select(DB::raw("
                                select u.id from orders o
                                join \"orderItem\" oi on oi.orderid=o.id
                                join users u on u.id=o.userid
                                where o.created_dt between '2020-06-01 00:00:00' and '2020-08-31 23:59:59'
                                and oi.productid=56
                                and o.statuscode=1
                                and (select count(*) from orders where created_dt between '2020-09-01 00:00:00' and '2020-09-30 23:59:59' and userid=o.userid) > 0
                                and u.account_status='APPROVED';"));

        $userIds = array_column($results, 'id');
        return User::findMany($userIds);
    }

    private function getPaymentMethodOrFail($user)
    {
        echo 'Retrieving primary payment method..' . PHP_EOL;

        $primaryPaymentMethod = $user->getFirstNonExpiredPaymentMethod(true);

        if ($primaryPaymentMethod) {
            return $primaryPaymentMethod;
        }

        echo 'No primary payment method found!' . PHP_EOL;

        return false;
    }

    private function getBillingAddressOrFail($user, $paymentMethod)
    {
        $billingAddress = $paymentMethod->address;

        if ($billingAddress) {
            return $billingAddress;
        }

        $errorMessage = 'Invalid address for user payment method (id ' . $paymentMethod->id . ')';
        echo $errorMessage . PHP_EOL;
        return false;
    }

    public function run()
    {
        echo "\n\nStarting Secondary Subscription Cron...\n";

        $users = $this->getUsersToRun();

        $fraudResponses = array_column(FraudResponse::all()->toArray(), 'text');

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            echo PHP_EOL . PHP_EOL;
            echo str_repeat('*', 10) . ' ' . $user->distid . ' ' . str_repeat('*', 10);
            echo PHP_EOL;

            $product = Product::find(56);

            $paymentMethod = $this->getPaymentMethodOrFail($user);

            if (!$paymentMethod) {
                continue;
            }

            $billingAddress = $this->getBillingAddressOrFail($user, $paymentMethod);

            if (!$billingAddress) {
                continue;
            }

            $amount = $product->price;
            $currency = 'USD';
            $paymentAmount = $amount;

            $result = $this->chargePaymentGateway($user, $paymentMethod->card_token, $paymentMethod, $billingAddress, $product, $currency, $paymentAmount);


            if (isset($result) && $result['response'] == 1) {
                echo 'Subscription success!' . PHP_EOL;
                $this->recurringSuccess($user, $product, $paymentMethod, $result);
            } else {
                echo 'Subscription failure!' . PHP_EOL;
                if (isset($result['msg'])) {
                    echo 'Error: ' . PHP_EOL . $result['msg'] . PHP_EOL;
                }

                $this->recurringFailure($user, $product, $paymentMethod, $result, $fraudResponses);
            }
        }

        echo 'Finished Secondary Subscription Cron..' . PHP_EOL;
    }


    /**
     * @param User $user
     * @param $product
     * @param $paymentMethod
     * @param $result
     * @param $fraudResponses
     */
    private function recurringFailure($user, $product, $paymentMethod, $result, $fraudResponses)
    {
        $response = $result['responsetext'];

        echo 'Payment gateway response: ' . $response . PHP_EOL;

        $isRestricted = false;

        foreach ($fraudResponses as $fraudResponse) {
            if (stripos(strtolower($response), strtolower($fraudResponse)) !== false) {
                $isRestricted = true;
                break;
            }
        }

        if ($isRestricted) {
            echo 'Payment gateway response indicated restricted card. Deleting card.' . PHP_EOL;

            $paymentMethod->update([
                'active' => 0,
                'is_primary' => 0,
                'is_deleted' => 1,
            ]);

            $paymentMethod->delete();
        }
    }

    private function createOrder($user, $product, $paymentMethod, $result = null)
    {
        echo 'Creating order and adding orderItem...' . PHP_EOL;

        $productId = $product->id;
        $orderBV = $product->bv;
        $orderQV = $product->qv;
        $orderCV = $product->cv;
        $orderSubtotal = $product->price;
        $orderTotal = $product->price;
        $transactionId = $result ? $result['transactionid'] . '#creditcard' : null;

        $orderId = Order::addNew($user->id,
            $orderSubtotal,
            $orderTotal,
            $orderBV,
            $orderQV,
            $orderCV,
            $transactionId,
            null,
            null,
            null,
            now()->format('Y-m-d'),
            null,
            null,
            null,
            0,
            0,
            null,
            $paymentMethod->id);

        OrderItem::addNew(
            $orderId,
            $productId,
            1,
            $orderTotal,
            $orderBV,
            $orderQV,
            $orderCV,
            false,
            null,
            0,
            0,
            now()
        );

        return $orderId;
    }

    private function recurringSuccess($user, $product, $paymentMethod, $result = null)
    {
        $this->createOrder($user, $product, $paymentMethod, $result);
    }

    protected function chargePaymentGateway($user, $number, $paymentMethod, $billingAddress, $product, $currency, $amount)
    {
        echo 'Attempting to charge card at payment gateway..' . PHP_EOL;
//        $paymentMethodType = strtoupper($billingAddress->countrycode) == 'US' ?
//                        env('SUBS_CRON_US_PAYMENT_METHOD', PaymentMethodType::TYPE_PAYARC) :
//                        env('SUBS_CRON_OTHER_PAYMENT_METHOD', PaymentMethodType::TYPE_T1_PAYMENTS);

        return Helper::networkMerchants(
            $user,
            $number,
            $paymentMethod->expiration_year,
            $paymentMethod->expiration_month,
            $product,
            $billingAddress,
            $amount,
            PaymentMethodType::TYPE_PAYARC,
            $currency,
            true);
    }
}
