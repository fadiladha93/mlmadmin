<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use DB;

class BulkEmail extends Model {

    protected $table = "bulk_mails";
    public $timestamps = false;

    public static function addNew($req) {
        $r = new BulkEmail();
        $r->to = implode("<br/>", $req->to);
        $r->subject = $req->subject;
        $r->content = $req->content;
        $r->sent_by = Auth::user()->id;
        $r->sent_on = \utill::getCurrentDateTime();
        $r->save();
    }

    public static function updateRec($recId, $req) {
        $r = BulkEmail::find($recId);
        $r->subject = $req->subject;
        $r->content = $req->content;
        $r->save();
    }

}
