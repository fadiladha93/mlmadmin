<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use Validator;

class MailTemplateController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin');
    }

    public function install() {
        \App\MailTemplate::install();
        // dd('done');
    }

    public function index() {
        $d = array();
        return view('admin.mail_template.index')->with($d);
    }

    public function getDataTable() {
        $query = DB::table('mail_templates');
        return DataTables::of($query)->toJson();
    }

    public function frmEdit($recId) {
        $d = array();
        $d['rec'] = DB::table('mail_templates')
                ->where('id', $recId)
                ->first();
        return view('admin.mail_template.frmEdit')->with($d);
    }

    public function saveRec() {
        $req = request();
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            $recId = $req->rec_id;
            $rec = \App\MailTemplate::find($recId);
            $rec->subject = $req->subject;
            $rec->content = $req->content;
            $rec->is_active = $req->is_active == "on" ? 1 : 0; 
            $rec->save();
            return response()->json(['error' => 0, 'msg' => "Saved"]);
        }
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'subject' => 'required',
                    'content' => 'required',
                        ], [
                    'subject.required' => 'Subject is required',
                    'content.required' => 'Content is required',
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
