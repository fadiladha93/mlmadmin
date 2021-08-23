<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderConversion extends Model
{
    protected $table = "order_conversions";

    public $fillable = [
        'order_id',
        'display_amount',
        'original_amount',
        'original_currency',
        'converted_amount',
        'converted_currency',
        'exchange_rate',
        'created_at',
        'updated_at',
        'expires_at'
    ];

    public $dates = [
        'expires_at'
    ];

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public static function setOrderId($orderConversionId, $orderId)
    {
        $orderConversion = static::query()->find($orderConversionId);
        $orderConversion->order_id = $orderId;
        $orderConversion->save();
    }
}
