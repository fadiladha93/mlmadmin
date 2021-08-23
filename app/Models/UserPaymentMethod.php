<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserPaymentAddress;

class UserPaymentMethod extends Model
{
    use SoftDeletes;

    public $table = 'user_payment_methods';

    public $fillable = [
        'user_id',
        'user_payment_address_id',
        'first_name',
        'last_name',
        'card_token',
        'is_primary',
        'active',
        'deleted_at',
        'created_at',
        'updated_at',
        'cvv',
        'expiration_month',
        'expiration_year',
        'pay_method_type',
        'is_deleted'
    ];

    public function address()
    {
        return $this->hasOne('App\Models\UserPaymentAddress', 'id', 'user_payment_address_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function getMaskedCreditCardAttribute()
    {
        $token = substr($this->card_token, 0, 6) . str_repeat("X", 6) . substr($this->card_token, -4);

        return $token;
    }

    public static function getPaymentsWithNumbers($first_numbers, $last_numbers)
    {

        $payments = UserPaymentMethod::where('card_token', 'like', "{$first_numbers}%")
            ->where('card_token', 'like', "%{$last_numbers}")
            ->with("user")
            ->withCount('orders')
            ->withTrashed()
            ->get();

        return $payments;
    }

    public function orders()
    {
        return $this->hasMany(\App\Order::class, 'user_payment_methods_id', 'id');
    }

}
