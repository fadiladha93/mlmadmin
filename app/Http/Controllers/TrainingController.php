<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;
use DB;

class TrainingController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'trainingVideoShop',
        ]]);
        $this->middleware('auth.affiliate');
    }

    public function trainingVideoShop() {
        $a = $this->checkVideoAccess();
        $d = array();
        $promo = \App\PromoInfo::getPromoAll(2);
        if ($promo->side_banner_is_active == 1) {
            $d['promo'] = $promo;
            $d['useraccess'] = $a;
            return view('affiliate.shop.videoshop')->with($d);
        } else
            return redirect('/');
    }

    public function checkVideoAccess() {
        $userId = Auth::user()->id;
        return DB::select(DB::raw("SELECT
                        oi.orderid,
                        oi.productid,
                        oi.quantity,
                        users.id,
                        users.username,
                        users.firstname,
                        users.lastname,
                        users.email,
                        users.distid
                    FROM \"orderItem\" AS oi
                    JOIN orders ord on ord.id = oi.orderid
                    JOIN users ON users.id = ord.userid
                    WHERE
                        oi.productid = 56
                        AND users.id = ".$userId." "));

    }


}
