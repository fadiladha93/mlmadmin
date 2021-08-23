<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NMIGateway extends Model {

    public $timestamps = false;

    const TRANSACTION_TYPE_AUTHORIZATION = 1;
    const TRANSACTION_TYPE_CAPTURE = 2;
    const TRANSACTION_TYPE_SALE = 3;
    const TRANSACTION_TYPE_REFUND = 4;
    const TRANSACTION_TYPE_VOID = 5;

    /**
     * @param $tokenEx
     * @param $firstname
     * @param $lastname
     * @param $expMonth
     * @param $expYear
     * @param $cvv
     * @param $amount
     * @param $authorization
     * @param $lastFourDigitCardNo
     * @param null $pay_method_type
     * @return array
     */
    public static function refundPayment(
        $tokenEx,
        $firstname,
        $lastname,
        $expMonth,
        $expYear,
        $cvv,
        $amount,
        $authorization,
        $lastFourDigitCardNo,
        $pay_method_type = null
    ) {
        $formattedAmount = (int)round($amount * 100);
        $postData = array(
            "TransactionType" => self::TRANSACTION_TYPE_REFUND,
            'TransactionRequest' =>
                array(
                    'gateway' =>
                        array(
                            'name' => 'NmiGateway',
                            'login' => \Config::get('api_endpoints.NMIUsername'),
                            'password' => \Config::get('api_endpoints.NMIPassword'),
                        ),
                    'credit_card' =>
                        array(
                            'number' => $tokenEx,
                            'month' => $expMonth,
                            'year' => $expYear,
                            'verification_value' => $cvv,
                            'first_name' => $firstname,
                            'last_name' => $lastname,
                        ),
                    'transaction' =>
                        array(
                            'amount' => $formattedAmount,
                            'authorization' => $authorization,
                            'card_number' => $lastFourDigitCardNo,
                            'first_name' => $firstname,
                            'last_name' => $lastname
                        ),
                ),
        );
        if ($pay_method_type == \App\PaymentMethodType::TYPE_T1_PAYMENTS) {
            $postData['TransactionRequest']['gateway']['login'] = \Config::get('api_endpoints.t1Username');
            $postData['TransactionRequest']['gateway']['password'] = \Config::get('api_endpoints.t1Password');
        }
        if ($pay_method_type == \App\PaymentMethodType::TYPE_PAYARC) {
            $postData['TransactionRequest']['gateway']['login'] = \Config::get('api_endpoints.payArcUsername');
            $postData['TransactionRequest']['gateway']['password'] = \Config::get( 'api_endpoints.payArcPassword');
        }

        return self::process($postData);
    }

    /**
     * @param $tokenEx
     * @param $firstName
     * @param $lastName
     * @param $expMonth
     * @param $expYear
     * @param $cvv
     * @param $amount
     * @param $address1
     * @param $city
     * @param $state
     * @param $postalCode
     * @param $countryCode
     * @param null $paymentMethodType
     * @param int $transactionType
     * @param string $currency
     * @param int $currencyAmount
     * @return array
     */
    public static function processPayment(
        $tokenEx,
        $firstName,
        $lastName,
        $expMonth,
        $expYear,
        $cvv,
        $amount,
        $address1,
        $city,
        $state,
        $postalCode,
        $countryCode,
        $paymentMethodType = null,
        $transactionType = self::TRANSACTION_TYPE_SALE,
        $currency = null,
        $currencyAmount = null
    ) {
        $loginKey    = 'api_endpoints.NMIUsername';
        $passwordKey = 'api_endpoints.NMIPassword';

        $t1PaymentMethodTypes = [
            \App\PaymentMethodType::TYPE_T1_PAYMENTS,
            \App\PaymentMethodType::TYPE_T1_PAYMENTS_SECONDARY_CC,
        ];

        $payArcT1OverrideEnabled = env('PAYARC_T1_OVERRIDE', false);

        if ($payArcT1OverrideEnabled) {
            $t1PaymentMethodTypes[] = \App\PaymentMethodType::TYPE_PAYARC;
        }

        if (in_array($paymentMethodType, $t1PaymentMethodTypes)) {
            $loginKey = 'api_endpoints.t1Username';
            $passwordKey = 'api_endpoints.t1Password';
        } elseif ($paymentMethodType == \App\PaymentMethodType::TYPE_PAYARC) {
            $loginKey = 'api_endpoints.payArcUsername';
            $passwordKey = 'api_endpoints.payArcPassword';
        }

        $postData = array(
            "TransactionType" => $transactionType,
            'TransactionRequest' => array(
                'gateway' => array(
                    'name' => 'NmiGateway',
                    'login' => \Config::get($loginKey),
                    'password' => \Config::get($passwordKey),
                ),

                'credit_card' => array(
                    'number' => $tokenEx,
                    'month' => $expMonth,
                    'year' => $expYear,
                    'verification_value' => $cvv,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ),

                'transaction' => array(
                    'amount' => $amount * 100,
                    'billing_address' => array(
                        'address1' => $address1,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $postalCode,
                        'country' => $countryCode,
                    ),
                ),
            ),
        );

        if ($currency) {
            $postData['transaction']['currency'] = $currency;
            $postData['transaction']['amount'] = $currencyAmount;
        }

        return self::process($postData);
    }

    /**
     * @param $postData
     * @return array
     */
    private static function process($postData)
    {
        $response = null;
        $authorization = null;

        try {
            $response = (new \tokenexAPI())
                ->processTransactionAndTokenize('ProcessTransactionAndTokenize', $postData);

            $response = json_decode($response);
            if ($response->TransactionResult) {
                $error = 0;
                $msg = null;
                $authorization = $response->Authorization;
            } else {
                $error = 1;
                $msg = "";

                if (isset($response->Error))
                    $msg .= $response->Error . "<br/>";

                if (isset($response->Message))
                    $msg .= $response->Message;

                $authorization = null;
            }
        } catch (\Exception $ex) {
            $msg = $ex->getMessage();
            $error = 1;
        }

        $result = array(
            'error' => $error,
            'msg' => $msg,
            'authorization' => $authorization,
            'request' => $postData,
            'response' => $response
        );

        return $result;
    }
}
