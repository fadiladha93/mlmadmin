<?php


namespace App\Core;


use App\Core\PaymentGateways\NmiPaymentGateway;
use App\Exceptions\Payment\TokenNotFoundException;
use App\Exceptions\Payment\TransactionNotFoundException;
use App\Order;
use App\PaymentMethod;

class PaymentGateways
{
    const PAYMENT_PROCESS_TYPE_REFUND = 'refund';

    const ACCOUNT_TYPE_T1     = 't1';
    const ACCOUNT_TYPE_NMI    = 'nmi';
    const ACCOUNT_TYPE_PAYARC = 'payArc';
    const ACCOUNT_TYPE_METROPOLITAN = 'metropolitan';

    /** @var Order */
    private $order;

    /** float */
    private $amount;

    /** @var PaymentMethod */
    private $paymentMethod;

    /** @var string */
    private $authorization;

    /** @var string */
    private $currency;

    /**
     * PaymentGateways constructor.
     * @param $order
     * @param $currency
     * @param $amount
     * @throws \Exception
     */
    public function __construct($order, $currency, $amount)
    {
        $this->order  = $order;
        $this->currency = $currency;
        $this->amount = $amount;

        //get the authorization
        $transactionInfo = explode('#', $this->order->trasnactionid);
        $authorization   = !empty($transactionInfo[0]) ? $transactionInfo[0]: null;

        if (empty($authorization)) {
            throw new \Exception('The authorization could not be found. Unable to process transaction');
        }

        $this->authorization = $authorization;

        // get the payment method
        if (!$this->paymentMethod = PaymentMethod::where('id', $this->order->payment_methods_id)->first()) {
            throw new \Exception('No payment method found. Unable to process transaction');
        }
    }

    /**
     * @throws \Exception
     */
    public function runNmiPaymentGateway()
    {
        $isProcessedSuccessfully = false;
        foreach ($this->getAccounts() as $account) {
            try {
                $nmiPaymentGateway = (new NmiPaymentGateway(
                    $this->paymentMethod,
                    self::PAYMENT_PROCESS_TYPE_REFUND,
                    $this->authorization,
                    $this->amount,
                    $this->currency,
                    $account['username'],
                    $account['password']
                ))->execute();

                if ($nmiPaymentGateway->success()) {
                    $isProcessedSuccessfully = true;
                    break;
                }
            } catch (TokenNotFoundException $ex) {
                continue;
            } catch (TransactionNotFoundException $ex) {
                continue;
            } catch (\Exception $ex) {
                throw new \Exception($ex->getMessage());
            }
        }

        if (!$isProcessedSuccessfully) {
            throw new \Exception('Unable to process transaction on all accounts');
        }
    }

    private function getAccounts()
    {
        return [
            self::ACCOUNT_TYPE_T1 => [
                'username' => \Config::get('api_endpoints.t1Username'),
                'password' => \Config::get('api_endpoints.t1Password')
            ],
            self::ACCOUNT_TYPE_NMI => [
                'username' => \Config::get('api_endpoints.NMIUsername'),
                'password' => \Config::get('api_endpoints.NMIPassword')
            ],
            self::ACCOUNT_TYPE_PAYARC => [
                'username' => \Config::get('api_endpoints.payArcUsername'),
                'password' => \Config::get('api_endpoints.payArcPassword')
            ],
            self::ACCOUNT_TYPE_METROPOLITAN => [
                'username' => \Config::get('api_endpoints.metroUsername'),
                'password' => \Config::get('api_endpoints.metroPassword')
            ]
        ];
    }
}
