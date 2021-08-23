<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    public $timestamps = false;

    const TYPE_BILLING = 1;
    const TYPE_SHIPPING = 2;
    const TYPE_REGISTRATION = 3;

    protected $fillable = [
        'userid',
        'addrtype',
        'primary',
        'address1',
        'address2',
        'city',
        'stateprov',
        'postalcode',
        'countrycode',
        'apt'
    ];

    public static function getRec($userId, $addressType, $isPrimary = 1)
    {
        return DB::table('addresses')
            ->where('userid', $userId)
            ->where('addrtype', $addressType)
            ->where('primary', $isPrimary)
            ->first();

    }


    public static function getRecByAddType($userId, $addressType)
    {
        return DB::table('addresses')
            ->where('userid', $userId)
            ->where('addrtype', $addressType)
            ->first();
    }

    public static function getById($id)
    {
        return self::find($id);
    }

    public static function addNewRecSecondaryAddressTvUser($userId, $addressType, $isPrimary, $req)
    {
        $rec = new Address();
        $rec->userid = $userId;
        $rec->addrtype = $addressType;
        $rec->primary = $isPrimary;
        $rec->address1 = $req->address1;
        $rec->address2 = $req->address2;
        $rec->city = $req->city;
        $rec->stateprov = $req->stateprov;
        $rec->postalcode = $req->postalcode;
        $rec->countrycode = $req->countrycode;
        $rec->apt = $req->apt;
        $rec->save();
        return $rec->id;
    }

    public static function addNewRecSecondaryAddress($userId, $addressType, $isPrimary, $req)
    {
        $rec = new Address();
        $rec->userid = $userId;
        $rec->addrtype = $addressType;
        $rec->primary = $isPrimary;
        $rec->address1 = $req->address1;
        $rec->address2 = $req->has('address2') ? $req->address2 : "";
        $rec->city = $req->city;
        $rec->stateprov = $req->stateprov;
        $rec->postalcode = $req->postalcode;
        $rec->countrycode = $req->countrycode;
        $rec->apt = $req->apt;
        $rec->save();
        return $rec->id;
    }

    public static function updateRecByAddType($userId, $addressType, $req)
    {
        $mode = "update";
        $address_rec = "";
        $rec = Address::where('userid', $userId)
            ->where('addrtype', $addressType)
            ->first();

        if (empty($rec)) {
            $rec = new Address();
            $rec->userid = $userId;
            $rec->addrtype = $addressType;
            $mode = "add";
        } else {
            $address_rec = clone $rec;
        }
        //
        $rec->address1 = $req->address1;
        $rec->address2 = $req->has('address2') ? $req->address2 : "";
        $rec->city = $req->city;
        $rec->stateprov = $req->stateprov;
        $rec->postalcode = $req->postalcode;
        $rec->countrycode = $req->countrycode;
        $rec->apt = $req->apt;
        $rec->save();

        if ($mode == "update") {
            \App\UpdateHistory::addressUpdate($rec->id, $address_rec, $req);
        } else {
            \App\UpdateHistory::addressAdd($userId, $rec->id, 0);
        }
        return $rec->id;
    }

    public static function updateRec($userId, $addressType, $isPrimary, $req)
    {
        $mode = "update";
        $address_rec = "";
        $rec = Address::where('userid', $userId)
            ->where('addrtype', $addressType)
            ->where('primary', $isPrimary)
            ->first();

        if (empty($rec)) {
            $rec = new Address();
            $rec->userid = $userId;
            $rec->addrtype = $addressType;
            $rec->primary = $isPrimary;
            $mode = "add";
        } else {
            $address_rec = clone $rec;
        }
        //
        $rec->address1 = $req->address1;
        $rec->address2 = $req->has('address2') ? $req->address2 : "";
        $rec->city = $req->city;
        $rec->stateprov = $req->stateprov;
        $rec->postalcode = $req->postalcode;
        $rec->countrycode = $req->countrycode;
        $rec->apt = $req->apt;
        $rec->save();

        if ($mode == "update") {
            \App\UpdateHistory::addressUpdate($rec->id, $address_rec, $req);
        } else {
            \App\UpdateHistory::addressAdd($userId, $rec->id, $isPrimary);
        }
        return $rec->id;
    }

    public static function createEmptyRec($userId, $addressType, $isPrimary)
    {
        $rec = new Address();
        $rec->userid = $userId;
        $rec->addrtype = $addressType;
        $rec->primary = $isPrimary;
        $rec->save();
        return $rec->id;
    }

    public static function deleteSecondary($userId, $addressType)
    {
        return DB::table('addresses')
            ->where('userid', $userId)
            ->where('addrtype', $addressType)
            ->where('primary', 0)
            ->delete();
    }

    public static function addSecondaryAddress($userId, $addressType, $req)
    {
        //
        $rec = new Address();
        $rec->userid = $userId;
        $rec->address1 = $req->address1;
        $rec->address2 = $req->has('address2') ? $req->address2 : "";
        $rec->city = $req->city;
        $rec->stateprov = $req->stateprov;
        $rec->postalcode = $req->postalcode;
        $rec->countrycode = $req->countrycode;
        $rec->apt = $req->apt;
        $rec->addrtype = $addressType;
        $rec->primary = 0;
        $rec->save();

        \App\UpdateHistory::addressAdd($userId, $rec->id, 0);

        return $rec->id;
    }

    public static function getBillingAddress($userId)
    {
        return DB::table('addresses')
            ->where('userid', $userId)
            ->where('addrtype', 1)
            ->first();
    }

    public static function getBillingAddresses($userId)
    {
        return DB::table('addresses')
            ->where('userid', $userId)
            ->where('addrtype', Address::TYPE_BILLING)
            ->orderByDesc('id')
            ->get();
    }

    public static function getFilteredBillingAddresses($userId)
    {
        return DB::table('addresses')
            ->where('userid', $userId)
            ->where('addrtype', Address::TYPE_BILLING)
            ->whereNotNull('address1')
            ->whereNotNull('city')
            ->whereNotNull('stateprov')
            ->whereNotNull('countrycode')
            ->whereNotNull('postalcode')
            ->orderByDesc('id')
            ->get();
    }

    public static function getSummary($address)
    {
        $summary = $address->address1;

        if ($address->address2 != null) {
            $summary .= ", " . $address->address2;
        }

        if (!empty($address->apt)) {
            $summary .= " , Apt #" . $address->apt;
        }

        $summaryParts = [
            $address->city,
            $address->stateprov,
            Country::getCountryByCode($address->countrycode)->country,
            $address->postalcode,
        ];

        $summary .= implode(", ", $summaryParts);

        return $summary;
    }
}
