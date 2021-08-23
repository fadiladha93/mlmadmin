<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

class BoomerangInvController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
            'dlgInstructions'
        ]]);
    }

    public function setNewTotal() {
        if (!\App\AdminPermission::fn_update_boomerang()) {
            return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
        }
        $req = request();
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        //
        $userId = $req->user_id;
        $rec = \App\BoomerangInv::where('userid', $userId)->first();
        if (!empty($rec)) {
            $b_rec = clone $rec;
            if ($rec->pending_tot > $req->new_boomerang) {
                return response()->json(['error' => 1, 'msg' => "New total boomerang must be greater than pending"]);
            }
            $rec->available_tot = $req->new_boomerang - $rec->pending_tot;
            $rec->save();
            \App\UpdateHistory::boomerangInvUpdate($rec->id, $b_rec, $req);
        } else {
            $n = new \App\BoomerangInv();
            $n->userid = $userId;
            $n->pending_tot = 0;
            $n->available_tot = $req->new_boomerang;
            $n->save();
            \App\UpdateHistory::boomerangInvAdd($n->id);
        }
        //
        return response()->json(['error' => 0, 'url' => 'reload']);
    }

    public function setMaxBoomAvailable() {
        if (!\App\AdminPermission::fn_update_boomerang()) {
            return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
        }
        $request = request();
        $validateResponse = $this->validateMaxAvailable();
        if ($validateResponse['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $validateResponse['msg']]);
        }

        //set is available field
        $userId = $request->user_id;
        $boomerangInventory = \App\BoomerangInv::where('userid', $userId)->first();
        if (!empty($boomerangInventory)) {
            $oldRecord = clone $boomerangInventory;
            $boomerangInventory->max_available = $request->max_available;
            $boomerangInventory->save();
            \App\UpdateHistory::boomerangMaxAvailableInvUpdate($boomerangInventory->id, $oldRecord, $request);
        } else {
            $n = new \App\BoomerangInv();
            $n->userid = $userId;
            $n->pending_tot   = 0;
            $n->available_tot = 0;
            $n->max_available = $request->max_available;
            $n->save();
            \App\UpdateHistory::boomerangInvAdd($n->id);
        }
        //
        return response()->json(['error' => 0, 'msg' => 'Max Boomerang Count Updated!','url' => 'reload']);
    }

    public function dlgInstructions()
    {
        return view('affiliate.boomerangs.dlg_instructions');
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'new_boomerang' => 'required|integer',
                        ], [
                    'new_boomerang.required' => 'New total boomerang is required',
                    'new_boomerang.integer' => 'New total boomerang must be integer'
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

    private function validateMaxAvailable() {
        $req = request();
        $validator = Validator::make(
            $req->all(),
            ['max_available' => 'required|integer'],
            [
                'max_available.required' => 'Max available number is required',
                'max_available.integer' => 'Max available number must be integer'
            ]
        );

        $msg = '';
        $valid = 1;
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        }

        if ($valid && $req->max_available != 0 && $req->max_available <= \App\BoomerangInv::MAX_BUUMERANGS_ALLOWED) {
            $valid = 0;
            $msg .= "<div>Max Available Boomerang must be 0 or greater than " . \App\BoomerangInv::MAX_BUUMERANGS_ALLOWED ."</div>";
        }

        return [
            'valid' => $valid,
            'msg'   => $msg
        ];
    }
}
