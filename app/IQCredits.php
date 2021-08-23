<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class IQCredits extends Model {

    protected $table = "iq_credits";
    public $timestamps = false;

    public static function isValid($iqId) {
        $rec = DB::table('iq_credits')
                ->where('legacyid', $iqId)
                ->where('is_used', 0)
                ->first();
        if (empty($rec))
            return false;
        else
            return true;
    }

    public static function getCreditAmount($iqId) {
        $rec = DB::table('iq_credits')
                ->select('credit_amt')
                ->where('legacyid', $iqId)
                ->first();
        if (empty($rec))
            return 0;
        else
            return $rec->credit_amt;
    }

    public static function setUsed($iqId) {
        DB::table('iq_credits')
                ->where('legacyid', $iqId)
                ->update([
                    'is_used' => 1
        ]);
    }

}
