<?php

namespace App;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table = "orders";
    public $timestamps = false;

    const ORDER_ACTIVE = 1;
    const ORDER_STATUS_REFUND = 6;
    const ORDER_STATUS_PARTIAL_REFUND = 9;
    const ORDER_STATUS_REFUNDED = 10;
    const ORDER_STATUS_PARTIALLY_REFUNDED = 11;
    const ORDER_STATUS_CHARGEBACK = 12;
    const ORDER_STATUS_CHARGED_BACK = 13;
    const ORDER_STATUS_REFUNDED_AND_CHARGED_BACK = 14;

    public $fillable = [
        'userid',
        'statuscode',
        'ordersubtotal',
        'ordertax',
        'ordertotal',
        'orderbv',
        'orderqv',
        'ordercv',
        'trasnactionid',
        'updated_at',
        'created_at',
        'payment_methods_id',
        'shipping_address_id',
        'inv_id',
        'created_date',
        'created_time',
        'processed',
        'coupon_code',
        'order_refund_ref',
        'order_chargeback_ref',
        'created_dt',
        'orderqc',
        'orderac'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'userid');
    }


    public function orderItems()
    {
        return $this->hasMany('App\OrderItem', 'orderid', 'id');
    }

    public function conversion()
    {
        return $this->hasOne('App\Models\OrderConversion');
    }


    /**
     * @param int $userId
     * @param double $subtotal
     * @param double $orderTotal
     * @param int $orderBV
     * @param int $orderQV
     * @param int $orderCV
     * @param $transactionId
     * @param $paymentMethodId
     * @param $shippingAddressId
     * @param $invId
     * @param string $createdDate
     * @param string $discountCode
     * @param null $orderStatus
     * @param null $order_refund_ref
     * @param int $orderQC
     * @param int $orderAC
     * @param null $isTSBOrder
     * @param null $userPaymentMethodId
     * @return int|mixed
     */
    public static function addNew(
        $userId,
        $subtotal,
        $orderTotal,
        $orderBV,
        $orderQV,
        $orderCV,
        $transactionId,
        $paymentMethodId,
        $shippingAddressId,
        $invId,
        $createdDate = '',
        $discountCode = '',
        $orderStatus = null,
        $order_refund_ref = null,
        $orderQC = 0,
        $orderAC = 0,
        $isTSBOrder = null,
        $userPaymentMethodId = null
    ) {
        $rec = new Order();
        $rec->userid = $userId;
        $rec->statuscode = (empty($orderStatus) ? 1 : $orderStatus);
        $rec->ordersubtotal = $subtotal;
        $rec->ordertotal = $orderTotal;
        $rec->orderbv = $orderBV;
        $rec->orderqv = $orderQV;
        $rec->ordercv = $orderCV;
        $rec->orderqc = $orderQC;
        $rec->orderac = $orderAC;
        $rec->trasnactionid = $transactionId;
        $rec->payment_methods_id = $paymentMethodId;
        $rec->shipping_address_id = $shippingAddressId;
        $rec->inv_id = $invId;
        $rec->coupon_code = $discountCode;
        $rec->order_refund_ref = $order_refund_ref;
        $rec->user_payment_methods_id  = $userPaymentMethodId;
        if (!$isTSBOrder) {
            if (!empty($createdDate)) {
                $rec->created_date = $createdDate;
                $rec->created_dt = $createdDate . " " . \utill::getCurrentTime();
            } else {
                $rec->created_date = \utill::getCurrentDate();
                $rec->created_dt = \utill::getCurrentDateTime();
            }
            $rec->created_time = \utill::getCurrentTime();
        } else {
            $rec->created_date = date("Y-m-d", strtotime($createdDate));
            $rec->created_dt = $createdDate;
            $rec->created_time = date("h:i:s", strtotime($createdDate));
        }
        $rec->save();
        return $rec->id;
    }

    public static function updateRec($orderId, $rec, $req) {
        $createdDt = $req->created_date . " " . \utill::getCurrentTime();
        $r = Order::find($orderId);
        $r->ordertotal = $req->ordertotal;
        $r->ordersubtotal = $req->ordersubtotal;
        $r->orderbv = $req->orderbv;
        $r->orderqv = $req->orderqv;
        $r->ordercv = $req->ordercv;
        $r->orderqc = $req->orderqc;
        $r->orderac = $req->orderac;
        $r->created_date = $req->created_date;
        $r->created_dt = $createdDt;
        $r->save();
        //
        UpdateHistory::orderUpdate($orderId, $rec, $req);

        DB::table('orderItem')
            ->where('orderid', $orderId)
            ->update(['created_dt' => $createdDt]);
    }

    public static function getById($id) {
        return DB::table('orders')
                        ->where('id', $id)
                        ->first();
    }

    /**
     * @param $id
     * @return Order|
     */
    public static function getActiveOrder($id) {
        return Order::query()
                        ->where('id', $id)
                        ->whereIn('statuscode', [self::ORDER_ACTIVE, self::ORDER_STATUS_PARTIALLY_REFUNDED])
                        ->where('order_refund_ref', null)
                        ->first();
    }

    public static function getByUser($id) {
        return DB::table('orders')
            ->where('userid', $id)
            ->where('trasnactionid', 'not like', '%AMB%')
            ->where('trasnactionid', 'not like', '%SOR%')
            // ->orWhereNull ('trasnactionid')
            ->orWhereRaw(DB::raw("userid = ".$id." AND trasnactionid is NULL"))
            ->orderBy('created_dt', 'desc')
            ->get();
    }

    public static function getUserOrder($id) {
        return DB::table('orders')
                        ->where('id', $id)
                        ->where('userid', Auth::user()->id)
                        ->first();
    }

    public static function getThisMonthOrderQV($userId) {
        $monthAgo = date('Y-m-d', strtotime("-1 Months"));
        $rec = DB::table('orders')
                ->selectRaw('sum(orderqv) as qv')
                ->where('userid', $userId)
                ->whereDate('created_dt', '>=', $monthAgo)
                ->first();
        return $rec->qv;
    }

    public static function getOrdersByTsaDateRange($fromDate, $toDate, $type, $distid)
    {
        $userId = \App\User::getByDistId($distid);
        return DB::table('orders')
            ->join('orderItem', 'orders.id', '=', 'orderItem.orderid')
            ->join('products', 'orderItem.productid', '=', 'products.id')
            ->join('producttype', 'products.producttype', '=', 'producttype.id')
            ->select('orders.id', 'orders.created_dt', 'products.productname', 'products.price', 'producttype.typedesc')
            ->whereIn('producttype.typedesc', $type)
            ->where('orders.userid', $userId->id)
            ->whereDate('orders.created_dt', '>=', $fromDate)
            ->whereDate('orders.created_dt', '<=', $toDate)
            ->get();
    }

    public static function orderWithTransactionIdExists($transactionID)
    {
        return static::query()->where('trasnactionid', $transactionID)->count() > 0;
    }

    public function isRefunded()
    {
        $isRefunded = true;
        foreach ($this->orderItems as $orderItem) {
            if (!$orderItem->is_refunded) {
                $isRefunded = false;
                break;
            }
        }

        return $isRefunded;
    }

    public function getStandByOrderItem()
    {
        return OrderItem::query()
            ->where('orderid', $this->id)
            ->where('productid', Product::ID_NCREASE_ISBO)
            ->first();
    }

    public function isPurchasedByVoucher()
    {
        return !empty($this->trasnactionid) && strpos($this->trasnactionid, 'COUPON#') !== false;
    }

    /**
     * @return bool
     */
    public function isVoucherPurchaseOrder()
    {
        if ($this->orderItems->count() !== 1) {
            return false;
        }

        $orderItem = $this->orderItems[0];
        if (!$orderItem->getActiveDiscountCoupon()) {
            return false;
        }

        return true;
    }

    /**
     * @return DiscountCoupon
     */
    public function getAssociatedDiscountCoupon()
    {
        if ($this->orderItems->count() !== 1) {
            return null;
        }

        $orderItem = $this->orderItems[0];
        if (!$discountCoupon = $orderItem->getActiveDiscountCoupon()) {
            return null;
        }

        return $discountCoupon;
    }

    public static function getUserIdsWithSubscriptionRunsPreviousMonth()
    {
        $lastMonthFirstDay = now()->subMonthNoOverFlow()->firstOfMonth();
        $firstDay = $lastMonthFirstDay->format('Y-m-d');
        $lastMonthLastDay = $lastMonthFirstDay->lastOfMonth();

        $results = static::query()->join('orderItem', 'orderItem.orderid', 'orders.id')
            ->whereDate('orders.created_date', '>=', $firstDay)
            ->whereDate('orders.created_date', '<=', $lastMonthLastDay)
            // ->whereIn('orderItem.productid', [11,12,26,33])
            ->where('orderItem.itemprice', '>', 0)
            ->where('orders.statuscode', '=', 1)
            ->groupBy(['orders.userid'])
            ->get(['orders.userid']);

        return array_column($results->all(), 'userid');
    }
}
