<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayAP extends Model {

    public static function verifyAccountNumber($userId, $phone_number) {
        $response = new \PayAP();
        $logId = \App\Helper::logApiRequests($userId, 'PAYAP - verify account', 'checkPhoneNumber', $phone_number);
        try {
            $jsonBody = $response->curl("checkPhoneNumber", $phone_number);
        } catch (\Exception $exception) {
            $jsonBody = (string) $exception->getResponse()->getBody(true);
        }
        $response = json_decode($jsonBody);
        \App\Helper::logApiResponse($logId->id, $jsonBody);
        return array('response' => $response, 'request' => array("phoneNumber" => $phone_number));
    }

    public static function makePayment($userId, $phoneNumber, $currency, $amount, $memo, $signature) {
        $response = new \PayAP();
        $requestPayload = array(
            "phone_number" => $phoneNumber,
            "currency" => $currency,
            "amount" => $amount,
            "memo" => $memo,
            "signature" => $signature
        );
        $logId = \App\Helper::logApiRequests($userId, 'PAYAP - make payment', 'makePayment', $requestPayload);
        try {
            $jsonBody = $response->curl("makePayment", $requestPayload);
        } catch (\Exception $exception) {
            $jsonBody = (string) $exception->getResponse()->getBody(true);
        }
        $response = json_decode($jsonBody);
        \App\Helper::logApiResponse($logId->id, $jsonBody);
        return array('response' => $response, 'request' => $requestPayload);
    }

}
