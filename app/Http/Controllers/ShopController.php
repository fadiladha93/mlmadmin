<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Validator;
use App\Http\Controllers\IbuumerangController;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->ibuumerangController = New IbuumerangController;

        $this->middleware('auth.admin', ['except' => [
            'goToShop',
            'dlgCheckOutXcceleratePhotobook',
            'dlgCheckOutXccelerateSalesToolsEng',
            'dlgCheckOutXccelerateSalesToolsSpan',
            'dlgCheckOutVideoSeries',
            'genericCheckOut',
            'genericCheckOutNewCard'
        ]]);
        $this->middleware('auth.affiliate');

    }

    public function goToShop() {
        return view('affiliate.shop.store');
    }


    // 2020 Xccelerate Items  ============================================

    public function dlgCheckOutXcceleratePhotobook()
    {
        $productId = \App\Product::ID_PHOTOBOOK_53;
        $photolink = "assets/images/photobook_orderform_2.jpg";
        return $this->popUpProductTemplateModal($productId,$photolink);
    }

    public function dlgCheckOutXccelerateSalesToolsEng()
    {
        $productId = \App\Product::IGO_SALES_TOOLS_ENG_54;
        $photolink = "assets/images/promo-ticket.png";
        $addPrice = "plus shipping";
        return $this->popUpProductTemplateModal($productId,$photolink);
    }

    public function dlgCheckOutXccelerateSalesToolsSpan()
    {
        $productId = \App\Product::IGO_SALES_TOOLS_SPAN_55;
        $photolink = "assets/images/promo-ticket.png";
        $addPrice = "plus shipping";
        return $this->popUpProductTemplateModal($productId,$photolink);
    }

    public function dlgCheckOutVideoSeries()
    {
        $productId = \App\Product::IGO_VIDEO_TRAINING_56;
        $photolink = "assets/images/promo-ticket.png";
        $addPrice = "plus shipping";
        return $this->popUpProductTemplateModal($productId,$photolink);
    }

    // END 2020 Xccelerate Items  ============================================


    public function popUpProductTemplateModal($productId, $photolink = null)
    {
        session_start();
        $product = \App\Product::getProduct($productId);
        $d['product'] = $product;
        $d['photo'] = $photolink;
        $d['sessionId'] = session_id();
        return view('affiliate.shop.dlg_store_product_template')->with($d);
    }

    public function genericCheckOutNewCard()
    {
        $req = request();
        $vali = \App\Helper::validatePaymentPage($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        return $this->doPaymentForNewCardGeneric($req);
    }


    public function genericCheckOut()
    {
        $req = request();
        $sesData = $this->genericCheckOutSessionDataValidate();

        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }

        $product = \App\Product::getProduct($sesData['productId']);

        //discount will come
        $amount = ($sesData['CheckOutQuantity'] * $product->price) - (float)$sesData['discount'];
        if ($amount <= 0) {
            return \App\Helper::paymentUsingCouponCode($sesData, $product, 'PURCHASE_SHOP_ITEM');
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
            return $this->doPaymentForBuyShopProductByEwallet($req);
//        BitPay and Skrill are currently disabled
//        } else if (!empty($paymentType) && $paymentType == "bit_pay") {
//            return $this->bitpayInvoiceGenerate();
//        } else if (!empty($paymentType) && $paymentType == "skrill") {
//            return $this->skrillPayment($product);
        } else if (!empty($paymentType)) {
            return $this->doPaymentForBuyShopProductByExistingCard($req);
        }
    }


    private function doPaymentForBuyShopProductByEwallet()
    {
        $sesData = $this->genericCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getProduct($sesData['productId']);

        $amount = ($sesData['CheckOutQuantity'] * $product->price) - $sesData['discount'];
        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();
        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }

        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, null, null, \App\Helper::createEmptyPaymentRequest(Auth::user()->firstname, Auth::user()->lastname, null), \App\PaymentMethodType::TYPE_E_WALET);
        $orderSubtotal = ($sesData['CheckOutQuantity'] * $product->price);
        $orderTotal = ($sesData['CheckOutQuantity'] * $product->price) - $sesData['discount'];

        /* Add E-wallet rows to DB */
        $orderId = \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, null, 'PURCHASE_SHOP_ITEM');
        $EwalletTransactionId = \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::TYPE_CHECKOUT_SHOP, -$orderTotal, $orderId);

        Session::put('checkOutBoomerangPack');
        Session::put('checkOutBoomerangPackDiscountCode');

        $d['product'] = $product;
        $v = (string)view('affiliate.ibuumerang.dlg_check_out_success')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }


    private function doPaymentForBuyShopProductByExistingCard($req)
    {
        // check discount code
        $sesData = $this->genericCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $paymentMethodId = $req->payment_method;
        $res = \App\Helper::checkExistingCardAndBillAddress(Auth::user()->id, $paymentMethodId);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $product = \App\Product::getProduct($sesData['productId']);
        return \App\Helper::NMIPaymentProcessUsingExistingCard(Auth::user()->id, $res['billingAddress'], $product, $sesData, $res['paymentMethod'], Auth::user()->email, Auth::user()->phonenumber, Auth::user()->firstname, Auth::user()->lastname, 'PURCHASE_SHOP_ITEM');
    }

    private function doPaymentForNewCardGeneric($req)
    {
        $sesData = $this->genericCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getProduct($sesData['productId']);
        $res = \App\Helper::checkExsitingCardAfterTokenize($req);
        if ($res['error'] == 1) {
            return response()->json($res);
        }

        $orderSubtotal = ($sesData['CheckOutQuantity'] * $product->price);
        $orderTotal = ($sesData['CheckOutQuantity'] * $product->price) - $sesData['discount'];

        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;

        if (\App\Helper::checkTMTAllowPayment($req->countrycode,Auth::user()->id) > 0) {
            if (\App\Helper::checkTMTAllowPayment($req->countrycode,Auth::user()->id) > 0) {
                //  $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
                // ONLY ON US CUSTOMERS
                if($req->countrycode == "US"){
                    $paymentMethodType = \App\PaymentMethodType::TYPE_PAYARC;
                }else{
                    $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
                }
            }
        }

        $nmiResult = \App\Helper::NMIPaymentProcessUsingNewCard($req, $orderTotal, $product, $sesData['sessionId'], Auth::user()->email, Auth::user()->phonenumber, $paymentMethodType);
        if ($nmiResult['error'] == 1) {
            return response()->json($nmiResult);
        }
        $authorization = $nmiResult['authorization'];
        $addressId = \App\Helper::createSecondoryAddressIfNotAvlPrimaryAddress(Auth::user()->id, $req,\App\PaymentMethodType::TYPE_CREDIT_CARD);
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, $res['token'], $addressId, $req, $paymentMethodType);
        \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, $authorization, 'PURCHASE_SHOP_ITEM');

        Session::put('checkOutBoomerangPack');
        Session::put('checkOutBoomerangPackDiscountCode');

        $d['product'] = $product;
        $v = (string)view('affiliate.ibuumerang.dlg_check_out_success')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }


    private function genericCheckOutSessionDataValidate()
    {
        // get discount code
        // NOTE - ignore non-generic session name 'checkOutBoomerangPackDiscountCode'
        //
        $discountCode = Session::get('checkOutBoomerangPackDiscountCode');
        $discount     = 0;
        if (!empty($discountCode) && !$discount = \App\DiscountCoupon::getDiscountAmount($discountCode)) {
            return ['error' => 1, 'msg' => "Invalid discount code"];
        }

        // these lines check for a session object called 'checkOutBoomerangPack'
        //  NOTE - ignore non-generic session called name of 'checkOutBoomerangPack'
        if (empty(Session::get('checkOutBoomerangPack'))) {
            return ['error' => 1, 'msg' => "Invalid session"];
        }
        $checkOutProduct = Session::get('checkOutBoomerangPack');
        if (empty($checkOutProduct['productId'])) {
            return ['error' => 1, 'msg' => "Invalid product id"];
        }
        if (empty($checkOutProduct['checkOutQuantity'])) {
            return ['error' => 1, 'msg' => "Invalid checkout quantity"];
        }
        if (empty($checkOutProduct['sessionId'])) {
            return ['error' => 1, 'msg' => "Invalid session during checkout"];
        }

//        No user max buumerang check

        return [
            'error' => 0,
            'discountCode' => $discountCode,
            'discount' => $discount,
            'productId' => $checkOutProduct['productId'],
            'boomerangPackId' => 0,
            'CheckOutQuantity' => $checkOutProduct['checkOutQuantity'],
            'sessionId' => $checkOutProduct['sessionId']
        ];
    }

}
