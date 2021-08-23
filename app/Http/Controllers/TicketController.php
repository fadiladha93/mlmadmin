<?php

namespace App\Http\Controllers;

use App\Order;
use Auth;
use DB;
use Session;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function checkPurchase()
    {
        session()->forget('products');

        $created_dt = Auth::user()->created_dt;

        session_start();
        $data['sessionId'] = session_id();

        $productDreamWeekendDecorator = new \stdClass();

        $productDreamWeekendDecorator->category_product = 'Dream Weekend';
        $productDreamWeekendDecorator->products = $this->getDecoratedProductsFromProducts(\App\Product::getProductsFromProductIdArray(\App\Product::ID_EVENTS_TICKET_DREAM_WEEKEND));
        $productDreamWeekendDecorator->image = 'dreamweekend2019.jpg';

        $productXccelerateDecorator = new \stdClass();

        $productXccelerateDecorator->category_product = 'Xccelerate';
        $productXccelerateDecorator->products = $this->getDecoratedProductsFromProducts(\App\Product::getProductsFromProductIdArray(\App\Product::ID_EVENTS_TICKET_XCCELERATE));
        $productXccelerateDecorator->image = 'xccelerate.jpeg';

        $productsByCategory['Dream Weekend'] = $productDreamWeekendDecorator;
        $productsByCategory['Xccelerate'] = $productXccelerateDecorator;

        $data['products_by_category'] = $productsByCategory;
        $data['cvv'] = \App\PaymentMethod::getAllRec(Auth::user()->id);

        $v = (string)view('affiliate.ticket.dlg_check_out_events_ticket')->with($data);

        return response()->json(['error' => 0, 'view' => $v]);
    }

    public function checkoutPaymentMethod()
    {
        $created_dt = Auth::user()->created_dt;
        $quantities = request('quantity');

        if (empty(request('quantity'))) {
            $productsTemp = session('products');
            foreach ($productsTemp as $productTemp)
            {
                $quantities[$productTemp->product->id] = $productTemp->quantity;
            }
        }

        $subtotal = 0;
        $total = 0;
        foreach ($quantities as $id => $quantity)
        {
            if ($quantity == 0) {
                continue;
            }

            $productDecorator = new \stdClass();
            $productDecorator->product = \App\Product::getProduct($id);
            $productDecorator->quantity = $quantity;

            $subtotal += $quantity;
            $total += $quantity * $productDecorator->product->price;

            $products[] = $productDecorator;
        }

        session(['products' => $products]);

        session_start();
        $data['sessionId'] = session_id();

        $productsId = array_merge(\App\Product::ID_EVENTS_TICKET_DREAM_WEEKEND, \App\Product::ID_EVENTS_TICKET_XCCELERATE);

        //total would be the amount paid (voucher if used would lower the total from the subtotal)
        $data['products'] = $products;
        $data['subtotal'] = $subtotal;
        $data['total'] = $total;

        $data['cvv'] = \App\PaymentMethod::getUserPaymentRecords(Auth::user()->id);
        $view = (string)view('affiliate.ticket.dlg_check_out_events_ticket_payment')->with($data);

        return response()->json(['error' => 0, 'view' => $view]);
    }

    public function ticketPacksCheckOut()
    {
        $req = request();

        $discount = 0;

        $sesData = $this->ticketCheckOutSessionDataValidate();

        if (!empty($sesData['discount'])) {
            $discount = (int) $sesData['discount'];
        }

        $quantities = session('products');

        $isDiscountEnoughToPay = false;
        $subtotal = 0;
        $total = 0;

        foreach ($quantities as $decoratedProduct)
        {
            if ($decoratedProduct->quantity == 0) {
                continue;
            }

            $price = $decoratedProduct->product->price;

            if ($decoratedProduct->product->discount_price > 0) {
                $price = $decoratedProduct->product->discount_price;
            }

            $total += $decoratedProduct->quantity * $price;
            $products[] = $decoratedProduct;
        }

        $subtotal = $total;

        $amountAfterDiscounts = (1 * $total) - $discount;
        $isDiscountEnoughToPay = $amountAfterDiscounts <= 0;

        if ($isDiscountEnoughToPay) {
            \App\Helper::paymentUsingCouponCodeForTicket($sesData, $products, 'PURCHASE_EVENTS_TICKET');

            $v = (string)view('affiliate.ticket.dlg_events_ticket_checkout_success');
            return response()->json(['error' => 0, 'v' => $v]);
        }

        $d['sub_total'] = $subtotal;
        $d['total'] = $amountAfterDiscounts;
        $d['products'] = $products;

        $valid = \App\Helper::validateCheckOutPaymentType($req);
        if ($valid['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $valid['msg']]);
        }

        $paymentType = $req->payment_method;

        if (!empty($paymentType) && $paymentType == "new_card") {
            $d['countries'] = DB::table('country')->orderBy('country', 'asc')->get();
            $v = (string)view('affiliate.ticket.dlg_check_out_add_payment_method_events_ticket')->with($d);
            return response()->json(['error' => 0, 'v' => $v]);
        } else if (!empty($paymentType) && $paymentType == "e_wallet") {
            return $this->doPaymentForBuyTicketByEwallet();
        } else if (!empty($paymentType)) {
            return $this->doPaymentForTicketPackByExistingCard($req);
        }
    }

    public function dlgCheckOutTicket()
    {
        session_start();

        $productDreamWeekendDecorator = new \stdClass();

        $productDreamWeekendDecorator->category_product = 'Dream Weekend';
        $productDreamWeekendDecorator->products = $this->getDecoratedProductsFromProducts(\App\Product::getProductsFromProductIdArray(\App\Product::ID_EVENTS_TICKET_DREAM_WEEKEND));
        $productDreamWeekendDecorator->image = 'dreamweekend2019.jpg';

        $productXccelerateDecorator = new \stdClass();
        $productXccelerateDecorator->category_product = 'Xccelerate';
        $productXccelerateDecorator->products = $this->getDecoratedProductsFromProducts(\App\Product::getProductsFromProductIdArray(\App\Product::ID_EVENTS_TICKET_XCCELERATE));
        $productXccelerateDecorator->image = 'xccelerate.jpeg';

        $productsByCategory['Dream Weekend'] = $productDreamWeekendDecorator;
        $productsByCategory['Xccelerate'] = $productXccelerateDecorator;

        $data['products_by_category'] = $productsByCategory;

        $data['products'] = session('products');

        session()->forget('products');

        $data['sessionId'] = session_id();

        $view = (string)view('affiliate.ticket.dlg_check_out_events_ticket')->with($data);

        return response()->json(['error' => 0, 'v' => $view]);
    }

    public function checkCouponCode()
    {
        $req = request();
        session(['checkOutEventsTicketPackDiscountCode' => ""]);

//        $productsQuantity = $req->quantity;

        $productsQuantity = session('products');

        $subTotal = 0;
        $total = 0;
        $price = 0;
        foreach ($productsQuantity as $productDecorator)
        {
            if ($productDecorator->quantity == 0) {
                continue;
            }

//            $productDecorator = new \stdClass();
//            $productDecorator->product = \App\Product::getProduct($id);
//            $productDecorator->quantity = $quantity;

            $price = $productDecorator->product->price;

            if ($productDecorator->product->original_price > 0) {
                $price = $productDecorator->product->original_price;
            }

            $subtotal = $productDecorator->quantity * $price;
            $total += $subtotal;

            $products[] = $productDecorator;
        }

        $d['products'] = $products;
        session(['products' => $products]);
        $d['total'] = $total;

        $v = (string)view('affiliate.ticket.dlg_buy_coupon_events_ticket')->with($d);
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

        session(['checkOutEventsTicketPackDiscountCode' => $discountCode]);

        if ($total <= 0) {
            $total = 0;
        }

        $d['sub_total'] = $subtotal;
        $d['total'] = $total - $discount;

        $v = (string)view('affiliate.ticket.dlg_buy_coupon_events_ticket')->with($d);

        foreach ($products as $productDecorated)
        {
            $this->doRegisterTicketPurchase($productDecorated->product->id);
        }

        return response()->json(['error' => 0, 'msg' => 'Valid discount code', 'v' => $v, 'total' => $total]);
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

        $productsInSession = session('products');

        foreach ($productsInSession as $decoratedProduct)
        {
            $product = $decoratedProduct->product;

            $purchaseResponse[$product->id] = \App\Helper::NMIPaymentProcessUsingExistingCard(
                Auth::user()->id,
                $res['billingAddress'],
                $product,
                $sesData,
                $res['paymentMethod'],
                Auth::user()->email,
                Auth::user()->phonenumber,
                Auth::user()->firstname,
                Auth::user()->lastname,
                'PURCHASE_EVENTS_TICKET');
        }

        $v = (string)view('affiliate.ticket.dlg_events_ticket_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function doPaymentForBuyTicketByEwallet()
    {
        $sesData = $this->ticketCheckOutSessionDataValidate();

        $discount = 0;

        if (! empty($sesData['discount'])) {
            $discount = $sesData['discount'];
        }

        $productsInSession = session('products');

        $amount = 0;
        foreach ($productsInSession as $decoratedProduct) {

            $product = $decoratedProduct->product;
            $price = $product->price;

            if ($product->discount_price > 0) {
                $price = $product->discount_price;
            }

            $amount += ($decoratedProduct->quantity * $price) - $discount;
        }

        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();

        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }

        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, null, null, \App\Helper::createEmptyPaymentRequest(Auth::user()->firstname, Auth::user()->lastname, null), \App\PaymentMethodType::TYPE_E_WALET);

        $orderSubtotal = (1 * $amount);
        $orderTotal = (1 * $amount) - $discount;

        foreach ($productsInSession as $decoratedProduct) {
            $products[] = $decoratedProduct->product;
        }

            $orderId = \App\Helper::createNewOrderWithMultipleProductsAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $productsInSession, null, 'PURCHASE_EVENTS_TICKET');
            \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::TYPE_CHECKOUT_TICKET, -$orderTotal, $orderId);

            DB::table('check_events_ticket_purchase')
                ->updateOrInsert(
                    ['user_id' => Auth::user()->id, 'product_id' => $product->id],
                    ['purchase' => 1]
                );

        $v = (string)view('affiliate.ticket.dlg_events_ticket_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
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
        $res = \App\Helper::checkExsitingCardAfterTokenize($req);

        if ($res['error'] == 1) {
            return response()->json($res);
        }

        $orderSubtotal = 0;
        $orderTotal = 0;
        $products = [];

        $decoratedProducts = $sesData['products'];
        $productIdArray = [];
        foreach ($decoratedProducts as $decoratedProduct)
        {
            $product = $decoratedProduct->product;
            $products[] = $product;

            $price = $product->price;

            if ($product->discount_price > 0) {
                $price = $product->discount_price;
            }

            $orderSubtotal += $decoratedProduct->quantity * $price;
            $orderTotal += $orderSubtotal;
        }

        $orderTotal = $orderSubtotal - $sesData['discount'];
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;

        if (\App\Helper::checkTMTAllowPayment($req->countrycode, Auth::user()->id) > 0) {
            $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
        }

        session()->forget('products');
        session(['products' => []]);

        foreach ($decoratedProducts as $decoratedProduct)
        {
            session()->push('products', $decoratedProduct);

            $nmiResult =  \App\Helper::NMIPaymentProcessUsingNewCard($req, $orderTotal, $decoratedProduct->product, $sesData['sessionId'], Auth::user()->email, Auth::user()->phonenumber, $paymentMethodType);

            if ($nmiResult['error'] == 1) {
                return response()->json($nmiResult);
            }

            $authorization = $nmiResult['authorization'];
            $addressId = \App\Helper::createSecondoryAddressIfNotAvlPrimaryAddress(Auth::user()->id, $req, \App\PaymentMethodType::TYPE_CREDIT_CARD);
            $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, $res['token'], $addressId, $req, $paymentMethodType);
            \App\Helper::createNewOrderWithMultipleProductsAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $decoratedProducts, $authorization, 'PURCHASE_EVENTS_TICKET');

            Session::put('discountCode');
            Session::put('checkOutEventsTicketPackDiscountCode');

            DB::table('check_events_ticket_purchase')
                ->updateOrInsert(
                    ['user_id' => Auth::user()->id, 'product_id' => $decoratedProduct->product->id],
                    ['purchase' => 1]
                );
        }

        $v = (string)view('affiliate.ticket.dlg_events_ticket_checkout_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function ticketCheckOutSessionDataValidate()
    {
        // check discount code
        $discountCode = Session::get('checkOutEventsTicketPackDiscountCode');
        $discount = 0;

        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                return ['error' => 1, 'msg' => "Invalid discount code"];
            }
        }
        return [
            'error' => 0,
            'discountCode' => $discountCode,
            'discount' => $discount,
            'products' => session('products'),
            'sessionId' => session('sessionId')
        ];
    }

    public function doRegisterTicketPurchase($productId = 0)
    {
        DB::table('check_events_ticket_purchase')
            ->updateOrInsert(
                ['user_id' => Auth::user()->id, 'product_id' => $productId],
                ['purchase' => 1]
            );
    }

    /**
     * @param \Illuminate\Support\Collection $productsCollection
     * @return array
     */
    private function getDecoratedProductsFromProducts(\Illuminate\Support\Collection $productsCollection)
    {
        $productDecorator = new \stdClass();
        $decoratedProducts = [];

        foreach ($productsCollection as $product)
        {
            $productDecorator = new \stdClass();
            $productDecorator->product = $product;

            $productDecorator->quantity = 0;

            if (!empty(session('products'))) {

                foreach (session('products') as $productInSession)
                {
                    if ($productInSession->product->id == $product->id) {
                        $productDecorator->quantity = $productInSession->quantity;
                    }
                }

            }

            $decoratedProducts[] = $productDecorator;
        }

        return $decoratedProducts;
    }
}
