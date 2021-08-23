<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chargeback extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'date',
        'amount',
        'currency',
        'chargeback_merchant_id',
        'merchant'
    ];
}
