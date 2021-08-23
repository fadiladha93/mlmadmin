<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;

class BinaryTreeEditorController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin_superAdmin');
    }

    public function index() {
        return view('admin.binary_tree_editor.index');
    }

    public function getAllExistingRecs() {
        $req = request();
        $q = $req->q;
        $query = DB::table('binary_plan as a');
        $query->join('users as b', 'b.id', '=', 'a.user_id');
        $query->select('b.id as id', DB::raw("CONCAT(b.distid,' - ',b.username) AS text"));
        $recs = $query->where(function ($sq) use ($q) {
                    $sq->where('b.distid', 'ilike', $q . "%")
                            ->orWhere('b.username', 'ilike', $q . "%");
                })->paginate(10);
        return $recs->toJson();
    }

    public function getAllActiveDistributors() {
        $req = request();
        $q = $req->q;
        $query = DB::table('users');
        $query->select('id as id', DB::raw("CONCAT(distid,' - ',username) AS text"));
        $query->where('usertype', \App\UserType::TYPE_DISTRIBUTOR);
        $query->where('account_status', \App\User::ACC_STATUS_APPROVED);
        $recs = $query->where(function ($sq) use ($q) {
                    $sq->where('distid', 'ilike', $q . "%")
                            ->orWhere('username', 'ilike', $q . "%");
                })->paginate(10);
        return $recs->toJson();
    }

    public function replace() {
        $req = request();
        $fromId = $req->from_id;
        $toId = $req->to_id;
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $replaceWith = DB::table('users as a')
                ->join('users as b', 'a.sponsorid', '=', 'b.distid')
                ->select('a.created_dt', 'b.id as sponsorid')
                ->where('a.id', $toId)
                ->first();
        $t = explode(" ", $replaceWith->created_dt);
        $enrolled_at = $t[0] . " 00:00:00";
        // update tree
        DB::table('binary_plan')
                ->where('user_id', $fromId)
                ->update([
                    'user_id' => $toId,
                    'enrolled_at' => $enrolled_at,
                    'sponsor_id' => $replaceWith->sponsorid
        ]);
        return response()->json(['error' => 0, 'msg' => "Replaced"]);
    }

    public function search() {
        $req = request();
        $userId = $req->user_id;
        if (\utill::isNullOrEmpty($userId)) {
            return response()->json(['error' => 1, 'msg' => "Select a distributor"]);
        }
        // get downline
        $downlineRecs = array();
        $rec = DB::table('binary_plan')
                ->where('user_id', $userId)
                ->first();
        if (empty($rec)) {
            return response()->json(['error' => 1, 'msg' => "Selected distributor not found at tree"]);
        }
        //
        $childs = $this->getDownlineUsers($userId);
        foreach($childs as $child)
        {
            array_push($downlineRecs, $child->user_id);
        }
        return response()->json(['error' => 0, 'msg' => $userId]);
    }

    private function getDownlineUsers($parentId) {
        return DB::table('binary_plan')
                        ->where('parent_id', $parentId)
                        ->get();
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'from_id' => 'required',
                    'to_id' => 'required',
                        ], [
                    'from_id.required' => 'Distributor to replace is requied',
                    'to_id.required' => 'Distributor replace with is required',
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
            // check if the replace with is already in the tree
            $count = DB::table('binary_plan')
                    ->where('user_id', $req->to_id)
                    ->count();
            if ($count > 0) {
                $valid = 0;
                $msg = "Replace with distributor already exist on the tree";
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

}
