<?php

namespace App\Http\Controllers;

use App\Order;
use Auth;
use DB;
use Session;

class Ticket extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    private function ticketCheckOutSessionDataValidate()
    {
        // check discount code
        $discountCode = Session::get('checkOutTicketPackDiscountCode');
        $discount = 0;
        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                return ['error' => 1, 'msg' => "Invalid discount code"];
            }
        }
        return ['error' => 0, 'discountCode' => $discountCode, 'discount' => $discount, 'ticketPackId' => \App\Product::ID_TICKET, 'ticketCheckOutQuantity' => 1, 'sessionId' => session('sessionId')];
    }

    public function ticketPacksCheckOut()
    {
        $req = request();
        $sesData = $this->ticketCheckOutSessionDataValidate();
        $product = \App\Product::getProduct(\App\Product::ID_TICKET);
        //discount will come
        $amount = (1 * (float)\App\Product::TICKET_PURCHASE_DISCOUNT_PRICE) - $sesData['discount'];
        if ($amount <= 0) {
            return \App\Helper::paymentUsingCouponCode($sesData, $product, 'PURCHASE_TICKET');
        }
        $vali = \App\Helper::validateCheckOutPaymentType($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $paymentType = $req->payment_method;
        if (!empty($paymentType) && $paymentType == "new_card") {
            $d['countries'] = DB::table('country')->orderBy('country', 'asc')->get();
            $v = (string)view('affiliate.ticket.dlg_check_out_add_payment_method')->with($d);
            return response()->json(['error' => 0, 'v' => $v]);
        } else if (!empty($paymentType) && $paymentType == "e_wallet") {
            return $this->doPaymentForBuyTicketByEwallet($req);
        } else if (!empty($paymentType)) {
            return $this->doPaymentForTicketPackByExistingCard($req);
        }

        $this->doRegisterTicketPurchase();
    }

    private function doPaymentForBuyTicketByEwallet()
    {
        $sesData = $this->ticketCheckOutSessionDataValidate();
        $product = \App\Product::getProduct($sesData['ticketPackId']);
        $amount = ($sesData['ticketCheckOutQuantity'] * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE) - $sesData['discount'];
        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();
        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, null, null, \App\Helper::createEmptyPaymentRequest(Auth::user()->firstname, Auth::user()->lastname, null), \App\PaymentMethodType::TYPE_E_WALET);
        $orderSubtotal = (1 * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE);
        $orderTotal = (1 * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE) - $sesData['discount'];
        $orderId = \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, null, 'PURCHASE_TICKET');
        \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::TYPE_CHECKOUT_TICKET, -$orderTotal, $orderId);
        Session::put('ticketPackId');
        Session::put('discountCode');
        Session::put('checkOutTicketPackDiscountCode');
        session(['ticket_purchased' => true]);
        DB::table('check_ticket_purchase')->where('user_id', Auth::user()->id)->update(['purchase' => 1]);
        $v = (string)view('affiliate.ticket.dlg_ticket_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function doPaymentForTicketPackByExistingCard($req)
    {
        // check discount code
        $sesData = $this->ticketCheckOutSessionDataValidate();
        $paymentMethodId = $req->payment_method;
        $res = \App\Helper::checkExistingCardAndBillAddress(Auth::user()->id, $paymentMethodId);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $product = \App\Product::getProduct($sesData['ticketPackId']);
        return \App\Helper::NMIPaymentProcessUsingExistingCard(Auth::user()->id, $res['billingAddress'], $product, $sesData, $res['paymentMethod'], Auth::user()->email, Auth::user()->phonenumber, Auth::user()->firstname, Auth::user()->lastname, 'PURCHASE_TICKET');
    }

    public function ticketCheckOutNewCard()
    {
        $req = request();
        $vali = \App\Helper::validatePaymentPage($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        return $this->doPaymentForNewCardTicket($req);
    }

    private function doPaymentForNewCardTicket($req)
    {
        $sesData = $this->ticketCheckOutSessionDataValidate();
        $product = \App\Product::getProduct($sesData['ticketPackId']);
        $res = \App\Helper::checkExsitingCardAfterTokenize($req);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $orderSubtotal = $sesData['ticketCheckOutQuantity'] * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE;
        $orderTotal = ($sesData['ticketCheckOutQuantity'] * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE) - $sesData['discount'];
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;
        if (\App\Helper::checkTMTAllowPayment($req->countrycode, Auth::user()->id) > 0) {
            $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
        }
        $nmiResult = \App\Helper::NMIPaymentProcessUsingNewCard($req, $orderTotal, $product, $sesData['sessionId'], Auth::user()->email, Auth::user()->phonenumber, $paymentMethodType);
        if ($nmiResult['error'] == 1) {
            return response()->json($nmiResult);
        }
        $authorization = $nmiResult['authorization'];
        $addressId = \App\Helper::createSecondoryAddressIfNotAvlPrimaryAddress(Auth::user()->id, $req, \App\PaymentMethodType::TYPE_CREDIT_CARD);
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, $res['token'], $addressId, $req, $paymentMethodType);
        \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, $authorization, 'PURCHASE_TICKET');
        Session::put('ticketPackId');
        Session::put('discountCode');
        Session::put('checkOutTicketPackDiscountCode');
        session(['ticket_purchased' => true]);
        DB::table('check_ticket_purchase')->where('user_id', Auth::user()->id)->update(['purchase' => 1]);
        $v = (string)view('affiliate.ticket.dlg_ticket_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }



    public function checkPurchase()
    {
        $created_dt = Auth::user()->created_dt;

        if ($created_dt < '2019-09-01' || $created_dt > '2019-09-17' || Auth::user()->current_product_id <= 1) {
            return;
        }

        \App\Helper::checkIsUserPurchaseTicket(Auth::user()->id);

        $ticket_purchased = session('ticket_purchased');
        if (!$ticket_purchased) {
            session_start();
            $d['sessionId'] = session_id();
            session(['checkOutTicketPackDiscountCode' => '', 'sessionId' => $d['sessionId']]);
            $v = (string)view('affiliate.ticket.dlg_check_out_ticket')->with($d);
            return response()->json(['error' => 0, 'v' => $v]);
        }
    }

    public function dlgCheckOutTicket()
    {
        $productId = \App\Product::ID_TICKET;
        session_start();
        $product = \App\Product::getProduct($productId);
        $d['product'] = $product;
        $d['sessionId'] = session_id();
        return view('affiliate.ticket.dlg_check_out_ticket')->with($d);
    }

    public function skipPurchaseConfim()
    {
        $v = (string)view('affiliate.ticket.dlg_ticket_skip_confirm');
        return response()->json(['error' => 0, 'v' => $v]);
//        DB::table('check_ticket_purchase')->where('user_id', Auth::user()->id)->update(['purchase' => 1]);
//        session(['ticket_purchased' => true]);
//        return response()->json(['error' => '0', 'msg' => "Ticket purchase skipped. You won't see it again."]);
    }

    public function skipPurchase()
    {
        $this->doRegisterTicketPurchase();

        return response()->json(['error' => '0', 'msg' => "Ticket purchase skipped. You won't see it again."]);
    }

    public function doRegisterTicketPurchase()
    {
        DB::table('check_ticket_purchase')->where('user_id', Auth::user()->id)->update(['purchase' => 1]);
        session(['ticket_purchased' => true]);
    }

    public function checkCouponCode()
    {
        $req = request();
        session(['checkOutTicketPackDiscountCode' => ""]);
        $boomerangPackId = \App\Product::ID_TICKET;
        $boomerangCheckOutQuantity = 1;
        $product = \App\Product::getProduct($boomerangPackId);
        $subTotal = \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE * $boomerangCheckOutQuantity;
        $total = $subTotal;
        $d['product'] = $product;
        $d['sub_total'] = $subTotal;
        $d['quantity'] = $boomerangCheckOutQuantity;
        $d['total'] = $total;
        $v = (string)view('affiliate.ticket.dlg_buy_coupon')->with($d);
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
        session(['checkOutTicketPackDiscountCode' => $discountCode]);
        $subTotal = \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE * $boomerangCheckOutQuantity;
        $total = $subTotal - $discount;
        if ($total <= 0) {
            $total = 0;
        }
        $d['product'] = $product;
        $d['sub_total'] = $subTotal;
        $d['total'] = $total;
        $d['quantity'] = $boomerangCheckOutQuantity;
        $v = (string)view('affiliate.ticket.dlg_buy_coupon')->with($d);

        $this->doRegisterTicketPurchase();

        return response()->json(['error' => 0, 'msg' => 'Valid discount code', 'v' => $v, 'total' => $total]);
    }


    public function checkoutPaymentMethod()
    {
        session(['checkOutTicketPackDiscountCode' => '']);
        $d['product'] = \App\Product::getProduct(\App\Product::ID_TICKET);
        $d['checkOutQty'] = 1;
        $d['cvv'] = \App\PaymentMethod::getAllRec(Auth::user()->id);
        $v = (string)view('affiliate.ticket.dlg_check_out_payment')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

}
