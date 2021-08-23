<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargebackMerchant extends Model
{
    protected $fillable = [
        'name',
        'transaction_id',
        'chargeback_date',
        'chargeback_deadline_date',
        'chargeback_amount',
        'chargeback_reason_description',
        'chargeback_subject',
        'chargeback_id',
        'transaction_amount',
        'card_bin',
        'card_last_four',
        'card_brand',
        'card_holder',
        'header_line',
        'data_line'
    ];
}
