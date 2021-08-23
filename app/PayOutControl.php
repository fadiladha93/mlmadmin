<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class PayOutControl extends Model
{
    protected $table = 'payout_type_country';
    public $fillable = ['country_id', 'type'];

    public static function getPayoutTypeByCountryCode($countryCode)
    {
        $country = \App\Country::getCountryByCode($countryCode);
        $payoutType = null;
        if (!empty($country)) {
            $payoutType = \App\PayOutControl::getPayuotTypeByCountryId($country);
        }
        return $payoutType;
    }

    public static function getPayuotTypeByCountryId($country)
    {
        return DB::table('payout_type_country')->select('*')->where('country_id', $country->id)->first();
    }
}
