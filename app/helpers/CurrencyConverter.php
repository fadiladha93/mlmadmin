<?php
namespace App\helpers;

use App\Http\Controllers\Controller;
use App\Models\OrderConversion;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Log;


class CurrencyConverter {
    private const CONVERT_API_URL = 'v1/api/currency/convert';

    public static function convert($amount, $country)
    {
        $baseUrl = env('BILLING_BASE_URL') ;
        $apiToken = env('BILLING_API_TOKEN');

        $client = new Client();
        $baseUrl = 'https://' . $baseUrl . '/';

        Log::info("Query",[
                    'type' => 'country',
                    'locale' => 'en_US',
                    'amount' => $amount,
                    'country' => $country
                ]);

        Log::info("Billing URL -> ".$baseUrl);
        try {
            $result = $client->get($baseUrl . self::CONVERT_API_URL, [
                'query' => [
                    'type' => 'country',
                    'locale' => 'en_US',
                    'amount' => $amount,
                    'country' => $country
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken
                ]
            ]);

            $responseJson = $result->getBody()->getContents();
            return json_decode($responseJson, true);
        } catch (Exception $e) {
            return null;
        }
    }
}

?>
