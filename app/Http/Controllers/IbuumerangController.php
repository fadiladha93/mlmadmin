<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Validator;

class IbuumerangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin', ['except' => [
            'dlgCheckOutIbuumerang',
            'ibuumerangPackAddToCart',
            'ibuumerangPackAddToCartBack',
            'ibuumerangPacksCheckOut',
            'ibuumerangPacksCheckOutNewCard',
            'checkCouponCode',
        ]]);
        $this->middleware('auth.affiliate');
    }



    public function ibuumerangPacksCheckOutNewCard()
    {
        $req = request();
        $vali = \App\Helper::validatePaymentPage($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        return $this->doPaymentForNewCardIbuumerangPacks($req);
    }

    public function ibuumerangPacksCheckOut()
    {
        $req = request();
        $sesData = $this->ibuumerangCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getProduct($sesData['boomerangPackId']);
        //discount will come
        $amount = ($sesData['boomerangCheckOutQuantity'] * $product->price) - (float)$sesData['discount'];
        if ($amount <= 0) {
            return \App\Helper::paymentUsingCouponCode($sesData, $product, 'PURCHASE_IBUUMERANG');
        }
        $vali = \App\Helper::validateCheckOutPaymentType($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $paymentType = $req->payment_method;
        if (!empty($paymentType) && $paymentType == "new_card") {
            $d['countries'] = DB::table('country')->orderBy('country', 'asc')->get();
            $d['product'] = $product;
            $v = (string)view('affiliate.ibuumerang.dlg_check_out_add_payment_method')->with($d);
            return response()->json(['error' => 0, 'v' => $v]);
        } else if (!empty($paymentType) && $paymentType == "e_wallet") {
            return $this->doPaymentForBuyBoomerangPackByEwallet($req);
        } else if (!empty($paymentType) && $paymentType == "bit_pay") {
            return $this->bitpayInvoiceGenerate();
        } else if (!empty($paymentType) && $paymentType == "skrill") {
            return $this->skrillPayment($product);
        } else if (!empty($paymentType)) {
            return $this->doPaymentForBuyBoomerangPackByExistingCard($req);
        }
    }

    public function bitpayInvoiceGenerate()
    {
        $sesData = $this->ibuumerangCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $response = \App\Helper::bitPayPaymentRequest(Auth::user(), $sesData, 'PURCHASE_IBUUMERANG');
        return response()->json($response);
    }

    private function skrillPayment($product)
    {

        $sesData = $this->ibuumerangCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getProduct($sesData['boomerangPackId']);
        $amount = ($sesData['boomerangCheckOutQuantity'] * $product->price) - $sesData['discount'];
        Cache::store('file')->put(Auth::user()->id . '_skrill_product', json_encode($product), 1000);
        Cache::store('file')->put(Auth::user()->id . '_skrill_amount', json_encode($amount), 1000);
        Cache::store('file')->put(Auth::user()->id . '_skrill_discountCode', json_encode($sesData['discountCode']), 1000);
        $query = [
            'pay_to_email' => config('api_endpoints.skrill.sb.pay_to_email'),
            'currency' => 'USD',
            'return_url' => config('api_endpoints.skrill.sb.return_url'),
            'return_url_target' => 4,
            'amount' => 1,
            'detail1_text' => $product->productname,
            'detail1_description' => $product->productdesc,
            'detail1_description' => $product->productdesc,
        ];
        $url = 'https://pay.skrill.com?' . http_build_query($query);
        return response()->json(['error' => 0, 'url' => $url]);
    }


    private function doPaymentForBuyBoomerangPackByExistingCard($req)
    {
        // check discount code
        $sesData = $this->ibuumerangCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $paymentMethodId = $req->payment_method;
        $res = \App\Helper::checkExistingCardAndBillAddress(Auth::user()->id, $paymentMethodId);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $product = \App\Product::getProduct($sesData['boomerangPackId']);
        return \App\Helper::NMIPaymentProcessUsingExistingCard(Auth::user()->id, $res['billingAddress'], $product, $sesData, $res['paymentMethod'], Auth::user()->email, Auth::user()->phonenumber, Auth::user()->firstname, Auth::user()->lastname, 'PURCHASE_IBUUMERANG');
    }

    public function ibuumerangPackAddToCartBack()
    {
        $checkOutBoomerangPack = Session::get('checkOutBoomerangPack');
        $d['product'] = \App\Product::getProduct($checkOutBoomerangPack['productId']);
        $d['checkOutQty'] = $checkOutBoomerangPack['checkOutQuantity'];
        //existing card details
        $d['cvv'] = \App\PaymentMethod::getUserPaymentRecords(Auth::user()->id);
        $v = (string)view('affiliate.ibuumerang.dlg_check_out_payment')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function doPaymentForBuyBoomerangPackByEwallet()
    {
        $sesData = $this->ibuumerangCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getProduct($sesData['boomerangPackId']);

        $amount = ($sesData['boomerangCheckOutQuantity'] * $product->price) - $sesData['discount'];
        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();
        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, null, null, \App\Helper::createEmptyPaymentRequest(Auth::user()->firstname, Auth::user()->lastname, null), \App\PaymentMethodType::TYPE_E_WALET);
        $orderSubtotal = ($sesData['boomerangCheckOutQuantity'] * $product->price);
        $orderTotal = ($sesData['boomerangCheckOutQuantity'] * $product->price) - $sesData['discount'];

        /* Add E-wallet rows to DB */
        $orderId = \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, null, 'PURCHASE_IBUUMERANG');
        $EwalletTransactionId = \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::TYPE_CHECKOUT_BUUMERANGS, -$orderTotal, $orderId);

        Session::put('checkOutBoomerangPack');
        Session::put('checkOutBoomerangPackDiscountCode');

        $d['product'] = $product;
        $v = (string)view('affiliate.ibuumerang.dlg_check_out_success')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }


    public function ibuumerangPackAddToCart()
    {
        $req = request();

        $product = \App\Product::getById($req->product);

        // Only do this check if this product is 15 reload buumerangs
        // Do not remove this check, generic modal uses this shared method
        if($product->id == 15) {

            //check existing available boomerang counts
            $boomerangInv = \App\BoomerangInv::where('userid', Auth::user()->id)->first();
            $newBoomerangs = $product->num_boomerangs * (int)$req->quantity;

            if($boomerangInv) {
                $total = $boomerangInv->getBoomerangTotal() + $newBoomerangs;

                if (!empty($boomerangInv->max_available) && $total > $boomerangInv->max_available) {
                    return ['error' => 1, 'msg' => 'You cannot keep boomerang inventory more than ' . $boomerangInv->max_available];
                }
                if (empty($boomerangInv->max_available) && $total > \APP\BoomerangInv::MAX_BUUMERANGS_ALLOWED) {
                    return ['error' => 1, 'msg' => 'You cannot keep boomerang inventory more than ' . \APP\BoomerangInv::MAX_BUUMERANGS_ALLOWED];
                }
            }
        }

        $vali = \App\Helper::validateCheckOutQuantity($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        //dlg_check_out_payment.blade
        session(['checkOutBoomerangPack' => ['productId' => $req->product, 'checkOutQuantity' => $req->quantity, 'sessionId' => $req->sessionId]]);
        $d['product'] = \App\Product::getProduct($req->product);
        $d['checkOutQty'] = $req->quantity;
        //existing card details
        $d['cvv'] = \App\PaymentMethod::getUserPaymentRecords(Auth::user()->id);
        $v = (string)view('affiliate.ibuumerang.dlg_check_out_payment')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function dlgCheckOutIbuumerang()
    {
        $productId = \App\Product::ID_IBUUMERANG_25;
        session_start();
        $product = \App\Product::getProduct($productId);
        $d['product'] = $product;
        $d['sessionId'] = session_id();
        return view('affiliate.ibuumerang.dlg_check_out')->with($d);
    }

    public function checkCouponCode()
    {
        $req = request();
        session(['checkOutBoomerangPackDiscountCode' => ""]);
        $checkOutBoomerangPack = Session::get('checkOutBoomerangPack');
        $boomerangPackId = $checkOutBoomerangPack['productId'];
        $boomerangCheckOutQuantity = $checkOutBoomerangPack['checkOutQuantity'];
        $product = \App\Product::getProduct($boomerangPackId);
        $subTotal = $product->price * $boomerangCheckOutQuantity;
        $total = $subTotal;
        $d['product'] = $product;
        $d['sub_total'] = $subTotal;
        $d['quantity'] = $boomerangCheckOutQuantity;
        $d['total'] = $total;
        $v = (string)view('affiliate.ibuumerang.dlg_buy_ibuumerang_coupon')->with($d);
        $discountCode = $req->coupon;
        $discount = 0;
        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v, 'total' => $total]);
            }
        } else {
            return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v, 'total' => $total]);
        }
        session(['checkOutBoomerangPackDiscountCode' => $discountCode]);
        $subTotal = $product->price * $boomerangCheckOutQuantity;
        $total = $subTotal - $discount;
        if ($total <= 0) {
            $total = 0;
        }
        $d['product'] = $product;
        $d['sub_total'] = $subTotal;
        $d['total'] = $total;
        $d['quantity'] = $boomerangCheckOutQuantity;
        $v = (string)view('affiliate.ibuumerang.dlg_buy_ibuumerang_coupon')->with($d);
        return response()->json(['error' => 0, 'msg' => 'Valid discount code', 'v' => $v, 'total' => $total]);
    }


    private function doPaymentForNewCardIbuumerangPacks($req)
    {
        $sesData = $this->ibuumerangCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getProduct($sesData['boomerangPackId']);
        $res = \App\Helper::checkExsitingCardAfterTokenize($req);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $orderSubtotal = $sesData['boomerangCheckOutQuantity'] * $product->price;
        $orderTotal = ($sesData['boomerangCheckOutQuantity'] * $product->price) - $sesData['discount'];

        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;

        if (\App\Helper::checkTMTAllowPayment($req->countrycode,Auth::user()->id) > 0) {
            //  $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
            // ONLY ON US CUSTOMERS
            if($req->countrycode == "US"){
                $paymentMethodType = \App\PaymentMethodType::TYPE_PAYARC;
            }else{
                $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
            }
        }

        $nmiResult = \App\Helper::NMIPaymentProcessUsingNewCard($req, $orderTotal, $product, $sesData['sessionId'], Auth::user()->email, Auth::user()->phonenumber, $paymentMethodType);
        if ($nmiResult['error'] == 1) {
            return response()->json($nmiResult);
        }
        $authorization = $nmiResult['authorization'];
        $addressId = \App\Helper::createSecondoryAddressIfNotAvlPrimaryAddress(Auth::user()->id, $req,\App\PaymentMethodType::TYPE_CREDIT_CARD);
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, $res['token'], $addressId, $req, $paymentMethodType);
        \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, $authorization, 'PURCHASE_IBUUMERANG');
        Session::put('checkOutBoomerangPack');
        Session::put('checkOutBoomerangPackDiscountCode');

        $d['product'] = $product;
        $v = (string)view('affiliate.ibuumerang.dlg_check_out_success')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function ibuumerangCheckOutSessionDataValidate()
    {
        $discountCode = Session::get('checkOutBoomerangPackDiscountCode');
        $discount     = 0;
        if (!empty($discountCode) && !$discount = \App\DiscountCoupon::getDiscountAmount($discountCode)) {
            return ['error' => 1, 'msg' => "Invalid discount code"];
        }

        if (empty(Session::get('checkOutBoomerangPack'))) {
            return ['error' => 1, 'msg' => "Invalid boomerang session"];
        }

        // set request
        $checkOutBoomerangPack = Session::get('checkOutBoomerangPack');
        if (empty($checkOutBoomerangPack['productId'])) {
            return ['error' => 1, 'msg' => "Invalid boomerang pack id"];
        }
        if (empty($checkOutBoomerangPack['checkOutQuantity'])) {
            return ['error' => 1, 'msg' => "Invalid boomerang checkout quantity"];
        }
        if (empty($checkOutBoomerangPack['sessionId'])) {
            return ['error' => 1, 'msg' => "Invalid boomerang checkout"];
        }

        if (!$boomerangInventory = \App\BoomerangInv::query()->where('userid', Auth::user()->id)->first()) {
            return ['error' => 1, 'msg' => "Boomerang Inventory not found for user " . Auth::user()->id];
        }

        $product = \App\Product::getById(\App\Product::ID_IBUUMERANG_25);
        $newBoomerangs = $product->num_boomerangs * (int)$checkOutBoomerangPack['checkOutQuantity'];

        $total = $boomerangInventory->getBoomerangTotal() + $newBoomerangs;
        if (!empty($boomerangInventory->max_available) && $total > $boomerangInventory->max_available) {
            return ['error' => 1, 'msg' => 'You cannot keep boomerang inventory more than' . $boomerangInventory->max_available];
        }
        if (empty($boomerangInventory->max_available) && $total > \APP\BoomerangInv::MAX_BUUMERANGS_ALLOWED) {
            return ['error' => 1, 'msg' => "You cannot keep boomerang inventory more than 200"];
        }

        return [
            'error' => 0,
            'discountCode' => $discountCode,
            'discount' => $discount,
            'boomerangPackId' => $checkOutBoomerangPack['productId'],
            'boomerangCheckOutQuantity' => $checkOutBoomerangPack['checkOutQuantity'],
            'sessionId' => $checkOutBoomerangPack['sessionId']
        ];
    }
}
