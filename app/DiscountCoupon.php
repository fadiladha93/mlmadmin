<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model {

    protected $table = "discount_coupon";
    public $timestamps = false;



    public static function getNewCode() {
        $code = \utill::getRandomString(6);
        if (self::isExist($code))
            return self::getNewCode();
        else
            return $code;
    }

    public static function addNew($req) {
        $r = new DiscountCoupon();
        $r->code = $req->code;
        $r->discount_amount = $req->amount;
        $r->generated_for = isset($req->generated_for) ? $req->generated_for : 0;
        $r->is_used = 0;
        $r->created_at = date('Y-m-d');
        $r->is_active = 1;
        $r->product_id = (!empty($req->product_id_ref) ? $req->product_id_ref : null);
        $r->save();
        return $r->id;
    }

    public static function getDiscountAmount($code) {
        $rec = DB::table('discount_coupon')
                ->select('discount_amount')
                ->where('code', $code)
                ->where('is_used', 0)
                ->where('is_active', 1)
                ->first();
        if (empty($rec))
            return 0;
        else
            return $rec->discount_amount;
    }

    private static function isExist($code) {
        $count = DB::table('discount_coupon')
                ->where('code', $code)
                ->count();
        return $count > 0;
    }

    public static function markAsUsed($usedById, $code, $type = null, $orderId = null)
    {
        if ($type == 'code') {
            $coupon = \App\DiscountCoupon::select('*')->where('code', $code)->where('is_active', 1)->where('is_used', 0)->first();
            if (!empty($coupon)) {
                DB::table('discount_coupon')
                    ->where('id', $coupon->id)
                    ->update([
                        'used_by' => $usedById,
                        'is_used' => 1
                    ]);
                \App\Order::where('id', $orderId)->update(['coupon_code' => $coupon->id]);
            }
        } else {
            DB::table('discount_coupon')
                ->where('code', $code)
                ->update([
                    'used_by' => $usedById,
                    'is_used' => 1
                ]);
        }

    }

    /**
     * This will mark the coupon as used and decrease the amount on the coupon according on the use
     *
     * @param $usedById
     * @param $code
     * @param null $type
     * @param null $orderId
     */
    public static function applyCoupon($usedById, $code, $type = null, $orderId = null, $usedAmount)
    {
        if ($type == 'code') {
            $coupon = \App\DiscountCoupon::select('*')->where('code', $code)->where('is_active', 1)->where('is_used', 0)->first();
            if (!empty($coupon)) {
                DB::table('discount_coupon')
                    ->where('id', $coupon->id)
                    ->update([
                        'used_by' => $usedById,
                        'is_used' => 1,
                        'discount_amount' => $coupon->discount_amount - $usedAmount
                    ]);
                \App\Order::where('id', $orderId)->update(['coupon_code' => $coupon->id]);
            }
        } else {
            DB::table('discount_coupon')
                ->where('code', $code)
                ->update([
                    'used_by' => $usedById,
                    'is_used' => 1
                ]);
        }

    }

    public static function cancelDiscountCode($recId){
        DB::table('discount_coupon')
            ->where('id',$recId)
            ->delete();
    }

    /**
     * @param $voucher
     * @return array|\Illuminate\Database\Query\Builder|mixed
     */
    public static function getOrder($voucher) {
        $discountCoupon = self::where('code', $voucher)
            ->where('is_used', 0)
            ->where('is_active', 1)
            ->first();

        if (!$discountCoupon) {
            return ['error' => 1, 'msg' => 'Discount coupon not found'];
        }

        $orderItem = \App\OrderItem::where('discount_voucher_id', $discountCoupon->id)->first();

        if (!$orderItem) {
            return ['error' => 1, 'msg' => 'Order Item not found'];
        }

        $order = \App\Order::find($orderItem->orderid);

        if (!$order) {
            return ['error' => 1, 'msg' => 'No order found with this voucher code'];
        }

        $order->product_id = $discountCoupon->product_id;

        return $order;
    }

    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
