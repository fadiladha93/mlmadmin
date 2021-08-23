<?php

namespace App\Http\Controllers;

use App\DiscountCoupon;
use Auth;
use DataTables;
use DB;
use Validator;

class DiscountCouponController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'addNewDiscountCoupon',
                'addNew'
        ]]);
        $this->middleware('auth');
    }

    public function addNewDiscountCoupon() {
        $d = array();
        $d['code'] = \App\DiscountCoupon::getNewCode();
        $d['prepaid_products'] = \App\Product::getByTypeId(\App\ProductType::TYPE_PRE_PAID_CODES);
        return view('affiliate.discount.add-discount')->with($d);
    }

    public function index() {
        return view('admin.discount_coupon.index');
    }

    public function getRecs() {
        $query = DB::table('v_discount_coupon');
        return DataTables::of($query)->toJson();
    }

    public function frmNew() {
        $d = array();
        $d['code'] = \App\DiscountCoupon::getNewCode();
        $d['prepaid_products'] = \App\Product::getByTypeId(\App\ProductType::TYPE_PRE_PAID_CODES);
        return view('admin.discount_coupon.frmNew')->with($d);
    }

    private function checkEwalletBalance($req) {
        $distributor = \App\User::where('distid', $req->sponsorid)->first();
        if (!$distributor) {
            return ['error' => 1, 'msg' => 'Distributor not found'];
        }
        $balance = 0;
        $estimatedBalance = $distributor->estimated_balance;
        if ($estimatedBalance < abs($req->amount)) {
            $balance = $estimatedBalance > 0 ? $estimatedBalance : 0;
            return ['error' => 1, 'msg' => 'You have $' . number_format($balance, 2) . ' in your ewallet'];
        }

        $req->merge(['generated_for' => $distributor->id]);
        $amount = -1 * abs($req->amount);
        $tsaPurchase = true;
        return ['success' => 1, 'tsaPurchase' => $tsaPurchase, 'distributor' => $distributor, 'amount' => $amount, 'balance' => $balance];
    }

    public function addNew() {
        $req = request();
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $product = \App\Product::getById($req->product_id);
        $amount = (float) $product->price;
        $req['product_id_ref'] = $req->product_id;

        $req->merge(['amount' => $amount]);
        $cEBRes['tsaPurchase'] = false;
        if (!empty($req->sponsorid)) {
            $cEBRes = $this->checkEwalletBalance($req);
        } else {
            if (Auth::user()->usertype == \App\UserType::TYPE_DISTRIBUTOR && !empty(Auth::user()->distid)) {
                $req['sponsorid'] = Auth::user()->distid;
                $cEBRes = $this->checkEwalletBalance($req);
            }
        }
        if (!empty($cEBRes['error']) && $cEBRes['error'] == 1) {
            return response()->json(['error' => 1, 'msg' => $cEBRes['msg']]);
        }
        $couponCodeId = \App\DiscountCoupon::addNew($req);
        if ($cEBRes['tsaPurchase']) {
            $distributor = $cEBRes['distributor'];
            $note = $product->productname . " - " . $req->code;
            $paymentMethodId = $this->getEwalletPaymentMethodId(Auth::user()->id);
            $orderId = \App\Order::addNew(
                            $distributor->id, (float) $product->price, (float) $product->price, $product->bv, $product->qv, $product->cv, null, $paymentMethodId, null, null
            );
            \App\OrderItem::addNew(
                    $orderId, $req->product_id, 1, (float) $product->price, $product->bv, $product->qv, $product->cv, false, $couponCodeId
            );
            \App\EwalletTransaction::addPurchase($distributor->id, \App\EwalletTransaction::TYPE_CODE_PURCHASE, -$amount, $orderId, $note);
        }
        //$newCode = \App\DiscountCoupon::getNewCode();
        //return response()->json(['error' => 0, 'code' => $newCode]);
        if ($req->type == 'ajaxModal') {
            $d['code'] = $req->code;
            $v = (string) view('affiliate.discount.add-discount-success')->with($d);
            return response()->json(['error' => 0, 'msg' => 'Voucher code added successfully', 'v' => $v]);
        } else {
            return response()->json(['error' => 0, 'url' => url('/discount-coupons')]);
        }
    }

    private function getEwalletPaymentMethodId($userId) {
        $paymentMethod = \App\PaymentMethod::getByUserPayMethodType($userId, \App\PaymentMethodType::TYPE_E_WALET);
        if (empty($paymentMethod)) {
            $paymentMethod = \App\PaymentMethod::addNewCustomPaymentMethod([
                        'userID' => Auth::user()->id,
                        'created_at' => \utill::getCurrentDateTime(),
                        'updated_at' => \utill::getCurrentDateTime(),
                        'pay_method_type' => \App\PaymentMethodType::TYPE_E_WALET
            ]);
            return $paymentMethod->id;
        } else {
            return $paymentMethod->id;
        }
    }

    public function toggleActive($recId) {
        $rec = \App\DiscountCoupon::find($recId);
        if (!empty($rec)) {
            $rec->is_active = $rec->is_active == 0 ? 1 : 0;
            $rec->save();
        }
        return redirect('/discount-coupons');
    }

    public function deleteDiscountCode($recId) {
        \App\EwalletTransaction::refundCouponCode($recId);
        \App\DiscountCoupon::cancelDiscountCode($recId);

        return redirect('/discount-coupons');
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'code' => 'required|alpha_num|unique:discount_coupon,code',
                    'product_id' => 'required',
                        ], [
                    'code.required' => 'Code is required',
                    'code.alpha_num' => 'Invalid code',
                    'code.unique' => 'Code already used',
                    'product_id.required' => 'Discount Amount is required',
                        //'amount.numeric' => 'Amount must be numeric',
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
            /* if ($req->amount <= 0) {
              $valid = 0;
              $msg = "Amount must be greater than 0";
              } */
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function refundVoucher() {
        $req = request();

        if ($req->isMethod('get')) {
            $d['voucher'] = '';

            return view('admin.discount_coupon.refund')->with($d);
        }

        $validator = Validator::make($req->all(), [
            'voucher' => 'required|alpha_num',
        ], [
            'code.required' => 'Voucher Code is required',
            'code.alpha_num' => 'Invalid Voucher Code',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 1, 'msg' => $validator->messages()]);
        }

        $voucher = $req->voucher;
        $response = DiscountCoupon::getOrder($voucher);

        if (is_array($response) && isset($response['error'])){
            return response()->json($response);
        }

        $order = $response;
        $product = \App\Product::find($order->product_id);

        if (!$product) {
            return ['error' => 1, 'msg' => 'Product not found'];
        }

        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $amount = money_format('%.2n', $product->price);

        $user = \App\User::getById($order->userid);

        return response()->json(
            [
                'error' => 0,
                'tsaNumber' => $user->distid,
                'voucher' => $voucher,
                'orderId' => $order->id,
                'fullName' => sprintf('%s %s', $user->firstname, $user->lastname),
                'amount' => $amount
            ]
        );
    }
}
