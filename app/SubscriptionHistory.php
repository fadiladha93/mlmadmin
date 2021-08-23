<?php

namespace App;

use App\Http\Controllers\SubscriptionAlertController;
use App\Services\SubscriptionCronService;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model {

    protected $table = 'subscription_history';
    protected $fillable = [
        'user_id',
        'subscription_product_id',
        'attempted_date',
        'attempt_count',
        'status',
        'response',
        'next_attempt_date'
    ];
    public $timestamps = false;

    public static function updateSubscription($userId, $req) {
        $user = User::find($userId);

        $user->next_subscription_date = $req['next_subscription_date'];
        $user->subscription_payment_method_id = $req['subscription_payment_method_id'];
        $user->gflag = $req['gflag'];
        $user->save();
    }

    public static function getCurrentSubscriptionPlan($userId) {
        $subscriptionProduct = self::getSubscriptionProduct($userId);
        if (!$subscriptionProduct) {
            return false;
        }

        $currentProductId = User::getCurrentProductId($userId);
        $product = Product::getById($currentProductId);

        return $product->productname . ' $' . $subscriptionProduct->price . '/month';
    }

    public static function getSubscriptionProduct($userId) {
        $currentProductId = User::getCurrentProductId($userId);
        if (!$currentProductId) {
            return false;
        }
        $tvUser = \App\User::getById($userId);

        if (!empty($tvUser->subscription_product)) {
            return Product::getById($tvUser->subscription_product);
        }

        if ($tvUser->is_tv_user == 1) {
//            $idecide = \App\IDecide::getIDecideUserId($userId);
            $idecide = DB::table('idecide_users')
                ->select('*')
                ->where('user_id', $userId)
                ->first();
            if (!empty($idecide) && $idecide->status == \App\IDecide::ACTIVE) {
                $subscriptionProductId = Product::MONTHLY_MEMBERSHIP;
            } else {
                $tv_upg_products = [4];
                if (in_array($currentProductId, $tv_upg_products)) {
                    $subscriptionProductId = Product::MONTHLY_MEMBERSHIP;
                } else {
                    $subscriptionProductId = Product::ID_MONTHLY_MEMBERSHIP;
                }
            }
            return Product::getById($subscriptionProductId);
        } else if ($currentProductId == Product::ID_NCREASE_ISBO) {
            $subscriptionProductId = Product::MONTHLY_MEMBERSHIP_STAND_BY_USER;
            return Product::getById($subscriptionProductId);
        } else if ($currentProductId == Product::ID_BASIC_PACK) {
            $address = Address::getRec($userId, Address::TYPE_BILLING);
            $countryCode = (isset($address->countrycode) ? $address->countrycode : '');

            $country = Country::getCountryByCode($countryCode);
            if (!$country) {
                $subscriptionProductId = Product::MONTHLY_MEMBERSHIP;

                return Product::getById($subscriptionProductId);
                //return false;
            }

            if (Country::isTier3($countryCode)) {
                $subscriptionProductId = Product::TEIR3_COACHSUBSCRIPTION;
            } else {
                $subscriptionProductId = Product::MONTHLY_MEMBERSHIP;
            }

            return Product::getById($subscriptionProductId);
        } else {
            $subscriptionProductId = Product::MONTHLY_MEMBERSHIP;

            return Product::getById($subscriptionProductId);
        }
    }

    public static function runMonthlySubscriptionCron(Carbon $date = null)
    {
        if ( env('APP_ENV') === 'prod' || env('APP_ENV') === 'production' ) {
            $cronVersion = env('SUBSCRIPTION_CRON_VERSION', 2);

            switch($cronVersion) {
                case 1:
                    $cron = new SubscriptionAlertController();
                    return $cron->RunCronProcess($date);
                case 2:
                    $cron = new SubscriptionCronService();
                    return $cron->run($date);
            }
        }
    }

    public static function addEntry($userId, $subscriptionProductId, $subscriptionAttempts, $paymentMethodId, $response, $nextSubscriptionDate, $status)
    {
        $subscription = new SubscriptionHistory();
        $subscription->user_id = $userId;
        $subscription->subscription_product_id = $subscriptionProductId;
        $subscription->attempted_date = now()->format('Y-m-d');
        $subscription->attempt_count = $subscriptionAttempts;
        $subscription->payment_method_id = $paymentMethodId;
        $subscription->response = $response;
        $subscription->next_attempt_date = $nextSubscriptionDate;
        $subscription->status = $status;
        $subscription->save();
    }

    public static function UpdateSubscriptionHistoryOnly($user_id,
                                                         $attempt_date,
                                                         $attempt_count,
                                                         $status,
                                                         $products_id,
                                                         $payment_method_id,
                                                         $next_subscription_date,
                                                         $response = null, $isReactivate = 0)
    {
        // - Add subscription history.
        $subscription = new SubscriptionHistory();
        $subscription->user_id = $user_id;
        $subscription->subscription_product_id = $products_id;
        $subscription->attempted_date = $attempt_date;
        $subscription->attempt_count = $attempt_count;
        $subscription->payment_method_id = $payment_method_id;
        $subscription->response = $response;
        $subscription->next_attempt_date = $next_subscription_date;
        $subscription->status = $status;
        $subscription->is_reactivate = $isReactivate;
        $subscription->save();
    }

    public static function getNextSubscriptionDate()
    {
        $currentDate = date('Y-m-d');
        $date = strtotime(date("Y-m-d", strtotime($currentDate)) . " +1 month");
        $date = date('Y-m-d', $date);

        $parts = explode('-', $date);

        if (end($parts) > 25) {
            $parts[2] = 25;
        }

        $nextSubscriptionDate = implode('-', $parts);
        return $nextSubscriptionDate;
    }
}
