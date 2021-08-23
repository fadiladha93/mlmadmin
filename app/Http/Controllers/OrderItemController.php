<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class OrderItemController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin');
        $this->middleware(function ($request, $next) {
            if (!(\App\AdminPermission::add_edit_refund_orders_and_order_items())) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                }

                return redirect('/');
            }

            return $next($request);
        });
    }

    public function dlgUpdate($itemId) {
        $d = array();
        $d['item'] = \App\OrderItem::getById($itemId);
        $d['prods'] = \App\Product::getAll();
        return view('admin.order_item.dlg_edit')->with($d);
    }

    public function updateRec() {
        $req = request();
        $orderRec = \App\Order::getById($req->order_id);
        //
        $rec = \App\OrderItem::getById($req->item_id);
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        \App\OrderItem::updateRec($req->item_id, $rec, $req);
        return response()->json(['error' => '0', 'url' => 'reload']);
    }

    public function dlgNew($orderId) {
        $d = array();
        $d['order_id'] = $orderId;
        $d['prods'] = \App\Product::getAll();
        return view('admin.order_item.dlg_new')->with($d);
    }

    public function addRec() {
        $req = request();
        $orderRec = \App\Order::getById($req->order_id);
        //
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        //
        \App\OrderItem::addNew(
            $req->order_id,
            $req->productid,
            1,
            $req->itemprice,
            $req->bv,
            $req->qv,
            $req->cv,
            true,
            null,
            $req->qc,
            $req->ac
        );

        return response()->json(['error' => '0', 'url' => 'reload']);
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'productid' => 'required',
                    'itemprice' => 'required|numeric',
                    'bv' => 'required|numeric',
                    'qv' => 'required|numeric',
                    'cv' => 'required|numeric',
                    'qc' => 'nullable|numeric',
                    'ac' => 'nullable|numeric',
                        ], [
                    'productid.required' => 'Product is required',
                    'itemprice.required' => 'Item price is required',
                    'itemprice.numeric' => 'Item price must be numeric',
                    'bv.required' => 'BV is required',
                    'bv.numeric' => 'BV must be numeric',
                    'qv.required' => 'QV is required',
                    'qv.numeric' => 'QV must be numeric',
                    'cv.required' => 'CV is required',
                    'cv.numeric' => 'CV must be numeric',
                    'qc.numeric' => 'QC must be numeric',
                    'ac.numeric' => 'AC must be numeric',
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

}
