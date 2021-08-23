<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\UserPaymentMethod;

class PaymentMethod extends Model
{

    const MINIMUM_TOKEN_LENGTH = 8;

    const PAYMENT_METHOD_TYPE_CREDITCARD      = 1;
    const PAYMENT_METHOD_TYPE_ADMIN           = 2;
    const PAYMENT_METHOD_TYPE_EWALLET         = 3;
    const PAYMENT_METHOD_TYPE_BITPAY          = 4;   // Not In use
    const PAYMENT_METHOD_TYPE_SKRILL          = 5;   // Not In use
    const PAYMENT_METHOD_TYPE_SECONDARY_CC    = 6;
    const PAYMENT_METHOD_TYPE_CREDIT_CARD_TMT = 8;  // No Longer Used
    const PAYMENT_METHOD_TYPE_CREDIT_CARD_T1  = 9;
    const PAYMENT_METHOD_TYPE_CREDIT_CARD_T1_SECONDARY = 10;
    const TYPE_PAYARC = 11; // refunds only


    public static $creditCards = [
        self::PAYMENT_METHOD_TYPE_CREDITCARD,
        self::PAYMENT_METHOD_TYPE_SECONDARY_CC,
        self::PAYMENT_METHOD_TYPE_CREDIT_CARD_TMT,  // No Longer Used
        self::PAYMENT_METHOD_TYPE_CREDIT_CARD_T1,
        self::PAYMENT_METHOD_TYPE_CREDIT_CARD_T1_SECONDARY
    ];

    protected $table = "payment_methods";
    public $timestamps = false;
    protected $fillable = [
        'userID',
        'primary',
        'deleted',
        'token',
        'cvv',
        'expMonth',
        'expYear',
        'firstname',
        'lastname',
        'bill_addr_id',
        'pay_method_type',
        'is_restricted',
        'is_deleted',
        'flag',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public static function getById($id, $userId = '')
    {
        $paymentMethod = self::where('id', $id);
        if (!empty($userId)) {
            $paymentMethod = $paymentMethod->where('userID', $userId);
        }

        $paymentMethod = $paymentMethod->first();

        return $paymentMethod;
    }

    public static function checkCardAlreadyExists($userId, $tokenEx)
    {
        $rec = PaymentMethod::where('userID', $userId)
            ->where('token', $tokenEx)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->first();
        return $rec;
    }

    public static function addSecondaryCard($userId, $isPrimary, $token, $billAddressId, $paymentMethodTypeId, $req, $isSubscription = 0)
    {
        $rec = new PaymentMethod();
        $rec->userID = $userId;
        $rec->primary = $isPrimary;
        $rec->pay_method_type = $paymentMethodTypeId;
        //
        $expiry_date = $req->expiry_date;
        $temp = explode("/", $expiry_date);

        $rec->token = $token;
        $rec->cvv = $req->cvv;
        $rec->firstname = $req->first_name;
        $rec->lastname = $req->last_name;
        $rec->expMonth = (!empty($temp[0]) ? $temp[0] : '');
        $rec->expYear = (!empty($temp[1]) ? $temp[1] : '');
        $rec->bill_addr_id = $billAddressId;
        $rec->is_subscription = $isSubscription;
        $rec->save();
        return $rec->id;
    }

    public static function addNewRec($userId, $isPrimary, $token, $billAddressId, $paymentMethodTypeId, $req)
    {
        $rec = PaymentMethod::where('userID', $userId)
            ->where('primary', $isPrimary)
            ->where('pay_method_type', $paymentMethodTypeId)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->first();

        if (empty($rec)) {
            $rec = new PaymentMethod();
            $rec->userID = $userId;
            $rec->primary = $isPrimary;
            $rec->pay_method_type = $paymentMethodTypeId;
        }
        if ($req != null) {
            //
            $expiry_date = $req->expiry_date;
            $temp = explode("/", $expiry_date);

            if ($token != null) {
                $rec->token = $token;
            }
            if (isset($req->encrypt_cvv)) {
                if ($req->encrypt_cvv != $req->cvv) {
                    $rec->cvv = $req->cvv;
                }
            } else {
                $rec->cvv = $req->cvv;
            }

            $rec->firstname = $req->first_name;
            $rec->lastname = $req->last_name;
            $rec->expMonth = (!empty($temp[0]) ? $temp[0] : '');
            $rec->expYear = (!empty($temp[1]) ? $temp[1] : '');
        }
        $rec->bill_addr_id = $billAddressId;
        $rec->save();
        return $rec->id;
    }

    public static function getAllRec($userId, $paymentMethodTypeId = null)
    {
        $payments =  PaymentMethod::where('userID', $userId);
        if ($paymentMethodTypeId) {
            $payments->where('pay_method_type', $paymentMethodTypeId)
                ->where('is_restricted', '=', 0);
        }

        return $payments->where(function ($query) {
            $query->where('is_deleted', '=', 0)
                ->orWhereNull('is_deleted');
        })
            ->get();
    }

    public static function getUserPaymentRecords($userId)
    {
        return PaymentMethod::where('userID', $userId)->get();
    }

    public static function getRecByCountry($userId)
    {
        $userCountry = \App\Address::getRec($userId, \App\Address::TYPE_BILLING);
        if (empty($userCountry)) {
            $userCountry = \App\Address::getRec($userId, \App\Address::TYPE_REGISTRATION);
        }
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;
        if (\App\Helper::checkTMTAllowPayment($userCountry->countrycode, $userId) > 0) {
            $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
        }
        return PaymentMethod::where('userID', $userId)
            ->where('pay_method_type', $paymentMethodType)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->get();
    }

    public static function getRecAllPaymentMethod($userId, $isPrimary)
    {
        return PaymentMethod::where('userID', $userId)
            ->where('primary', $isPrimary)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->first();
    }

    public static function getRec($userId, $isPrimary, $paymentMethodTypeId)
    {

        $paymentMethod = PaymentMethod::where('userID', $userId)
            ->where('primary', $isPrimary)
            ->where('pay_method_type', $paymentMethodTypeId)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->first();

        if (empty($paymentMethod)) {
            return PaymentMethod::where('userID', $userId)
                ->where('primary', $isPrimary)
                ->where('is_restricted', '=', 0)
                ->where(function ($query) {
                    $query->where('is_deleted', '=', 0)
                        ->orWhereNull('is_deleted');
                })
                ->first();
        } else {
            return $paymentMethod;
        }
    }

    public static function deleteSecondary($userId, $paymentMethodTypeId)
    {
        PaymentMethod::where('userID', $userId)
            ->where('primary', 0)
            ->where('pay_method_type', $paymentMethodTypeId)
            ->delete();
    }

    public static function generateTokenEx($cardNo)
    {
        $t = new \tokenexAPI();
        $res = $t->tokenize('Tokenize', $cardNo);
        $res = json_decode($res);
        //
        if ($res->Success) {
            $error = 0;
            $token = $res->Token;
            $msg = null;
        } else {
            $error = 1;
            $token = null;
            $msg = $res->Error;
        }

        $result = array();
        $result['error'] = $error;
        $result['token'] = $token;
        $result['msg'] = $msg;

        return $result;
    }

    public static function addNewCustomPaymentMethod($req)
    {
        return self::create($req);
    }

    public static function getFormatedCardNo($token)
    {
        if (\utill::isNullOrEmpty($token) || strlen($token) < self::MINIMUM_TOKEN_LENGTH)
            return "";
        $count = strlen($token);
        $temp1 = substr($token, 0, 6);
        $temp2 = substr($token, -4);
        $xCount = $count - 10;
        return $temp1 . str_repeat('x', $xCount) . $temp2;
    }

    public static function getFormatedCVV($cvv)
    {
        if (\utill::isNullOrEmpty($cvv))
            return "";
        $count = strlen($cvv);
        return str_repeat('x', $count);
    }

    public static function getUserPaymentMethods($userId, $isCard = '')
    {

        $paymentMethods = PaymentMethod::where('userID', $userId)
            ->whereNotNull('pay_method_type')
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            });
        if (!empty($isCard)) {
            $paymentMethods = $paymentMethods->whereIn('pay_method_type', [PaymentMethodType::TYPE_CREDIT_CARD, PaymentMethodType::TYPE_SECONDARY_CC, PaymentMethodType::TYPE_T1_PAYMENTS, PaymentMethodType::TYPE_T1_PAYMENTS_SECONDARY_CC]);
        }
        $paymentMethods = $paymentMethods->orderBy('id', 'asc')
            ->get();

