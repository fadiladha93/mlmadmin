<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;

class BulkEmailController extends Controller {

    public function __construct() {
        set_time_limit(0);
        $this->middleware('auth.admin');
    }

    public function index() {
        $d['recs'] = DB::table('v_bulk_email')
                ->get();
        return view('admin.bulk_email.index')->with($d);
    }

    public function frmNew() {
        \App\MailGunMailList::updateListCount();
        //
        $d = array();
        $d['to'] = \App\MailGunMailList::orderBy('id', 'asc')->get();
        return view('admin.bulk_email.frmNew')->with($d);
    }

    public function send() {
        $req = request();
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        \App\BulkEmail::addNew($req);
        // send
        foreach ($req->to as $to) {
            \MyMail::sendBulkMail($to, $req->subject, $req->content);
        }
        //
        return response()->json(['error' => '0', 'url' => url('/bulk-email')]);
    }

    public function viewMail($recId) {
        \App\MailGunMailList::updateListCount();
        //
        $d = array();
        $d['recId'] = $recId;
        $d['to'] = \App\MailGunMailList::orderBy('id', 'asc')->get();
        $d['rec'] = DB::table('bulk_mails')
                ->where('id', $recId)
                ->first();
        return view('admin.bulk_email.frmView')->with($d);
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'to' => 'required',
                    'subject' => 'required',
                    'content' => 'required',
                        ], [
                    'to.required' => 'To is required',
                    'subject.required' => 'Subject is required',
                    'content.required' => 'Content ID is required',
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
