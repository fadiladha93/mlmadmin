<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PreEnrollmentSelection extends Model {

    protected $table = "pre_enrollment_selection";
    public $timestamps = false;

    public static function getProductId($userId) {
        $rec = DB::table('pre_enrollment_selection')
                ->select('productId')
                ->where('userId', $userId)
                ->first();
        if (empty($rec))
            return null;
        else
            return $rec->productId;
    }

    public static function addNewRec($userId, $productId) {
        $rec = PreEnrollmentSelection::where('userId', $userId)->first();
        if (empty($rec)) {
            $rec = new PreEnrollmentSelection();
            $rec->userId = $userId;
        }
        $rec->productId = $productId;
        $rec->save();
    }

    public static function deleteRec($userId) {
        DB::table('pre_enrollment_selection')
                ->where('userId', $userId)
                ->delete();
    }

    public static function getGroupCount() {
        $q = DB::table('pre_enrollment_selection');
        $q->select('productId', DB::raw('count(1) as total'));
        $q->groupBy('productId');
        return $q->get();
    }

    public static function getGroupCountByUser($distid) {
        return DB::table('pre_enrollment_selection as a')
                        ->select('a.productId', DB::raw('count(1) as total'))
                        ->join('users as b', 'b.id', '=', 'a.userId')
                        ->where('b.sponsorid', $distid)
                        ->groupBy('a.productId')
                        ->get();
    }

}
