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
use MyMail;
use tokenexAPI;

class SubscriptionCronService
{
    private $tokenEx;

    private const NO_SUBSCRIPTION_PRODUCT = 1;
    private const SUBSCRIPTION_PRODUCT_DOES_NOT_EXIST = 2;
    private const NO_PAYMENT_METHOD = 3;
    private const NO_BILLING_ADDRESS_ON_PAYMENT_METHOD = 4;
    private const BAD_BILLING_ADDRESS_ON_PAYMENT_METHOD = 5;
    private const DETOKENIZATION_ERROR = 6;
    private const CURRENCY_CONVERSION_ERROR = 7;

    public function __construct()
    {
        $this->tokenEx = new tokenexAPI();
    }

    private function importCsv($filename)
    {
        $csv = array_map('str_getcsv', file($filename));

        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        array_shift($csv);

        return $csv;
    }

    /**
     * Goes through $possibilities, checking if any of the keys exist in $array
     * If it finds a key, it returns the key, null otherwise.
     *
     * @param array $array
     * @param array $possibilities
     * @return string|null returns the key if it finds anything, otherwise null
     */
    private function whichKeyExists(array $array, array $possibilities)
    {
        foreach ($possibilities as $possibility) {
            if (in_array($possibility, $array)) {
                return $possibility;
            }
        }

        return null;
    }

    private function importCronCSV()
    {
        $filename = env('SUBSCRIPTION_CRON_CSV');
        echo 'Parsing csv: ' . $filename;

        $userList = $this->importCsv($filename);

        $headers = array_keys($userList[0]);

        $userIdKeys = [
            'userid',
            'user_id'
        ];

        $distIdKeys = [
            'distid',
            'dist_id'
        ];

        $userIdKey = $this->whichKeyExists($headers, $userIdKeys);

        if (!$userIdKey) {
            $distIdKey = $this->whichKeyExists($headers, $distIdKeys);

            if (!$distIdKey) {
                echo 'No dist id key found!';
                return [];
            }

            $distIds = array_column($userList, $distIdKey);

            return User::whereIn('distid', $distIds)->get();
        }

        $userIds = array_column($userList, $userIdKey);

        return User::whereIn('id', $userIds)->get();
    }

    /**
     * @param Collection $users
     * @return mixed
     */
    private function eliminateUsersWithOrdersThisCycle($users)
    {
        $firstOfThisMonth = date('Y-m-01');
        $lastOfThisMonth = date('Y-m-t');

        return $users->filter(function($user) use ($firstOfThisMonth, $lastOfThisMonth) {
            /**
             * @var \App\User $user
             */
            return !$user->orders()->whereDate('orders.created_date', '>=', $firstOfThisMonth)
                                    ->whereDate('orders.created_date', '<=', $lastOfThisMonth)
                                    ->join('orderItem', 'orders.id', '=', 'orderItem.orderid')
                                    ->whereIn('orderItem.productid', [11,12,26,33])
                                    ->exists();
        });
    }

    private function getSubscriptionsToRun($date)
    {
        if (env('SUBSCRIPTION_CRON_CSV')) {
            $users = $this->importCronCSV();
            return $this->eliminateUsersWithOrdersThisCycle($users);
        }

        $failedSubUsers = SubscriptionHistory::select('user_id')
            ->whereDate('attempted_date', '>=', '2020-10-06')
            ->where('status', '=', 0)
            ->groupBy(['user_id'])
            ->get();

        $failedSubUserIds = array_column($failedSubUsers->all(), 'user_id');
        $subRunsPreviousMonthUserIds = Order::getUserIdsWithSubscriptionRunsPreviousMonth();

        return User::getSubscriptionsToRun($date, $failedSubUserIds, $subRunsPreviousMonthUserIds);
    }

