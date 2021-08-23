<?php

namespace App\Http\Controllers;

use App\IDecide;
use App\PaymentMethod;
use App\PaymentMethodType;
use App\SaveOn;
use App\Services\SubscriptionGroupService;
use App\SubscriptionHistory;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Session;
use Validator;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class SubscriptionController extends Controller
{
    private $subscriptionGroupService;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionGroupService $subscriptionGroupService
     */
    public function __construct(SubscriptionGroupService $subscriptionGroupService)
    {
        $this->subscriptionGroupService = $subscriptionGroupService;

        $this->middleware('auth.affiliate', ['except' => [
            'dlgSubscriptionReactivateSuspendedUser',
            'reactivateSubscriptionSuspendedUser',
            'checkCouponCode',
            'checkCouponCodeforSuspendedUser',
            'addNewCardSubscriptionSuspendedUserReactivate',
        ]]);
        $this->middleware('auth');
    }

    public function index()
    {
        $d = array();
        session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
        $user = User::getById(Auth::user()->id);
        $subscriptionPlan = SubscriptionHistory::getCurrentSubscriptionPlan(Auth::user()->id);
        $d['current_plan'] = $subscriptionPlan;
        $d['next_subscription_date'] = $subscriptionPlan ? (string)$user->next_subscription_date : '';
        $paymentMethodId = $user->subscription_payment_method_id;
        $d['gflag'] = $user->gflag;
        $pMDrop = $this->getPaymentMethods($paymentMethodId);
        $d['payment_method'] = $pMDrop;
        $subscriptionCardAdded = PaymentMethod::checkSubscriptionCardAdded(Auth::user()->id);
        $d['subscription_card_added'] = $subscriptionCardAdded;
        return view('affiliate.subscription.index')->with($d);
    }

    public function saveSubscription()
    {
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }

        $subscriptionPlan = SubscriptionHistory::getCurrentSubscriptionPlan(Auth::user()->id);
        if (!$subscriptionPlan) {
            return response()->json(['error' => 1, 'msg' => 'Error']);
        }
        $req = request();
        $nextSubscriptionDate = \Carbon\Carbon::parse($req['next_subscription_date']);
        $date = date('d', strtotime($req['next_subscription_date']));
        if ($date > 25) {
            return response()->json(['error' => 1, 'msg' => 'Date should less than or equal to 25']);
        }
        $nextSubscriptionDate = date("Y-m-d", strtotime($req['next_subscription_date']));
        if ($nextSubscriptionDate <= date("Y-m-d")) {
            return response()->json(['error' => 1, 'msg' => 'Next subscription date cannot be less than or equal to current date']);
        }
        $original_subscription_date = date("Y-m-d", strtotime(Auth::user()->original_subscription_date));
        if ($original_subscription_date > $nextSubscriptionDate) {
            return response()->json(['error' => 1, 'msg' => 'Invalid Date']);
        }
        $now = \Carbon\Carbon::now();
        $diff = $now->diffInMonths($nextSubscriptionDate, false);
        if ($diff >= 2) {
            return response()->json(['error' => 1, 'msg' => 'Invalid Date']);
        }
        SubscriptionHistory::updateSubscription(Auth::user()->id, $req->except('_token'));
        return response()->json(['error' => 0, 'msg' => 'Updated']);
    }

    public function getGracePeriod()
    {
        $req = request();

        $nextSubscriptionDate = \Carbon\Carbon::parse($req['next_subscription_date']);
        $originalSubscriptionDate = \Carbon\Carbon::parse(Auth::user()->original_subscription_date);

        $diff = $originalSubscriptionDate->diffInDays($nextSubscriptionDate, false);

        if ($diff > 7) {
            return response()->json([
                'alert' => 1,
                'title' => 'Grace period passed',
                'text' => 'Setting this date would be outside of the grace period and your account would be put on hold',
                'type' => 'warning'
            ]);
        }

        return response()->json(['alert' => 0]);
    }

    public function dlgAddNewCard()
    {
        session_start();
        $d['countries'] = \App\Country::getAll();
        $d['sessionId'] = session_id();
        return view('affiliate.subscription.dlg_add_new_card')->with($d);
    }

    public function addNewCard()
    {
        $vali = $this->validateAddCard();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);
        if (!$subscriptionProduct) {
            return response()->json(['error' => 1, 'msg' => "Subscription product not found"]);
        }
        $payInCard = $subscriptionProduct->price;
        $req = request();

        $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;

        $tokenExResult = \App\PaymentMethod::generateTokenEx($req->number);
        if ($tokenExResult['error'] == 1) {
            return response()->json(['error' => 1, 'msg' => "Invalid card number<br/>" . $tokenExResult['msg']]);
        }

        $tokenEx = $tokenExResult['token'];
        $cardAlreadyExists = PaymentMethod::checkCardAlreadyExists(Auth::user()->id, $tokenEx);
        if ($cardAlreadyExists) {
            return response()->json(['error' => 1, 'msg' => "Card already exists"]);
        }
        $addressId = \App\Address::addSecondaryAddress(Auth::user()->id, \App\Address::TYPE_BILLING, $req);
        $paymentMethodId = \App\PaymentMethod::addNewRec(Auth::user()->id, 1, $tokenEx, $addressId, $paymentMethodType, $req);
        return response()->json([
            'error' => 0,
            'msg' => 'New card added',
            'payment_method_id' => $paymentMethodId,
            'card_name' => 'Credit Card - ' . PaymentMethod::getFormatedCardNo($tokenEx)
        ]);
    }

    private function validateAddCard()
    {
        $req = request();
        $validator = Validator::make($req->all(), [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'number' => 'required',
            'cvv' => 'required|max:4',
            'expiry_date' => 'required|size:7',
            'address1' => 'required|max:255',
            'countrycode' => 'required|max:10',
            'city'=> 'required|max:255',
//            'stateprov' => 'required|max:50',
            'stateprov' => 'max:50',
            'postalcode' => 'required|max:10',
            'terms' => 'required',
        ], [
            'first_name.required' => 'First name on card is required',
            'first_name.max' => 'First name cannot exceed 50 charactors',
            'last_name.required' => 'Last name on card is required',
            'last_name.max' => 'Last name cannot exceed 50 charactors',
            'number.required' => 'Card number is required',
            'cvv.required' => 'CVV is required',
            'cvv.max' => 'CVV cannot exceed 4 charactors',
            'expiry_date.required' => 'Expiration date is required',
            'expiry_date.size' => 'Invalid expiration date format',
            'address1.required' => 'Address is required',
            'address1.max' => 'Address exceed the limit',
            'countrycode.required' => 'Country is required',
            'countrycode.max' => 'Country exceed the limit',
            'city.required' => 'City / Town is required',
            'city.max' => 'City / Town exceed the limit',
//                    'stateprov.required' => 'State / Province is required',
            'stateprov.max' => 'State / Province exceed the limit',
            'postalcode.required' => 'Postal code is required',
            'postalcode.max' => 'Postal code exceed the limit',
            'terms.required' => 'Agree to terms and conditions',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
            // validate expiry date
            $expiryDate = trim(str_replace(' ', '', $req->input('expiry_date')));
            $expireDateParts = explode('/', $expiryDate);

            if (!isset($expireDateParts[0]) || !isset($expireDateParts[1]) || strlen($expireDateParts[0]) != 2 || strlen($expireDateParts[1]) != 4) {
                $valid = 0;
                $msg = 'Invalid Expiry date';
            } else if (!preg_match('/^\d+$/', $expireDateParts[0]) || (!preg_match('/^\d+$/', $expireDateParts[1]))) {
                $valid = 0;
                $msg = 'Invalid Expiry date';
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    private function validateRec()
    {
        $req = request();
        if ($req['subscription_payment_method_id'] == 0) {
            $req['subscription_payment_method_id'] = '';
        }

        $validator = Validator::make($req->all(), [
            'next_subscription_date' => 'required|date',
            'subscription_payment_method_id' => 'required'
        ], [
            'next_subscription_date.required' => 'Subscription date is required',
            'subscription_payment_method_id.required' => 'Payment method required'
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function dlgSubscriptionReactivateSuspendedUser()
    {
        session_start();
        $d['sessionId'] = session_id();
        $d['payment_method'] = $this->getPaymentMethods();
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);
        $d['subscription_amount'] = $subscriptionProduct->price;
        $d['subscription_fee'] = \App\Product::getById(\App\Product::ID_REACTIVATION_PRODUCT);
        $d['total'] = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $d['coupon_code'] = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        return view('affiliate.subscription.dlg_subscription_reactivate_suspended_account')->with($d);
    }

    public function dlgSubscriptionReactivate()
    {
        session_start();

        //$d['countries'] = \App\Country::getAll();
        $d['sessionId'] = session_id();
        $d['payment_method'] = $this->getPaymentMethods();

        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);
        $d['subscription_amount'] = $subscriptionProduct->price;
        $d['total'] = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $d['subscription_fee'] = \App\Product::getById(\App\Product::ID_REACTIVATION_PRODUCT);
        $d['coupon_code'] = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';

        return view('affiliate.subscription.dlg_subscription_reactivate')->with($d);
    }


    public function reactivateSubscription()
    {
        $request = request();
        session_start();
        $subscriptionPaymentMethodId = $request->subscription_payment_method_id;
        if ($subscriptionPaymentMethodId == 'add_new_card') {
            $d['countries'] = DB::table('country')->orderBy('country', 'asc')->get();
            $d['sessionId'] = session_id();
            $v = (string)view('affiliate.subscription.dlg_reactivate_subscription_add_new_card')->with($d);
            return response()->json(['error' => 0, 'v' => $v, 'act' => 'add_new_card']);
        } else if (is_numeric($subscriptionPaymentMethodId)) {
            $paymentMethod = PaymentMethod::find($subscriptionPaymentMethodId);
            if (!$paymentMethod) {
                return response()->json(['error' => 0, 'msg' => 'Invalid Payment Method']);
            }
            $paymethodType = $paymentMethod->pay_method_type;
            if ($paymethodType == PaymentMethodType::TYPE_E_WALET) {
                return $this->reactivateByEwallet($subscriptionPaymentMethodId);
            } else if ($paymethodType == PaymentMethodType::TYPE_SECONDARY_CC || $paymethodType == PaymentMethodType::TYPE_CREDIT_CARD || $paymethodType == PaymentMethodType::TYPE_T1_PAYMENTS || $paymethodType == PaymentMethodType::TYPE_T1_PAYMENTS_SECONDARY_CC || $paymethodType == PaymentMethodType::TYPE_PAYARC) {
                return $this->reactivateByCard($subscriptionPaymentMethodId);
            }
        }
    }


    public function reactivateSubscriptionSuspendedUser()
    {
        $request = request();
        $userId = Auth::user()->id;
        $subscriptionPaymentMethodId = $request->subscription_payment_method_id;
        if ($subscriptionPaymentMethodId == 'add_new_card') {
            $d['countries'] = DB::table('country')->orderBy('country', 'asc')->get();
            $d['sessionId'] = session_id();
            $v = (string)view('affiliate.subscription.dlg_reactivate_subscription_suspended_user_add_new_card')->with($d);
            return response()->json(['error' => 0, 'v' => $v, 'act' => 'add_new_card']);
        } else if (is_numeric($subscriptionPaymentMethodId)) {
            $paymentMethod = PaymentMethod::find($subscriptionPaymentMethodId);
            if (!$paymentMethod) {
                return response()->json(['error' => 0, 'msg' => 'Invalid Payment Method']);
            }
            $paymethodType = $paymentMethod->pay_method_type;
            if ($paymethodType == PaymentMethodType::TYPE_E_WALET) {
                return $this->reactivateByEwalletForSuspendedUser($subscriptionPaymentMethodId, $userId);
            } else if ($paymethodType == PaymentMethodType::TYPE_SECONDARY_CC || $paymethodType == PaymentMethodType::TYPE_CREDIT_CARD || $paymethodType == PaymentMethodType::TYPE_T1_PAYMENTS || $paymethodType == PaymentMethodType::TYPE_T1_PAYMENTS_SECONDARY_CC || $paymethodType == PaymentMethodType::TYPE_PAYARC) {
                return $this->reactivateSuspendedUserByCard($subscriptionPaymentMethodId, $userId);
            }
        } else if (isset($request->coupon)) {
            return $this->reactivateSuspendedUserByCouponCode($request->coupon, $userId);
        } else {
            return response()->json(['error' => 0, 'msg' => 'Invalid Payment Method']);
        }
    }

    private function reactivateSuspendedUserByCouponCode($discountCode, $userId)
    {
        $user = \App\User::select('*')->where('id', $userId)->first();
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($user->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;

        $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);

        if ($discount == 0) {
            return response()->json(['error' => 1, 'msg' => "Invalid discount code"]);
        } else if ($subscriptionAmount > $discount) {
            return response()->json(['error' => 1, 'msg' => "Invalid discount code"]);
        }

        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($user->id);

        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;

        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;

        // create new order
        $orderId = \App\Order::addNew($userId, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, null, null, null, null, $discountCode);
        // create new order item
        \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
        //

        if (!empty($discountCode)) {
            \App\DiscountCoupon::markAsUsed($user->id, $discountCode, "code", $orderId);
        }

        SaveOn::enableUser($user->current_product_id, $user->distid, $user->id);
        IDecide::enableUser($user->id);
        \App\BoomerangInv::addToInventory($user->id, $subscriptionProduct->num_boomerangs);
        \App\User::updateUserSitesStatus($user->id, 0, 0, 0);
        $attemptDate = date('Y-m-d');
        $attemptCount = 1;
        $status = '1';
        $productId = $subscriptionProduct->id;
        $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
        $response = 'Reactivate subscription';

        \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, null, $nextSubscriptionDate, $response, 1);
        \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);
        \App\User::updateAccountStatusByUserId($userId, \App\User::ACC_STATUS_APPROVED);
        session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
        $v = (string)view('affiliate.subscription.dlg_subscription_reactive_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function checkCouponCodeforSuspendedUser()
    {
        $request = request();
        $userId = Auth::user()->id;
        $discountCode = $request->coupon;
        $d['coupon_code'] = $discountCode;
        $d['payment_method'] = $this->getPaymentMethods();
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($userId);
        $d['subscription_amount'] = $subscriptionProduct->price;
        $d['total'] = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $d['subscription_fee'] = \App\Product::getById(\App\Product::ID_REACTIVATION_PRODUCT);
        $v = (string)view('affiliate.subscription.dlg_subscription_reactivate_suspended_account')->with($d);
        $discount = 0;
        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                $d['coupon_code'] = "";
                $v = (string)view('affiliate.subscription.dlg_subscription_reactivate_suspended_account')->with($d);
                return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v]);
            }
        } else {
            $d['coupon_code'] = "";
            $v = (string)view('affiliate.subscription.dlg_subscription_reactivate_suspended_account')->with($d);
            return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v]);
        }
        session()->put('reactivateSubscriptionCouponCode', $discountCode);

        $total = ($subscriptionProduct->price + \App\Helper::getReactivationFee()) - $discount;
        if ($total < 0) {
            $total = 0;
        }
        $d['total'] = $total;

        session()->put('reactivateSubscriptionTotal', $total);

        $v = (string)view('affiliate.subscription.dlg_subscription_reactivate_suspended_account')->with($d);
        return response()->json(['error' => 0, 'msg' => 'Valid discount code', 'v' => $v, 'total' => $total]);
    }

    public function checkCouponCode()
    {
        $request = request();
        $userId = Auth::user()->id;
        $discountCode = $request->coupon;

        $d['coupon_code'] = $discountCode;
        $d['payment_method'] = $this->getPaymentMethods();

        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($userId);
        $d['subscription_amount'] = $subscriptionProduct->price;
        $d['subscription_fee'] = \App\Product::getById(\App\Product::ID_REACTIVATION_PRODUCT);
        $d['total'] = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $v = (string)view('affiliate.subscription.dlg_subscription_reactivate')->with($d);

        $discount = 0;
        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v]);
            }
        } else {
            return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v]);
        }

        session()->put('reactivateSubscriptionCouponCode', $discountCode);

        $total = ($subscriptionProduct->price + \App\Helper::getReactivationFee()) - $discount;
        if ($total < 0) {
            $total = 0;
        }
        $d['total'] = $total;

        session()->put('reactivateSubscriptionTotal', $total);

        $v = (string)view('affiliate.subscription.dlg_subscription_reactivate')->with($d);
        return response()->json(['error' => 0, 'msg' => 'Valid discount code', 'v' => $v, 'total' => $total]);
    }

    public function addNewCardSubscriptionSuspendedUserReactivate()
    {
        $user = \App\User::getById(Auth::user()->id);
        $vali = $this->validateAddCard();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $user = \App\User::getById(Auth::user()->id);
        if (empty($user)) {
            return response()->json(['error' => 1, 'msg' => "User couldn't found"]);
        }
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($user->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $discountCode = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;
        $req = request();
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;
        if (\App\Helper::checkTMTAllowPayment($req->countrycode,$user->id) > 0) {
            $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
        }
        if ($paymentMethodType != \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
            // kount
            $kount = new \Kount();
            $uniqueId = md5($user->email . time());
            $kountResponse = $kount->RequestInquiry($req, $amount, $user->email, $user->phonenumber, $subscriptionProduct, $uniqueId, $req->sessionId);
            //
            if (!$kountResponse['success']) {
                return response()->json(['error' => 1, 'msg' => "Payment Failed:<br/>" . $kountResponse['message']]);
            }
        }
        $tokenExResult = \App\PaymentMethod::generateTokenEx($req->number);
        if ($tokenExResult['error'] == 1) {
            return response()->json(['error' => 1, 'msg' => "Invalid card number<br/>" . $tokenExResult['msg']]);
        }
        $tokenEx = $tokenExResult['token'];
        $cardAlreadyExists = PaymentMethod::checkCardAlreadyExists($user->id, $tokenEx);

        if ($cardAlreadyExists) {
            return response()->json(['error' => 1, 'msg' => "Card already exists"]);
        }

        $expiry_date = $req->expiry_date;
        $temp = explode("/", $expiry_date);
        $nmiResult = \App\NMIGateway::processPayment($req->number, $req->first_name, $req->last_name, $temp[0], $temp[1], $req->cvv, $amount, $req->address1, $req->city, $req->stateprov, $req->postalcode, $req->countrycode, $paymentMethodType);
        $sessionId = $req->sessionId;
        if ($nmiResult['error'] == 1) {
            if ($paymentMethodType != \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
                $kount->RequestUpdate($sessionId, $kountResponse['transaction_id'], 'D');
            }
            return response()->json(['error' => 1, 'msg' => "Payment Failed:<br/>" . $nmiResult['msg']]);
        }
        if ($paymentMethodType != \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
            $kount->RequestUpdate($sessionId, $kountResponse['transaction_id'], 'A');
        }
        $authorization = $nmiResult['authorization'];
        //check address already exists
        $hasPrimaryAddress = \App\Address::getRec($user->id, \App\Address::TYPE_BILLING, 1);
        if (empty($hasPrimaryAddress)) {
            $addressId = \App\Address::addNewRecSecondaryAddress($user->id, \App\Address::TYPE_BILLING, 1, $req);
        } else {
            $addressId = \App\Address::addNewRecSecondaryAddress($user->id, \App\Address::TYPE_BILLING, 0, $req);
        }

        $hasPaymentMethod = \App\PaymentMethod::getAllRec($user->id, $paymentMethodType);
        if (empty($hasPaymentMethod)) {
            $paymentMethodId = \App\PaymentMethod::addSecondaryCard($user->id, 1, $tokenEx, $addressId, $paymentMethodType, $req);
        } else {
            $paymentMethodId = \App\PaymentMethod::addSecondaryCard($user->id, 0, $tokenEx, $addressId, $paymentMethodType, $req);
        }


        // create new order
        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;
        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;

        $orderId = \App\Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethodId, null, null, null, $discountCode);
        $OrderItem = \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
        if (!empty($discountCode)) {
            \App\DiscountCoupon::markAsUsed($user, $discountCode, "code", $orderId);
        }
        SaveOn::enableUser($user->current_product_id, $user->distid, $user->id);
        IDecide::enableUser($user->id);

        \App\BoomerangInv::addToInventory($user->id, $subscriptionProduct->num_boomerangs);
        \App\User::updateUserSitesStatus($user->id, 0, 1, 0);

        $userId = $user->id;
        $attemptDate = date('Y-m-d');
        $attemptCount = 1;
        $status = 1;
        $productId = $subscriptionProduct->id;
        $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
        $response = 'Reactivate subscription';

        \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, $paymentMethodId, $nextSubscriptionDate, $response, 1);
        \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);
        \App\User::updateAccountStatusByUserId($userId, \App\User::ACC_STATUS_APPROVED);
        session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
        $v = (string)view('affiliate.subscription.dlg_subscription_reactive_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }


    public function addNewCardSubscriptionReactivate()
    {
        $vali = $this->validateAddCard();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $discountCode = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;

        $req = request();
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;
        if (\App\Helper::checkTMTAllowPayment($req->countrycode,Auth::user()->id) > 0) {
            $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
        }
        if ($paymentMethodType != \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
            // kount
            $kount = new \Kount();
            $uniqueId = md5(Auth::user()->email . time());
            $kountResponse = $kount->RequestInquiry($req, $amount, Auth::user()->email, Auth::user()->phonenumber, $subscriptionProduct, $uniqueId, $req->sessionId);
            //
            if (!$kountResponse['success']) {
                return response()->json(['error' => 1, 'msg' => "Payment Failed:<br/>" . $kountResponse['message']]);
            }
        }
        $tokenExResult = \App\PaymentMethod::generateTokenEx($req->number);
        if ($tokenExResult['error'] == 1) {
            return response()->json(['error' => 1, 'msg' => "Invalid card number<br/>" . $tokenExResult['msg']]);
        }

        $tokenEx = $tokenExResult['token'];
        $cardAlreadyExists = PaymentMethod::checkCardAlreadyExists(Auth::user()->id, $tokenEx);

        if ($cardAlreadyExists) {
            return response()->json(['error' => 1, 'msg' => "Card already exists"]);
        }

        $expiry_date = $req->expiry_date;
        $temp = explode("/", $expiry_date);
        $nmiResult = \App\NMIGateway::processPayment($req->number, $req->first_name, $req->last_name, $temp[0], $temp[1], $req->cvv, $amount, $req->address1, $req->city, $req->stateprov, $req->postalcode, $req->countrycode, $paymentMethodType);
        $sessionId = $req->sessionId;
        if ($nmiResult['error'] == 1) {
            if ($paymentMethodType != \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
                $kount->RequestUpdate($sessionId, $kountResponse['transaction_id'], 'D');
            }
            return response()->json(['error' => 1, 'msg' => "Payment Failed:<br/>" . $nmiResult['msg']]);
        }
        if ($paymentMethodType != \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
            $kount->RequestUpdate($sessionId, $kountResponse['transaction_id'], 'A');
        }
        $authorization = $nmiResult['authorization'];
        //check address already exists
        $hasPrimaryAddress = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_BILLING, 1);
        if (empty($hasPrimaryAddress)) {
            $addressId = \App\Address::addNewRecSecondaryAddress(Auth::user()->id, \App\Address::TYPE_BILLING, 1, $req);
        } else {
            $addressId = \App\Address::addNewRecSecondaryAddress(Auth::user()->id, \App\Address::TYPE_BILLING, 0, $req);
        }

        $hasPaymentMethod = \App\PaymentMethod::getAllRec(Auth::user()->id, $paymentMethodType);
        if (empty($hasPaymentMethod)) {
            $paymentMethodId = \App\PaymentMethod::addSecondaryCard(Auth::user()->id, 1, $tokenEx, $addressId, $paymentMethodType, $req);
        } else {
            $paymentMethodId = \App\PaymentMethod::addSecondaryCard(Auth::user()->id, 0, $tokenEx, $addressId, $paymentMethodType, $req);
        }
        // create new order
        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;
        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;

        $orderId = \App\Order::addNew(Auth::user()->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethodId, null, null, null, $discountCode);
        // create new order item
        $OrderItem = \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
        //

        if (!empty($discountCode)) {
            \App\DiscountCoupon::markAsUsed(Auth::user()->id, $discountCode, "code", $orderId);
        }

        SaveOn::enableUser(Auth::user()->current_product_id, Auth::user()->distid, Auth::user()->id);
        IDecide::enableUser(Auth::user()->id);

        \App\BoomerangInv::addToInventory(Auth::user()->id, $subscriptionProduct->num_boomerangs);
        \App\User::updateUserSitesStatus(Auth::user()->id, 0, 1, 0);

        $userId = Auth::user()->id;
        $attemptDate = date('Y-m-d');
        $attemptCount = 1;
        $status = 1;
        $productId = $subscriptionProduct->id;
        $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
        $response = 'Reactivate subscription';

        \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, $paymentMethodId, $nextSubscriptionDate, $response, 1);
        \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);

        session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
        return response()->json(['error' => 0, 'msg' => 'Account reactivated', 'nd' => $nextSubscriptionDate]);
    }


    private function createEwalletIfNotPresent($userId)
    {
        $ewalletExists = PaymentMethod::where('userID', '=', $userId)
                                        ->where('pay_method_type', '=', PaymentMethodType::TYPE_E_WALET)
                                        ->where(function ($q) {
                                            $q->where('is_deleted', '=', 0)->orWhereNull('is_deleted');
                                        })
                                        ->exists();
        if (!$ewalletExists) {
            PaymentMethod::addNewCustomPaymentMethod([
                'userID' => $userId,
                'created_at' => \utill::getCurrentDateTime(),
                'updated_at' => \utill::getCurrentDateTime(),
                'pay_method_type' => PaymentMethodType::TYPE_E_WALET
            ]);
        }
    }

    private function getPaymentMethods($selectedPaymentMethodId = null)
    {
        $userId = Auth::id();
        $this->createEwalletIfNotPresent($userId);
        $paymentMethods = PaymentMethod::getAllRec($userId);
        $pMDrop = '';

        $ignoredPaymentMethods = [
            PaymentMethodType::TYPE_ADMIN,
            PaymentMethodType::TYPE_BITPAY,
            PaymentMethodType::TYPE_SKRILL,
            PaymentMethodType::TYPE_COUPON_CODE
        ];

        foreach ($paymentMethods as $p) {
            if ($p->is_deleted == true || $p->is_restricted == true || in_array($p->pay_method_type, $ignoredPaymentMethods)) {
                continue;
            }

            if ($p->pay_method_type == PaymentMethodType::TYPE_E_WALET) {
                $paymentMethodName = 'E-WALLET';
            } else {
                if (empty($p->token)) {
                    continue;
                }

                $paymentMethodName = 'Credit Card - ' . PaymentMethod::getFormatedCardNo($p->token);
            }

            if(!empty($paymentMethodName)){
                $selected = $selectedPaymentMethodId == $p->id ? ' selected ' : '';
                $pMDrop .= '<option value="' . $p->id . '"' . $selected . '>' . $paymentMethodName . '</option>';
            }
        }
        return $pMDrop;
    }

    private function reactivateByCard($subscriptionPaymentMethodId)
    {

        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $discountCode = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;

        $paymentMethod = \App\PaymentMethod::select('*')
            ->where('id', $subscriptionPaymentMethodId)
            ->where('userID', Auth::user()->id)
            ->first();

        /*echo $subscriptionPaymentMethodId;*/

        if (empty($paymentMethod)) {
            return response()->json(['error' => 1, 'msg' => "Invalid payment methods"]);
        }

        $billingAddress = \App\Address::find($paymentMethod->bill_addr_id);
        if (empty($billingAddress)) {
            return response()->json(['error' => 1, 'msg' => "Invalid billing address"]);
        }

        //detokenize
        $tokenEx = new \tokenexAPI();
        $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
        $tokenRes = $tokenRes['response'];
        if (!$tokenRes->Success) {
            return response()->json(['error' => 1, 'msg' => "TokenEx Error : " . $tokenRes->Error]);
        }
        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;

        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;

        //Only use PayArc or T1
        if($paymentMethod->pay_method_type == 11){
            $nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode, 11);
        } else {
            //force T1
            //$nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode, $paymentMethod->pay_method_type);
            $nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode, 9);
        }

        if ($nmiResult['error'] == 1) {
            return response()->json(['error' => 1, 'msg' => "Payment Failed:<br/>" . $nmiResult['msg']]);
        } else {
            // place order
            $authorization = $nmiResult['authorization'];
            // create new order
            $orderId = \App\Order::addNew(Auth::user()->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethod->id, null, null, null, $discountCode);
            // create new order item
            \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
            //
            if (!empty($discountCode)) {
                \App\DiscountCoupon::markAsUsed(Auth::user()->id, $discountCode, "code", $orderId);
            }
            //

            SaveOn::enableUser(Auth::user()->current_product_id, Auth::user()->distid, Auth::user()->id);
            IDecide::enableUser(Auth::user()->id);

            \App\BoomerangInv::addToInventory(Auth::user()->id, $subscriptionProduct->num_boomerangs);
            \App\User::updateUserSitesStatus(Auth::user()->id, 0, 0, 0);

            $userId = Auth::user()->id;
            $attemptDate = date('Y-m-d');
            $attemptCount = 1;
            $status = '1';
            $productId = $subscriptionProduct->id;
            $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
            $response = 'Reactivate subscription';

            \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, $subscriptionPaymentMethodId, $nextSubscriptionDate, $response, 1);
            \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);

            session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
            return response()->json(['error' => 0, 'msg' => 'Account reactivated', 'act' => 'card', 'nd' => $nextSubscriptionDate]);
        }
    }


    private function reactivateSuspendedUserByCard($subscriptionPaymentMethodId, $userId)
    {
        $user = \App\User::getById($userId);
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($user->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $discountCode = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;

        $paymentMethod = \App\PaymentMethod::select('*')
            ->where('id', $subscriptionPaymentMethodId)
            ->where('userID', $user->id)
            ->first();

        /*echo $subscriptionPaymentMethodId;*/

        if (empty($paymentMethod)) {
            return response()->json(['error' => 1, 'msg' => "Invalid payment methods"]);
        }

        $billingAddress = \App\Address::find($paymentMethod->bill_addr_id);
        if (empty($billingAddress)) {
            return response()->json(['error' => 1, 'msg' => "Invalid billing address"]);
        }

        //detokenize
        $tokenEx = new \tokenexAPI();
        $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
        $tokenRes = $tokenRes['response'];
        if (!$tokenRes->Success) {
            return response()->json(['error' => 1, 'msg' => "TokenEx Error : " . $tokenRes->Error]);
        }

        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;
        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;


        //Only use PayArc or T1
        if($paymentMethod->pay_method_type == 11){
            $nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode, 11);
        } else {
            //force T1
            //$nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode, $paymentMethod->pay_method_type);
            $nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode, 9);
        }

        if ($nmiResult['error'] == 1) {
            return response()->json(['error' => 1, 'msg' => "Payment Failed:<br/>" . $nmiResult['msg']]);
        } else {
            // place order
            $authorization = $nmiResult['authorization'];
            // create new order
            $orderId = \App\Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethod->id, null, null, null, $discountCode);
            // create new order item
            \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
            //
            if (!empty($discountCode)) {
                \App\DiscountCoupon::markAsUsed($user->id, $discountCode, "code", $orderId);
            }
            //

            SaveOn::enableUser($user->current_product_id, $user->distid, $user->id);
            IDecide::enableUser($user->id);

            \App\BoomerangInv::addToInventory($user->id, $subscriptionProduct->num_boomerangs);
            \App\User::updateUserSitesStatus($user->id, 0, 0, 0);

            $userId = $user->id;
            $attemptDate = date('Y-m-d');
            $attemptCount = 1;
            $status = '1';
            $productId = $subscriptionProduct->id;
            $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
            $response = 'Reactivate subscription';

            \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, $subscriptionPaymentMethodId, $nextSubscriptionDate, $response, 1);
            \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);
            \App\User::updateAccountStatusByUserId($userId, \App\User::ACC_STATUS_APPROVED);
            session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
            $v = (string)view('affiliate.subscription.dlg_subscription_reactive_success');
            return response()->json(['error' => 0, 'v' => $v]);
        }
    }


    private function reactivateByEwalletForSuspendedUser($subscriptionPaymentMethodId, $userId)
    {
        $user = \App\User::select('*')->where('id', $userId)->first();
        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($user->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $discountCode = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;

        $checkEwalletBalance = \App\User::select('*')->where('id', $user->id)->first();

        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }

        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct($user->id);

        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;

        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;

        // create new order
        $orderId = \App\Order::addNew($userId, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, $subscriptionPaymentMethodId, null, null, null, $discountCode);
        // create new order item
        \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
        //

        \App\EwalletTransaction::addPurchase($user->id, \App\EwalletTransaction::REACTIVATE_SUBSCRIPTION, -$amount, $orderId);

        if (!empty($discountCode)) {
            \App\DiscountCoupon::markAsUsed($user->id, $discountCode, "code", $orderId);
        }

        SaveOn::enableUser($user->current_product_id, $user->distid, $user->id);
        IDecide::enableUser($user->id);
        \App\BoomerangInv::addToInventory($user->id, $subscriptionProduct->num_boomerangs);
        \App\User::updateUserSitesStatus($user->id, 0, 0, 0);
        $userId = $user->id;
        $attemptDate = date('Y-m-d');
        $attemptCount = 1;
        $status = '1';
        $productId = $subscriptionProduct->id;
        $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
        $response = 'Reactivate subscription';

        \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, $subscriptionPaymentMethodId, $nextSubscriptionDate, $response, 1);
        \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);
        \App\User::updateAccountStatusByUserId($userId, \App\User::ACC_STATUS_APPROVED);
        session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
        $v = (string)view('affiliate.subscription.dlg_subscription_reactive_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function reactivateByEwallet($subscriptionPaymentMethodId)
    {


        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);
        $subscriptionAmount = $subscriptionProduct->price + \App\Helper::getReactivationFee();

        $discountCode = session()->has('reactivateSubscriptionCouponCode') ? session('reactivateSubscriptionCouponCode') : '';
        $amount = session()->has('reactivateSubscriptionTotal') ? session('reactivateSubscriptionTotal') : $subscriptionAmount;

        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();


        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }

        $subscriptionProduct = SubscriptionHistory::getSubscriptionProduct(Auth::user()->id);

        $orderSubtotal = $subscriptionProduct->price + \App\Helper::getReactivationFee();
        $orderTotal = (string)$amount;
        $orderBV = $subscriptionProduct->bv;
        $orderQV = $subscriptionProduct->qv;
        $orderCV = $subscriptionProduct->cv;

        // create new order
        $orderId = \App\Order::addNew(Auth::user()->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, $subscriptionPaymentMethodId, null, null, null, $discountCode);
        // create new order item
        \App\OrderItem::addNew($orderId, $subscriptionProduct->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
        //

        \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::REACTIVATE_SUBSCRIPTION, -$amount, $orderId);

        if (!empty($discountCode)) {
            \App\DiscountCoupon::markAsUsed(Auth::user()->id, $discountCode, "code", $orderId);
        }

        SaveOn::enableUser(Auth::user()->current_product_id, Auth::user()->distid, Auth::user()->id);
        IDecide::enableUser(Auth::user()->id);

        \App\BoomerangInv::addToInventory(Auth::user()->id, $subscriptionProduct->num_boomerangs);
        \App\User::updateUserSitesStatus(Auth::user()->id, 0, 0, 0);

        $userId = Auth::user()->id;
        $attemptDate = date('Y-m-d');
        $attemptCount = 1;
        $status = '1';
        $productId = $subscriptionProduct->id;
        $nextSubscriptionDate = SubscriptionHistory::getNextSubscriptionDate();
        $response = 'Reactivate subscription';

        \App\SubscriptionHistory::UpdateSubscriptionHistoryOnly($userId, $attemptDate, $attemptCount, $status, $productId, $subscriptionPaymentMethodId, $nextSubscriptionDate, $response, 1);
        \App\User::updateNextSubscriptionDate($userId, $nextSubscriptionDate);

        session()->forget(['reactivateSubscriptionCouponCode', 'reactivateSubscriptionTotal']);
        return response()->json(['error' => 0, 'msg' => 'Account reactivated', 'act' => 'ewallet', 'nd' => $nextSubscriptionDate]);
    }

    /**
     * @param $subscriptionType
     * @param $userId
     * @return Factory|View
     */
    public function subscriptionUserDetails($subscriptionType, $userId)
    {
        $user = User::find($userId);

        if (!$user || $user->id !== Auth::user()->id) {
            abort(404);
        }

        $title = $this->subscriptionGroupService->getSubscriptionTitle($subscriptionType);

        $data = [
            'subscriptionType' => $subscriptionType,
            'user' => $user,
            'title' => $title
        ];

        return view('affiliate.dashboard.subscription_details')->with($data);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function ajaxSubscriptionUserDetails(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'];
        $userId = $request->get('userId');
        $subscriptionType = $request->get('subscriptionType');

        $user = User::find($userId);
        $productIds = SubscriptionGroupService::SUBSCRIPTION_MAP[$subscriptionType];

        $users = $this->subscriptionGroupService->getDistributorsByProduct($user, $productIds, $search);

        $count = count($users);
        $userPagination = array_slice($users, $start, $length);

        $data = array(
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $userPagination,
        );

        return json_encode($data);
    }
}
