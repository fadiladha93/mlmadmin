<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargebackImport extends Model
{
    protected $table = 'chargeback_imports';

    protected $fillable = [
        'chargeback_table_id',
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

    public $timestamps = false;
}
