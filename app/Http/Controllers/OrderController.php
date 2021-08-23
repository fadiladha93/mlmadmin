<?php

namespace App\Http\Controllers;

use App\Country;
use App\PaymentMethod;
use App\PaymentMethodType;
use App\Product;
use App\ProductType;
use App\User;
use DataTables;
use DB;
use Faker\Provider\Payment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Validation\Rule;
use Validator;

class OrderController extends Controller {
    public function __construct() {
        $this->middleware('auth.admin');
//        $this->middleware(function ($request, $next) {
//            if (!(\App\User::admin_super_admin() ||
//                    \App\User::admin_cs_manager() ||
//                    \App\User::admin_cs_exec()
//                    )) {
//                if ($request->ajax() || $request->wantsJson()) {
//                    return response('Unauthorized.', 401);
//                } else {
//                    return redirect('/');
//                }
//            }
//            return $next($request);
//        });
    }

    public function ordersList($from = null, $to = null) {
        $d = array();
        //
        $q = DB::table('vorderuserspaymentmethods');
        $q->select(DB::raw('SUM(ordertotal) AS total_amount'), DB::raw('SUM(ordercv) AS total_cv'));
        if (!\utill::isNullOrEmpty($from) && !\utill::isNullOrEmpty($to)) {
            $q->whereDate('created_dt', '>=', $from);
            $q->whereDate('created_dt', '<=', $to)->first();
        }
        $sum = $q->first();
        //
        $d["from"] = $from;
        $d["to"] = $to;
        $d["total_amount"] = "$" . number_format($sum->total_amount);
        $d["total_cv"] = number_format($sum->total_cv);
        return view('admin.orders.orders')->with($d);
    }

    public function getOrdersDataTable() {
        $req = request();
        $q = DB::table('vorderuserspaymentmethods');
        $q->leftJoin('statuscode', 'statuscode.id', '=', 'vorderuserspaymentmethods.statuscode');
        if (!\utill::isNullOrEmpty($req->from) && !\utill::isNullOrEmpty($req->to)) {
            $q->whereDate('created_dt', '>=', $req->from);
            $q->whereDate('created_dt', '<=', $req->to);
        }
        $query = $q->select('order_id', 'distid', 'statuscode', 'ordercv', 'trasnactionid', 'ordersubtotal', 'ordertotal', 'pay_method_name', 'statuscode.status_desc', 'created_dt');
        return DataTables::of($query)->toJson();
    }

