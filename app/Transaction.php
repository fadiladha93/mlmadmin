<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Transaction extends Model {
    
    public $timestamps = false;

    public static function addNew($tran) {
        if (!self::recExist($tran->id)) {
            $rec = new Transaction();
            $rec->customer_id = Customer::getCustomerId($tran->customer);
            $rec->total = $tran->total;
            //
            $rec->product_sku = $tran->product->sku;
            $rec->product_name = $tran->product->name;
            $rec->product_price = $tran->product->price;
            $rec->product_cv = $tran->product->cv;
            $rec->product_pv = $tran->product->pv;
            $rec->product_qv = $tran->product->qv;
            //
            $rec->bill_firstname = $tran->billing_address->firstname;
            $rec->bill_last_name = $tran->billing_address->last_name;
            $rec->bill_address_1 = $tran->billing_address->address_1;
            $rec->bill_address_2 = $tran->billing_address->address_2;
            $rec->bill_city = $tran->billing_address->city;
            $rec->bill_state = $tran->billing_address->state;
            $rec->bill_zip = $tran->billing_address->zip;
            $rec->bill_country = $tran->billing_address->country;
            $rec->bill_phone = $tran->billing_address->phone;
            $rec->bill_fax = $tran->billing_address->fax;
            $rec->bill_cc_number = $tran->billing_address->cc_number;
            $rec->bill_cc_exp = $tran->billing_address->cc_exp;
            //
            $rec->store_trans_id = $tran->id;
            //
            $rec->save();
        }
    }

    public static function getRecById($recId) {
        return DB::table('transactions')
                        ->where('id', $recId)
                        ->first();
    }

    private static function recExist($storeTransId) {
        $count = DB::table('transactions')
                ->where('store_trans_id', $storeTransId)
                ->count();
        if ($count > 0)
            return true;
        else
            return false;
    }

}