    private function writeFailureToSubscriptionHistory($user, $error, $productId = null, $paymentMethodId = null)
    {
        $errorMessage = 'Subscription failed - ';

        switch ($error) {
            case self::NO_SUBSCRIPTION_PRODUCT:
                $errorMessage .= 'no subscription_product set';
                break;
            case self::SUBSCRIPTION_PRODUCT_DOES_NOT_EXIST:
                $errorMessage .= 'subscription product does not exist';
                break;
            case self::NO_PAYMENT_METHOD:
                $errorMessage .= 'no available payment method';
                break;
            case self::NO_BILLING_ADDRESS_ON_PAYMENT_METHOD:
                $errorMessage .= 'no billing address on payment method';
                break;
            case self::BAD_BILLING_ADDRESS_ON_PAYMENT_METHOD:
                $errorMessage .= 'bad billing address on payment method';
                break;
            case self::DETOKENIZATION_ERROR:
                $errorMessage .= 'detokenization error';
                break;
            case self::CURRENCY_CONVERSION_ERROR:
                $errorMessage .= 'currency conversion error';
                break;
        }

        $errorMessage .= ' (code: ' . (int)$error . ')';

        SubscriptionHistory::addEntry(
            $user->id,
            $productId,
            $user->subscription_attempts,
            $paymentMethodId,
            $errorMessage,
            $user->next_subscription_date,
            0);
    }

    private function getProductOrFail($user)
    {
        if (!$user->subscription_product) {
            echo 'No subscription_product set!' . PHP_EOL;
            $this->writeFailureToSubscriptionHistory($user, self::NO_SUBSCRIPTION_PRODUCT, null, null);
            $user->update([
                'is_sites_deactivate' => 1
            ]);
            return false;
        }

        $product = Product::find($user->subscription_product);

        if (!$product) {
            echo 'Subscription_product (id ' . $user->subscription_product . ') does not exist.' . PHP_EOL;
            $this->writeFailureToSubscriptionHistory($user, self::SUBSCRIPTION_PRODUCT_DOES_NOT_EXIST, $user->subscription_product, null);
            $user->update([
                'is_sites_deactivate' => 1
            ]);
            return false;
        }

        return $product;
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

    private function noPaymentMethodFailure($user, $product)
    {
        $user->update([
            'is_sites_deactivate' => 1
        ]);

        $this->writeFailureToSubscriptionHistory($user, self::NO_PAYMENT_METHOD, $product->id, null);

        if ($this->isProduction()) {
            $this->deactivateSorAndiDecide($user);

            $error = 'Subscription failed - insufficient ewallet balance and no payment method found';
            $this->sendFailureEmail($user, $error);
        }
    }

    private function getBillingAddressOrFail($user, $paymentMethod)
    {
        $billingAddress = $paymentMethod->address;

        if ($billingAddress) {
            return $billingAddress;
        }

        $errorMessage = 'Invalid address for user payment method (id ' . $paymentMethod->id . ')';
        echo $errorMessage . PHP_EOL;

        $this->writeFailureToSubscriptionHistory($user, self::BAD_BILLING_ADDRESS_ON_PAYMENT_METHOD, $user->subscription_product, $paymentMethod->id);
        return false;
    }

    private function pushUserSubNextMonth($user)
    {
        $nextSubscriptionDate = $this->determineNextSubscriptionDate($user);

        $user->update([
            'original_subscription_date' => $nextSubscriptionDate,
            'next_subscription_date' => $nextSubscriptionDate
        ]);
    }

    public function run($date = null)
    {
        echo "\n\nStarting Subscription Cron...\n";
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $users = $this->getSubscriptionsToRun($date);

        $fraudResponses = array_column(FraudResponse::all()->toArray(), 'text');

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            echo PHP_EOL . PHP_EOL;
            echo str_repeat('*', 10) . ' ' . $user->distid . ' ' . str_repeat('*', 10);
            echo PHP_EOL;

            $user->update([
                'subscription_attempts' => $user->subscription_attempts + 1
            ]);

            $product = $this->getProductOrFail($user);

            if (!$product) {
                continue;
            }

            $ewalletPaymentMethod = $this->createOrFindEwalletPaymentMethod($user);

            if ($this->canChargeEwallet($user, $product)) {
                $this->recurringSuccess($user, $product, $ewalletPaymentMethod);
                continue;
            }

            $paymentMethod = $this->getPaymentMethodOrFail($user);

            if (!$paymentMethod) {
                $this->noPaymentMethodFailure($user, $product);
                continue;
            }

            $billingAddress = $this->getBillingAddressOrFail($user, $paymentMethod);

            if (!$billingAddress) {
                continue;
            }

            $amount = $product->price;

            $currency = 'USD';
            //$currencyAmount = $amount;
//            list($conversionSuccess, $currency, $currencyAmount, $exchangeRate, $displayAmount) =
//                $this->convertCurrencyOrFail($user, $billingAddress->countrycode, $paymentMethod->id, $amount);

            // if (!$conversionSuccess) {
            //     echo "Currency conversion failed!" . PHP_EOL;
            //     continue;
            // }

            $paymentAmount = $amount;

            $result = $this->chargePaymentGateway($user, $paymentMethod->card_token, $paymentMethod, $billingAddress, $product, $currency, $paymentAmount);

            // $result = array_merge($result, [
            //     'currency' => $currency,
            //     'currencyAmount' => $currencyAmount,
            //      'exchangeRate' => $exchangeRate,
            //      'displayAmount' => $displayAmount
            // ]);

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

        echo 'Finished Subscription Cron..' . PHP_EOL;
    }

    private function sendFailureEmail($user, $message)
    {
        try {
            MyMail::sendSubscriptionRecurringFailed(
                $user->firstname,
                $user->lastname,
                $user->distid,
                $user->email,
                $message
            );
        } catch (Exception $exception) {
            echo 'Email sending failed' . PHP_EOL;
            echo $exception->getMessage() . PHP_EOL;
        }
    }

    private function deactivateSorAndiDecide($user)
    {
        echo 'Deactivating iDecide & SOR' . PHP_EOL;

        $iDecideResult  = Helper::deActivateIdecideUser($user->id);

        if ($iDecideResult['error'] === 1) {
            echo 'iDecide Deactivation error: ' . $iDecideResult['msg'] . PHP_EOL;
        }

        $sorResult = Helper::deActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, 'Subscription payment failure');

        if ($sorResult['error'] === 1) {
            echo 'SOR Deactivation error: ' . $sorResult['msg'] . PHP_EOL;
        }
    }

