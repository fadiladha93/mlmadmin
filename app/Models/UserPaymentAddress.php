<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentAddress extends Model
{
    public $table = 'user_payment_addresses';

    public $fillable = [
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country_code',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
