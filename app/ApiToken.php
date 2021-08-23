<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class ApiToken extends Model {

    protected $table = "api_token";
    public $timestamps = false;

    public static function getAll() {
        return DB::table("api_token as a")
                        ->select('a.*', 'b.firstname', 'b.lastname')
                        ->join("users as b", "a.generated_by", "=", "b.id")
                        ->get();
    }

    public static function generateNewToken() {
        $r = new ApiToken();
        $r->token = \utill::getRandomString(20);
        $r->is_active = 1;
        $r->generated_on = \utill::getCurrentDateTime();
        $r->generated_by = Auth::user()->id;
        $r->remarks = "";
        $r->save();
    }

    public static function toggleActive($recId) {
        $rec = ApiToken::find($recId);
        $rec->is_active = $rec->is_active == 1 ? 0 : 1;
        $rec->save();
    }

    public static function isValidToken($token) {
        // this token is hardcode to communicate with enrollement site
        if($token == "KBT49D2QGM8UGLQT6U84")
            return true;
        //
        $count = DB::table('api_token')
                ->where('token', $token)
                ->where('is_active', 1)
                ->count();
        return $count > 0;
    }

}