    protected function isProduction()
    {
        return in_array(strtolower(env('APP_ENV')), ['prod', 'production']);
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
        $user->update([
            'is_sites_deactivate' => 1
        ]);

        if ($this->isProduction()) {
            $this->deactivateSorAndiDecide($user);
        }

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
                'is_deleted' => 1
            ]);

            $paymentMethod->delete();
        }

        SubscriptionHistory::addEntry(
            $user->id,
            $product->id,
            $user->subscription_attempts,
            $paymentMethod->id,
            'Subscription payment failure - ' . $result['responsetext'],
            $user->next_subscription_date,
            0);

        if ($this->isProduction()) {
            echo 'Sending failure email' . PHP_EOL;
            $errorMessage = 'Subscription payment failure';

            $this->sendFailureEmail($user, $errorMessage);
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

        $orderId = Order::addNew(
            $user->id,
            $orderSubtotal,
            $orderTotal,
            $orderBV,
            $orderQV,
            $orderCV,
            $transactionId,
            null,
            null,
            null,
            Carbon::now()->format('Y-m-d'),
            null,
            null,
            null,
            0,
            0,
            null,
            $paymentMethod->id
        );

        if (isset($result['currency'])) {
            $orderConversion = new OrderConversion([
                'order_id' => $orderId,
                'original_amount' => $orderTotal *  100,
                'original_currency' => 'USD',
                'converted_amount' => $result['currencyAmount'],
                'converted_currency' => $result['currency'],
                'exchange_rate' => $result['exchangeRate'],
                'display_amount' => $result['displayAmount'],
                'expires_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $orderConversion->save();
        }

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
            Carbon::now()
        );

        return $orderId;
    }

    private function addEwalletTransaction($user, $product, $orderId)
    {
        echo 'Adding ewallet transaction..' . PHP_EOL;
        $price = $product->price;
        EwalletTransaction::addPurchase($user->id, EwalletTransaction::MONTHLY_SUBSCRIPTION, (-$price), $orderId);
    }

    private function addBoomerangs($user, $product)
    {
        $numBoomerangs = $product->num_boomerangs;
        echo "Adding $numBoomerangs boomerangs\n";
        BoomerangInv::addToInventory($user->id, $numBoomerangs);
    }

    private function recurringSuccess($user, $product, $paymentMethod, $result = null)
    {
        $orderId = $this->createOrder($user, $product, $paymentMethod, $result);

        if ($paymentMethod->pay_method_type == PaymentMethodType::TYPE_E_WALET) {
            $this->addEwalletTransaction($user, $product, $orderId);
        }

        $this->addBoomerangs($user, $product);

        $nextSubscriptionDate = $this->determineNextSubscriptionDate($user);

        $userUpdates = [
            'original_subscription_date' => $nextSubscriptionDate,
            'next_subscription_date' => $nextSubscriptionDate,
            'subscription_attempts' => 0,
            'is_sites_deactivate' => 0
        ];

        $user->update($userUpdates);

        SubscriptionHistory::addEntry(
            $user->id,
            $product->id,
            $user->subscription_attempts,
            $paymentMethod->id,
            'Subscription payment success',
            $user->next_subscription_date,
            1);

        if ($this->isProduction()) {

            try {
                MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->distid, $user->email);
            } catch (Exception $exception) {
                echo 'Email sending failed' . PHP_EOL;
                echo $exception->getMessage() . PHP_EOL;
            }

            Helper::reActivateIdecideUser($user->id);
            Helper::reActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, 'Subscription payment success');
        }
    }

    protected function convertCurrencyOrFail($user, $countryCode, $paymentMethodId, $amount)
    {
        // Successful but nothing to report, we didn't have to convert.
        if (strtoupper($countryCode) == 'US') {
            return array(true, null, null, null, null);
        }

        $result = CurrencyConverter::convert($amount * 100, $countryCode);

        if (!$result) {
            echo "Currency conversion failed. Skipping user.\n";
            $this->writeFailureToSubscriptionHistory($user, self::CURRENCY_CONVERSION_ERROR, $user->subscription_product, $paymentMethodId);
            return array(false, null, null, null, null);
        }

        $currency = $result['currency'];
        $currencyAmount = $result['amount'];
        $exchangeRate = $result['exchange_rate'];
        $displayAmount = $result['display_amount'];

        return array(true, $currency, $currencyAmount, $exchangeRate, $displayAmount);
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
            PaymentMethodType::TYPE_PAYNETWORX,
            $currency,
            true);
    }

    /**
     * @param User $user
     * @param Product $product
     */
    private function canChargeEwallet($user, $product)
    {
        if ($user->estimated_balance < $product->price) {
            echo 'Insufficient ewallet balance: ' . number_format($user->estimated_balance, 2) . PHP_EOL;
            return false;
        }

        echo 'Using ewallet balance: ' . $product->price . ' / ' . number_format($user->estimated_balance, 2) . PHP_EOL;

        return true;
    }

    /**
     * @param User $user
     * @return PaymentMethod
     */
    private function createOrFindEwalletPaymentMethod($user)
    {
        $paymentMethod = PaymentMethod::where('userID', '=', $user->id)
            ->where('pay_method_type', '=', PaymentMethodType::TYPE_E_WALET)
            ->where(function ($q) {
                $q->where('is_deleted', '=', 0)->orWhereNull('is_deleted');
            })
            ->first();

        if (!$paymentMethod) {
            echo 'No ewallet payment method found.. creating.' . PHP_EOL;
            $paymentMethod = PaymentMethod::create([
                'userID' => $user->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'pay_method_type' => PaymentMethodType::TYPE_E_WALET
            ]);
        }

        return $paymentMethod;
    }

    /**
     * @param User $user
     *
     * @return Carbon
     */
    private function determineNextSubscriptionDate($user)
    {
        $day = $user->original_subscription_date ? $user->original_subscription_date->day : $user->next_subscription_date->day;
        $newSubDate = $user->next_subscription_date->addMonthNoOverflow();
        $newSubDate->day($day);

        return $newSubDate;
    }
}
