<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BinaryPermission extends Model {

    protected $table = "binary_permission";
    public $timestamps = false;

    public static function isPermit($distId) {
        $rec = DB::table('binary_permission')
                ->first();
        $mode = $rec->mode;
        if ($mode == "Manual") {
            $permitTo = $rec->permit_to;
            if (strpos($permitTo, $distId) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            $count = DB::table('binary_plan as a')
                    ->join('users as b', 'a.user_id', '=', 'b.id')
                    ->where('b.distid', $distId)
                    ->count();
            if($count > 0)
                return true;
            else
                return false;
        }
    }

}
