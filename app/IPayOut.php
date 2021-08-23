<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPayOut extends Model
{
    protected $table = 'ipayout_user';
    protected $fillable = ['user_id', 'transaction_id'];
    const ITEM_DESCRIPTION = 'Fund deposit from https://myibuumerang.com/';
    public static function curl($requestPayload)
    {
        $requestPayload['MerchantGUID'] = \Config::get('api_endpoints.MerchantGUID');
        $requestPayload['MerchantPassword'] = \Config::get('api_endpoints.MerchantPassword');
        $request = json_encode($requestPayload);
        try {
            $ch = curl_init(\Config::get('api_endpoints.eWalletAPIURL'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $ips_response = curl_exec($ch);
            curl_close($ch);
            return json_decode($ips_response);
        } catch (\Exception $ex) {
            return json_decode(json_encode(['error' => 1, 'msg' => $ex->getMessage()]));
        }
    }

    public static function addUser($userId, $transactionRefId)
    {
        $hasRec = \App\IPayOut::getIPayoutByUserId($userId);
        if (empty($hasRec)) {
            $rec = new IPayOut();
            $rec->user_id = $userId;
            $rec->transaction_id = $transactionRefId;
            $rec->save();
            return $rec->id;
        }
        return $hasRec;
    }

    public static function getIPayoutByUserId($userId)
    {
        return IPayOut::where('user_id', $userId)->first();
    }
}
