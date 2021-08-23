<?php


namespace App\Services;

use App\Core\PaymentGateways;
use App\DiscountCoupon;
use App\EwalletTransaction;
use App\Helper;
use App\helpers\ApiHelper;
use App\Library\TMTService\TMTPayment;
use App\Models\UserPaymentMethod;
use App\PaymentMethod;
use App\PaymentMethodType;

use App\Order;
use App\Product;
use App\User;
use Exception;
use gwapi;

class RefundService
{
    /** @var Order */
    private $order;

    /** @var User */
    private $user;

    /** @var string */
    private $refundType;

    /** @var string */
    private $processType;

    /** @var float */
    private $refundAmount;

    /**
     * @var string
     */
    private $refundCurrency;

    /** @var string */
    private $nmiTransactionId;

    /** @var string */
    private $nmiAuthorization;

    /** @var string */
    private $ewalletTransactionType;

    /**@var DiscountCoupon */
    private $discountCoupon;

    const REFUND_PROCESS_TYPE_NMI = 'nmi';
    const REFUND_PROCESS_TYPE_BILLING = 'billing';
    const REFUND_PROCESS_TYPE_EWALLET = 'ewallet';
    const REFUND_PROCESS_TYPE_VOUCHER = 'voucher';

    const REFUND_TYPE_ENROLLMENT   = 'enrollment';
    const REFUND_TYPE_UPDGRADE     = 'upgrade';
    const REFUND_TYPE_VOUCHER      = 'voucher';
    const REFUND_TYPE_SUBSCRIPTION = 'subscription';
    const REFUND_TYPE_BUUMERANGS   = 'buumerangs';
    const REFUND_TYPE_OTHER        = 'other';

    protected static $ewalletTransactionTypes = [
        self::REFUND_TYPE_ENROLLMENT   => EwalletTransaction::TYPE_CODE_REFUND,
        self::REFUND_TYPE_UPDGRADE     => EwalletTransaction::TYPE_UPGRADE_REFUND,
        self::REFUND_TYPE_VOUCHER      => EwalletTransaction::TYPE_PURCHASED_VOUCHER_REFUND,
        self::REFUND_TYPE_SUBSCRIPTION => EwalletTransaction::TYPE_SUBSCRIPTION_REFUND,
        self::REFUND_TYPE_BUUMERANGS   => EwalletTransaction::TYPE_BUUMERANGS_REFUND,
        self::REFUND_TYPE_OTHER        => EwalletTransaction::TYPE_OTHER_REFUND,
    ];

    /**
     * RefundService constructor.
     * @param Order $order
     * @param User $user
     * @param float $refundAmount
     * @param string $refundCurrency
     * @throws \Exception
     */
    public function __construct($order, $user, $refundAmount, $refundCurrency)
    {
        $this->order = $order;
        $this->user  = $user;
        $this->refundAmount = $refundAmount;
        $this->refundCurrency = $refundCurrency;

        $this->init();
    }

    /**
     * @throws \Exception
     */
    private function init()
    {
        $this->processType = self::REFUND_PROCESS_TYPE_BILLING;


        if (empty($this->order->trasnactionid)) {
            $hasEwalletTrans = EwalletTransaction::query()
                ->select('*')
                ->where('purchase_id', $this->order->id)
                ->count();

            if (!$hasEwalletTrans) {
                throw new \Exception(
                    'A previous transaction of type e-wallet or otherwise
                    cannot be found for order #' . $this->order->id
                );
            }

            $this->ewalletTransactionType = EwalletTransaction::TYPE_REFUND;

            // check if it is a voucher purchase
            if ($this->order->isVoucherPurchaseOrder()) {
                $this->processType = self::REFUND_PROCESS_TYPE_VOUCHER;
                $this->discountCoupon = $this->order->getAssociatedDiscountCoupon();
                return;
            }

            $this->processType = self::REFUND_PROCESS_TYPE_EWALLET;
        }
        if ($this->order->isPurchasedByVoucher()) {
            $transaction = explode('#', $this->order->trasnactionid);
            if (empty($transaction[1])) {
                throw new \Exception('Refund failed. Unable to retrieve coupon code for transaction.');
            }

            $this->discountCoupon = DiscountCoupon::query()
                ->where('used_by', $this->user->id)
                ->where('code', $transaction[1])
                ->first();

            $this->processType = self::REFUND_PROCESS_TYPE_VOUCHER;
            $this->ewalletTransactionType = EwalletTransaction::TYPE_REFUND;
        }
    }

    /**
     * @return RefundService
     * @throws \Exception
     */
    public function refund()
    {
        switch ($this->processType) {
            case self::REFUND_PROCESS_TYPE_EWALLET:
                $this->processEwalletTransactions();
                return $this;
            case self::REFUND_PROCESS_TYPE_NMI:
                $this->processNmiTransactions();
                return $this;
            case self::REFUND_PROCESS_TYPE_BILLING;
                $this->processBillingTransactions();
                return $this;
            case self::REFUND_PROCESS_TYPE_VOUCHER:
                $this->processVoucherTransactions();
                return $this;
            default:
                throw new \Exception('Unable to process a refund. No suitable processing type found');
        }
    }


