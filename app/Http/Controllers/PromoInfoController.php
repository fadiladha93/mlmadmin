<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class PromoInfoController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'showPromo',
                'showEventPromo'
        ]]);
        $this->middleware('auth.affiliate');
    }

    public function showPromo() {
        $d = array();
        $promo = \App\PromoInfo::getPromoDetail();
        if ($promo->side_banner_is_active == 1) {
            $d['promo'] = $promo;
            return view('affiliate.promo_info.show_promo')->with($d);
        } else
            return redirect('/');
    }

    public function configPage() {
        $d = array();
        $d['rec'] = \App\PromoInfo::where('id', 1)->first();
        return view('admin.promo_info.config_page')->with($d);
    }

    public function validatePromoRec() {
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            return response()->json(['error' => 0]);
        }
    }

    public function savePromo() {
        $req = request();
        $rec = \App\PromoInfo::where('id', 1)->first();
        //
        $rec->top_banner_url = $req->top_banner_url;
        $rec->top_banner_is_active = $req->top_banner_is_active == "on" ? 1 : 0;
        //
        /*
        removed on 7/29/2020, card trello https://trello.com/c/L5rUZCoM/540-admin-allow-admin-to-upload-promo-image
        $rec->side_banner_title = $req->side_banner_title;
        $rec->side_banner_short_desc = $req->side_banner_short_desc;
        $rec->side_banner_long_desc = $req->side_banner_long_desc;
        $rec->side_banner_is_active = $req->side_banner_is_active == "on" ? 1 : 0;
        */
        //
        // save top promo image
        if ($req->hasFile('top_banner_img')) {
            $image = $req->file('top_banner_img')->store('/images/banner');
            if(!empty($rec->top_banner_img)) {
                \Storage::delete($rec->top_banner_img);
            }
            $rec->top_banner_img = $image;
        }
        // save side promo image
        /*
        removed on 7/29/2020, card trello https://trello.com/c/L5rUZCoM/540-admin-allow-admin-to-upload-promo-image
        if ($req->hasFile('side_banner_img')) {
            $getimageName = 'side_banner.' . $req->side_banner_img->getClientOriginalExtension();
            $req->side_banner_img->move(public_path('/promo'), $getimageName);
            //
            $rec->side_banner_img = $getimageName;
        }
        */
        //
        $rec->save();
        //
        return redirect('/promo-info');
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'top_banner_url' => 'url|nullable',
                        ], [
                    'top_banner_url.url' => 'Invalid top banner URL',
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

    public function showEventPromo() {
        $d = array();
        $promo = \App\PromoInfo::getPromoDetail();
        if ($promo->side_banner_is_active == 1) {
            $d['promo'] = $promo;
            return view('affiliate.shop.shop')->with($d);
        } else
            return redirect('/');
    }
}
