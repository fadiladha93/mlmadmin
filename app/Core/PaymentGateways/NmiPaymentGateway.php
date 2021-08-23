<?php


namespace App\Core\PaymentGateways;


use App\Core\PaymentGateways;
use App\Exceptions\Payment\TokenNotFoundException;
use App\Exceptions\Payment\TransactionNotFoundException;
use App\PaymentMethod;

class NmiPaymentGateway
{
    const TRANSACTION_TYPE_AUTHORIZATION = 1;
    const TRANSACTION_TYPE_CAPTURE = 2;
    const TRANSACTION_TYPE_SALE = 3;
    const TRANSACTION_TYPE_REFUND = 4;
    const TRANSACTION_TYPE_VOID = 5;

    /** @var PaymentMethod */
    private $paymentMethod;

    /** @var string */
    private $processType;

    /** @var string */
    private $authorization;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**@var float **/
    private $amount;

    /**@var string */
    private $currency;

    /** @var string */
    private $cardNumber;

    /** @var array */
    private $postData;

    /** @var Object */
    private $response;

    public function __construct($paymentMethod, $processType, $authorization, $amount, $currency, $username, $password)
    {
        $this->paymentMethod = $paymentMethod;
        $this->processType   = $processType;
        $this->authorization = $authorization;
        $this->amount        = $amount;
        $this->currency      = $currency;
        $this->username      = $username;
        $this->password      = $password;
    }

    /**
     * @return NmiPaymentGateway
     * @throws TokenNotFoundException
     * @throws TransactionNotFoundException
     * @throws \Exception
     */
    public function execute()
    {
        $this->fetchAuthToken();
        $this->buildRequest();
        $this->process();
        $this->validate();

        return $this;
    }

    /**
     * @return NmiPaymentGateway
     * @throws TokenNotFoundException
     * @throws \Exception
     */
    public function fetchAuthToken()
    {
        $tokenEx  = new \tokenexAPI();
        $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $this->paymentMethod->token);
        $tokenRes = $tokenRes['response'];

        if (!$tokenRes->Success) {
            if (strpos($tokenRes->Error, 'Token does not exist for client') !== false) {
                throw new TokenNotFoundException();
            }

            throw new \Exception($tokenRes->Error);
        }

        $this->cardNumber = $tokenRes->Value;
        return  $this;
    }

    /**
     * @throws \Exception
     */
    private function buildRequest()
    {
        switch ($this->processType) {
            case PaymentGateways::PAYMENT_PROCESS_TYPE_REFUND:
                $this->buildRefundRequest();
                break;
            default:
                throw new \Exception('Invalid payment processing type provided');
        }
    }

    private function buildRefundRequest() {
        $formattedAmount = (int)round($this->amount * 100);
        $this->postData = [
            "TransactionType" => self::TRANSACTION_TYPE_REFUND,
            'TransactionRequest' => [
                'gateway' => [
                    'name' => 'NmiGateway',
                    'login' => $this->username,
                    'password' => $this->password,
                ],
                'credit_card' => [
                    'number' => $this->cardNumber,
                    'month' => $this->paymentMethod->expMonth,
                    'year' => $this->paymentMethod->expYear,
                    'verification_value' => $this->paymentMethod->cvv,
                    'first_name' => $this->paymentMethod->firstname,
                    'last_name' => $this->paymentMethod->lastname,
                ],
                'transaction' => [
                    'currency' => $this->currency,
                    'amount' => $formattedAmount,
                    'authorization' => $this->authorization,
                    'card_number' => substr($this->cardNumber, -4),
                    'first_name' => $this->paymentMethod->firstname,
                    'last_name' => $this->paymentMethod->lastname
                ],
            ],
        ];

        return $this;
    }

    /**
     * @return NmiPaymentGateway
     * @throws \Exception
     */
    protected function process()
    {
        try {
            $response = (new \tokenexAPI())->processTransactionAndTokenize(
                'ProcessTransactionAndTokenize',
                $this->postData
            );

            $this->response = json_decode($response);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

        return $this;
    }

    /**
     * @return bool
     * @throws TransactionNotFoundException
     * @throws \Exception
     */
    protected function validate()
    {
        if ($this->success()) {
            return true;
        }

        if (strpos($this->response->Message, 'Transaction not found') !== false) {
            throw new TransactionNotFoundException();
        }

        throw new \Exception($this->response->Message);
    }

    public function success()
    {
        return filter_var($this->response->TransactionResult, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return Object
     */
    public function getResponse()
    {
        return $this->response;
    }
}