    private function processBillingTransactions()
    {

        $transaction   = explode('#', $this->order->trasnactionid);
        $transactionId = !empty($transaction[0]) ? $transaction[0] : null;

        if (empty($transactionId)) {
            throw new \Exception('The transaction id could not be found. Unable to process refund');
        }

        try {
            $request = ApiHelper::billingRequest(
                'POST',
                '/v1/api/refund',
                [
                    'transaction_id' => $this->order->trasnactionid,
                    'reason' => 'Refund Order'
                ]
            );

            $billingResponse = json_decode($request->getBody());

            if (!$billingResponse->success) {

                $merchantResponse = $this->processNmiTransactions();

                if ($merchantResponse['response'] == 3) {
                    throw new \Exception($merchantResponse['responsetext']);
                }
            }
        } catch (Exception $e) {
            throw new \Exception('Refund Proccess Error: ' . $e->getMessage());
        }
    }

    /**
     * @return RefundService
     * @throws \Exception
     */
    private function processNmiTransactions()
    {
        if (!$paymentMethod = PaymentMethod::find($this->order->payment_methods_id)) {
            throw new \Exception('No User payment method found. Stopping refund process');
        }


        switch ($paymentMethod->pay_method_type) {
                //intentional fallthrough
            case PaymentMethodType::TYPE_CREDIT_CARD:
            case PaymentMethodType::TYPE_T1_PAYMENTS:
            case PaymentMethodType::TYPE_T1_PAYMENTS_SECONDARY_CC:
            case PaymentMethodType::TYPE_SECONDARY_CC:
            case PaymentMethodType::TYPE_PAYARC:
            case PaymentMethodType::TYPE_METROPOLITAN:
            case PaymentMethodType::TYPE_PAYNETWORX:
                if ($this->order->ordertotal <= 0) {
                    throw new \Exception('Order total should be greater than 0 amount');
                }

                // $this->nmiRefund($paymentMethod);
                return $this->refunfByNetWorsMerchants();

                break;
            case PaymentMethodType::TYPE_TMT:
                if ($this->order->ordertotal <= 0) {
                    throw new \Exception('Order total should be greater than 0 amount');
                }

                $this->tmtRefundPayment($this->order);
                break;
            case PaymentMethodType::TYPE_SKRILL:
                throw new \Exception('Skrill refund not integrated');
                break;
            default:
                throw new \Exception('Payment method not found');
        }
    }

    private function refunfByNetWorsMerchants()
    {
        $transaction   = explode('#', $this->order->trasnactionid);
        $transactionId = !empty($transaction[0]) ? $transaction[0] : null;

        if (empty($transactionId)) {
            throw new \Exception('The transaction id could not be found. Unable to process refund');
        }

        foreach ($this->merchants() as $merchant) {
            $gateway = new gwapi();
            $gateway->setLogin($merchant);

            $merchantResponse =  $gateway->doRefund($transactionId, $this->refundCurrency, $this->refundAmount);

            if ($merchantResponse['response'] == 1) {
                break;
                return $merchantResponse;
            }
        }
    }

    private function merchants()
    {
        return [
            PaymentMethodType::TYPE_PAYNETWORX,
            PaymentMethodType::TYPE_METROPOLITAN,
        ];
    }

    private function processEwalletTransactions()
    {
        EwalletTransaction::addPurchase(
            $this->user->id,
            $this->ewalletTransactionType,
            $this->refundAmount,
            $this->order->id,
            $note = 'E-Wallet Refund'
        );
    }

    /**
     * @throws \Exception
     */
    private function processVoucherTransactions()
    {
        // refund to ewallet of generator
        $voucherProduct = Product::query()
            ->where('id', $this->discountCoupon->product_id)
            ->first();

        if (!$voucherProduct) {
            throw new \Exception('Unable to find matching product for voucher refund for order #' . $this->order->id);
        }

        if (!$this->discountCoupon->generated_for) {
            return; // generated by admin, no need to do anything with ewallet
        }

        // update voucher generator ewallet
        EwalletTransaction::addPurchase(
            $this->discountCoupon->generated_for,
            $this->ewalletTransactionType,
            $voucherProduct->price,
            $this->order->id,
            $note = 'Refund for Voucher ' . $this->discountCoupon->code
        );
    }

    /**
     * @param $paymentMethod
     * @return array
     * @throws \Exception
     */
    protected function nmiRefund($paymentMethod)
    {
        //do refund       
        $transaction   = explode('#', $this->order->trasnactionid);
        $transactionId = !empty($transaction[0]) ? $transaction[0] : null;


        if (empty($transactionId)) {

            throw new \Exception('The transaction id could not be found. Unable to process refund');
        }

        (new PaymentGateways($this->order, $this->refundCurrency, $this->refundAmount))->runNmiPaymentGateway();
    }

    /**
     * @param $order
     * @return RefundService
     */
    protected function tmtRefundPayment($order)
    {
        $tmtPayment = new TMTPayment();
        $transactionId = explode('#', $order->trasnactionid);
        $transaction = $tmtPayment->readTransaction($transactionId[0]);

        if ($transaction['error'] != 0) {
            $response = $tmtPayment->refundTransaction();
            return $this;
        }

        return $this;
    }

    /**
     * @param $order
     * @return RefundService
     */
    protected function payArcRefundPayment($order)
    {
        $tmtPayment = new TMTPayment();
        $transactionId = explode('#', $order->trasnactionid);
        $transaction = $tmtPayment->readTransaction($transactionId[0]);

        if ($transaction['error'] != 0) {
            $response = $tmtPayment->refundTransaction();
            return $this;
        }
        $response['error'] = 1;

        return $response;
    }

    /**
     * @return string
     */
    public function getNmiTransactionId()
    {
        return $this->nmiTransactionId;
    }

    /**
     * @return string
     */
    public function getNmiAuthorization()
    {
        return $this->nmiAuthorization;
    }
}