        return $paymentMethods;
    }

    public static function getByUserPayMethodType($userId, $paymentMethodTypeId)
    {
        return PaymentMethod::where('userID', $userId)
            ->where('pay_method_type', $paymentMethodTypeId)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->first();
    }

    public static function checkSubscriptionCardAdded($userId)
    {
        return PaymentMethod::where('userID', $userId)
            ->where('primary', 0)
            ->where('is_subscription', 1)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->where('pay_method_type', PaymentMethodType::TYPE_SECONDARY_CC)
            ->count();
    }

    public static function markAsDeleted($paymentMethodId)
    {
        self::where('id', $paymentMethodId)
            ->update(['is_deleted' => 1]);
    }

    public static function getPaymentMethodIdOfPayMethodTypeAdmin($userId)
    {
        $paymentMethod = PaymentMethod::where('userID', $userId)
            ->where('pay_method_type', PaymentMethodType::TYPE_ADMIN)
            ->first();

        if (!$paymentMethod) {
            $paymentMethod = self::create([
                'userID' => $userId,
                'pay_method_type' => PaymentMethodType::TYPE_ADMIN
            ]);

            return $paymentMethod->id;
        }

        return $paymentMethod->id;
    }

    

    /**
     * @param $userId
     * @param $data
     * @return bool
     */
    public function updatePaymentRecords($userId, $data)
    {
        if (!$payment =  PaymentMethod::query()->where('userId', $userId)->first()) {
            return false;
        }

        $hasChange = false;
        foreach (array_keys($data) as $index => $key) {
            if (!array_key_exists($key, $payment->toArray())) {
                return false;
            }

            if (strcasecmp($data[$key], $payment[$key]) !== 0) {
                $hasChange = true;
                $payment->$key = $data[$key];
            }
        }

        if (!$hasChange) {
            return false;
        }

        return $payment->save();
    }

    public function restrict()
    {
        $this->update([
            'is_restricted' => 1,
            'is_deleted' => 1,
            'deleted_at' => now()
        ]);
    }
}