    public function export($sort_col, $asc_desc, $searchFor = null) {
        $req = request();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Orders.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $q = DB::table('vorderuserspaymentmethods');
        $q->leftJoin('statuscode', 'statuscode.id', '=', 'vorderuserspaymentmethods.statuscode');
        $q->select('order_id', 'distid', 'statuscode', 'ordercv', 'ordersubtotal', 'ordertotal', 'pay_method_name', 'statuscode.status_desc', 'created_dt');
        if (!\utill::isNullOrEmpty($req->d_from) && !\utill::isNullOrEmpty($req->d_to)) {
            $q->whereDate('created_dt', '>=', $req->d_from);
            $q->whereDate('created_dt', '<=', $req->d_to);
        }
        if ($searchFor != null) {
            $q->where(function ($sq) use ($searchFor) {
                $sq->where('order_id', 'like', "%" . $searchFor . "%")
                ->orWhere('distid', 'like', "%" . $searchFor . "%")
                ->orWhere('statuscode', 'like', "%" . $searchFor . "%")
                ->orWhere('pay_method_name', 'like', "%" . $searchFor . "%");
            });
        }
        $q->orderBy($sort_col, $asc_desc);
        $recs = $q->get();

        $columns = array('Order ID', 'Distributor', 'Order Status', 'Order CV', 'Order Subtotal', 'Order total', 'Payment Method', 'Date');
        $callback = function () use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->order_id, $rec->distid, $rec->statuscode, $rec->ordercv, $rec->ordersubtotal, $rec->ordertotal, $rec->pay_method_name, $rec->created_dt));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function frmAdd() {
        if (\App\AdminPermission::add_edit_refund_orders_and_order_items()) {
            return view('admin.orders.frmAdd');
        }
        return redirect('/');
    }

    public function createOrder() {
        if (\App\AdminPermission::add_edit_refund_orders_and_order_items()) {
            $req = request();
            $vali = $this->validateRec(true);
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }

            $sponsorId = $req->input('sponsorid');
            $user = \App\User::getByDistId($sponsorId);

            $paymentMethodId = PaymentMethod::getPaymentMethodIdOfPayMethodTypeAdmin($user->id);

            $orderId = \App\Order::addNew(
                $user->id,
                $req->input('ordersubtotal'),
                $req->input('ordertotal'),
                $req->input('orderbv'),
                $req->input('orderqv'),
                $req->input('ordercv'),
                'Admin',
                $paymentMethodId,
                null,
                null,
                $req->input('created_date'),
                '',
                null,
                null,
                $req->input('orderqc'),
                $req->input('orderac')
            );

            \App\UpdateHistory::orderAdd($orderId);

            return response()->json(['error' => 0, 'url' => 'edit-order/' . $orderId]);
        }
        return response()->json(['error' => 1, 'msg' => 'Permission denied']);
    }

    public function frmEdit($orderId) {
        $d = array();
        $rec = DB::table('orders')
                ->where('id', $orderId)
                ->first();
        $d['rec'] = $rec;
        $d['user'] = DB::table('users')
                ->select('distid', 'firstname', 'lastname', 'username')
                ->where('id', $rec->userid)
                ->first();

        $d['items'] = \App\OrderItem::getOrderItem($orderId);
        $d['permit_to_edit'] = \App\AdminPermission::add_edit_refund_orders_and_order_items();
        return view('admin.orders.frmEdit')->with($d);
    }

    public function updateOrder() {
        if (\App\AdminPermission::add_edit_refund_orders_and_order_items()) {
            $req = request();
            $rec = \App\Order::getById($req->order_id);
            $vali = $this->validateRec();
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }
            //
            \App\Order::updateRec($req->order_id, $rec, $req);
            return response()->json(['error' => '0', 'msg' => 'Order Updated']);
        }
        return response()->json(['error' => '1', 'msg' => 'Permission Denied']);
    }

    private function validateRec($order = false) {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'ordertotal' => 'required|numeric',
                    'ordersubtotal' => 'required|numeric',
                    'orderbv' => 'required|numeric',
                    'orderqv' => 'required|numeric',
                    'ordercv' => 'required|numeric',
                    'orderqc' => 'nullable|numeric',
                    'orderac' => 'nullable|numeric',
                        ], [
                    'ordertotal.required' => 'Order total is required',
                    'ordertotal.numeric' => 'Order total must be numeric',
                    'ordersubtotal.required' => 'Order subtotal is required',
                    'ordersubtotal.numeric' => 'Order subtotal must be numeric',
                    'orderbv.required' => 'Order BV is required',
                    'orderbv.numeric' => 'Order BV must be numeric',
                    'orderqv.required' => 'Order QV is required',
                    'orderqv.numeric' => 'Order QV must be numeric',
                    'ordercv.required' => 'Order CV is required',
                    'ordercv.numeric' => 'Order CV must be numeric',
                    'orderqc.numeric' => 'Order QC must be numeric',
                    'orderac.numeric' => 'Order AC must be numeric',
        ]);

        if ($order) {
            $validator->after(function ($validator) use ($req) {
                if (empty($req->input('sponsorid'))) {
                    $validator->errors()->add('sponsorid', 'Sponsor Id is required');
                }

                if (empty($req->input('created_date'))) {
                    $validator->errors()->add('created_date', 'Order date is required');
                }
            });
        }

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
}
