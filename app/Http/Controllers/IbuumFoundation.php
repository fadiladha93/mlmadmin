<?php

namespace App\Http\Controllers;

use App\PaymentMethodType;
use Auth;
use Validator;

class IbuumFoundation extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkoutFoundation()
    {
        $req = request();
        $validator = Validator::make($req->all(), [
            'amount' => 'required|numeric',
            'payment_method' => 'required',
        ], [
            'amount.required' => 'Amount to be transferred is required',
            'amount.numeric' => 'Amount must be numeric',
            'payment_method.required' => 'Payment method cannot be empty',
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
            if ($req->amount < 0) {
                $valid = 0;
                $msg = "Invalid amount";
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;

        if (!$valid) {
            return response()->json(['error' => 1, 'msg' => $msg]);
        }
        if ($req->payment_method == 'e_wallet') {
            $balance = Auth::user()->estimated_balance;
            $availableBalace = $balance - \App\EwalletTransaction::TRANSACTION_FEE;
            if ($availableBalace < 0) {
                return response()->json(['error' => 1, 'msg' => 'Insufficient available balance']);
            } else if ($availableBalace < $req->amount) {
                return response()->json(['error' => 1, 'msg' => "Amount to be transferred cannot be exceeded " . number_format(floor($availableBalace), 2)]);
            } else {
                $this->doPaymentByEwallet(\App\Product::ID_FOUNDATION, $req->amount);
                $v = (string)view('affiliate.foundation.dlg_foundation_checkout_success');
                return response()->json(['error' => 0, 'v' => $v]);
            }
        } else if ($req->payment_method == 'new_card') {
            session_start();
            $d = [];
            $d['amount'] = $req->amount;
            $d['product'] = \App\Product::find(\App\Product::ID_FOUNDATION);
            $d['sessionId'] = session_id();
            $v = (string)view('affiliate.foundation.dlg_foundation_checkout_new_card')->with($d);
            return response()->json(['error' => 0, 'v' => $v]);
        } else {
            //existing card
            $paymentMethodId = $req->payment_method;
            $res = \App\Helper::checkExistingCardAndBillAddress(Auth::user()->id, $paymentMethodId);
            if ($res['error'] == 1) {
                return response()->json($res);
            }
            $product = \App\Product::getById(\App\Product::ID_FOUNDATION);
            $product->price = $req->amount;
            return \App\Helper::NMIPaymentProcessUsingExistingCard(Auth::user()->id, $res['billingAddress'], $product, $sesData = ['discount' => 0, 'sessionId' => $req->session_id], $res['paymentMethod'], Auth::user()->email, Auth::user()->phonenumber, Auth::user()->firstname, Auth::user()->lastname, 'FOUNDATION');
        }

    }


    public function checkoutCardFoundation()
    {
        $req = request();

        $validator = Validator::make($req->all(), [
            'number' => 'required',
            'cvv' => 'required|max:4',
            'expiry_date' => 'required|size:7',
        ], [
            'number.required' => 'Card number is required',
            'cvv.required' => 'CVV is required',
            'cvv.max' => 'CVV cannot exceed 4 charactors',
            'expiry_date.required' => 'Expiration date is required',
            'expiry_date.size' => 'Invalid expiration date format',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 1;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
            return response()->json(['error' => $valid, 'msg' => $msg]);
        } else {
            $valid = 0;
            // validate expiry date
            $expiryDate = trim(str_replace(' ', '', $req->input('expiry_date')));
            $expireDateParts = explode('/', $expiryDate);
            if (!isset($expireDateParts[0]) || !isset($expireDateParts[1]) || strlen($expireDateParts[0]) != 2 || strlen($expireDateParts[1]) != 4) {
                $valid = 1;
                $msg = 'Invalid Expiry date';
            } else if (!preg_match('/^\d+$/', $expireDateParts[0]) || (!preg_match('/^\d+$/', $expireDateParts[1]))) {
                $valid = 1;
                $msg = 'Invalid Expiry date';
            }
            if ($valid == 1) {
                return response()->json(['error' => 1, 'msg' => $msg]);
            }
        }
        $product = \App\Product::getById(\App\Product::ID_FOUNDATION);

        $res = \App\Helper::checkExsitingCardAfterTokenize($req);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $orderSubtotal = $req->amount;
        $orderTotal = $req->amount;
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;
        if (\App\Helper::checkTMTAllowPayment(Auth::user()->countrycode, Auth::user()->id) > 0) {
            $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
        }
        $userAddress = \App\Address::where('userid', Auth::user()->id)
            ->where('addrtype', \App\Address::TYPE_REGISTRATION)
            ->first();
        if (empty($userAddress)) {
            $userAddress = \App\Address::getBillingAddress(Auth::user()->id);
        }
        $cReq = new \stdClass();
        $cReq->first_name = Auth::user()->firstname;
        $cReq->last_name = Auth::user()->lastname;
        $cReq->session_id = $req->session_id;
        $cReq->number = $req->number;
        $cReq->cvv = $req->cvv;
        $cReq->expiry_date = $req->expiry_date;
        $cReq->address1 = (isset($userAddress->address1) ? $userAddress->address1 : '');
        $cReq->address2 = (isset($userAddress->address2) ? $userAddress->address2 : '');
        $cReq->city = (isset($userAddress->city) ? $userAddress->city : '');
        $cReq->stateprov = (isset($userAddress->stateprov) ? $userAddress->stateprov : '');
        $cReq->postalcode = (isset($userAddress->postalcode) ? $userAddress->postalcode : '');
        $cReq->countrycode = (isset($userAddress->countrycode) ? $userAddress->countrycode : '');
        $cReq->apt = (isset($userAddress->apt) ? $userAddress->apt : '');

        $nmiResult = \App\Helper::NMIPaymentProcessUsingNewCard($cReq, $orderTotal, $product, $req->session_id, Auth::user()->email, Auth::user()->phonenumber, $paymentMethodType);

        if ($nmiResult['error'] == 1) {
            return response()->json($nmiResult);
        }
        $authorization = $nmiResult['authorization'];
        $tokenEx = $nmiResult['response']->Token;
        $addressId = $userAddress->id;
        $paymentMethodId = \App\PaymentMethod::addSecondaryCard(Auth::user()->id, 0, $tokenEx, $addressId, \App\PaymentMethodType::TYPE_CREDIT_CARD, $cReq);
        \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData = ['discountCode' => ''], $product, $authorization, 'FOUNDATION');
        $v = (string)view('affiliate.foundation.dlg_foundation_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function ibuumFoundation()
    {
        session_start();

        $ignoredPaymentMethods = [
            PaymentMethodType::TYPE_ADMIN,
            PaymentMethodType::TYPE_BITPAY,
            PaymentMethodType::TYPE_SKRILL,
            PaymentMethodType::TYPE_COUPON_CODE
        ];

        $d['cvv'] = \App\PaymentMethod::where('userID', Auth::user()->id)
            ->whereNotIn('pay_method_type', $ignoredPaymentMethods)
            ->where('is_restricted', '=', 0)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->get();
        $d['sessionId'] = session_id();
        $d['product'] = \App\Product::getById(\App\Product::ID_FOUNDATION);
        $v = (string)view('affiliate.foundation.dlg_foundation')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }


    public static function validatePaymentPage($req)
    {
        $validator = Validator::make($req->all(), [
            'number' => 'required',
            'cvv' => 'required|max:4',
            'expiry_date' => 'required|size:7',
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

    private function doPaymentByEwallet($productId, $amount)
    {
        $product = \App\Product::getById($productId);
        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();
        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, null, null, \App\Helper::createEmptyPaymentRequest(Auth::user()->firstname, Auth::user()->lastname, null), \App\PaymentMethodType::TYPE_E_WALET);
        $orderSubtotal = $amount;
        $orderTotal = $amount;
        $orderId = \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData = ['discountCode' => ''], $product, null, $orderFor = "FOUNDATION");
        \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::TYPE_FOUNDATION, -$amount, $orderId);
        $v = (string)view('affiliate.foundation.dlg_foundation_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }
}
