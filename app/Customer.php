<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Customer extends Model {

    protected $table = "customers";
    public $timestamps = false;
    protected $fillable = [
        'userid',
        'name',
        'email',
        'mobile',
        'boomerang_code',
        'sor_default_password',
        'sync_with_mailgun',
        'custid'
    ];

    public static function getById($id) {
        return DB::table('customers')
                        ->where('id', $id)
                        ->first();
    }

    public static function addNewRec($req, $userId, $password) {
        $r = new Customer();
        $r->userid = $userId;
        $r->custid = self::getRandomCustomerId();
        $r->name = $req->first_name . " " . $req->last_name;
        $r->email = $req->email;
        $r->mobile = $req->phone;
        $r->boomerang_code = $req->boomerang_code;
        $r->created_date = date('Y-m-d');
        $r->sor_default_password = $password;
        $r->save();
    }

    public static function updateCustomer($customerId, $req) {
        $rec = Customer::find($customerId);
        $rec->name = $req->name;
        $rec->email = $req->email;
        $rec->mobile = $req->mobile;
        $rec->save();
    }

    public static function setCustomerId() {
        $recs = Customer::whereNull('custid')->get();
        foreach ($recs as $rec) {
            $rec->custid = self::getRandomCustomerId();
            $rec->save();
        }
        echo "done";
    }

    public static function getRandomCustomerId() {
        $cid = 'C' . \utill::getRandomString(7, "0123456789");
        //
        $count = DB::table('customers')
                ->where('custid', $cid)
                ->count();

        if ($count > 0) {
            return self::getRandomCustomerId();
        } else {
            return $cid;
        }
    }
}
