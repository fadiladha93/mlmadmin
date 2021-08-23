<?php


namespace App\Core\Refunder;

use App\Order;
use App\OrderItem;
use App\Services\RefundService;

class RefundOrderItem extends AbstractRefunder
{
    /** @var OrderItem  */
    private $orderItem;
    /**
     * RefundOrderItem constructor.
     * @param OrderItem $orderItem
     * @throws \Exception
     */
    public function __construct(OrderItem $orderItem)
    {
        //set order item
        $this->orderItem = $orderItem;

        //set the user
        if (!$this->user = $orderItem->getUser()) {
            throw new \Exception('No user found. Stopping refund process');
        }

        //set order
        /** @var Order $order */
        $order = Order::where('id', $orderItem->orderid)->first();
        if (!$this->order = $order) {
            throw new \Exception('Unable to find associated order. Stopping refund process');
        }

        // set refund amount
        $this->refundAmount = (float)((int)$orderItem->quantity * (float)$orderItem->itemprice);
        $this->refundCurrency = 'USD';

        if ($order->conversion()->exists()) {
            $this->refundCurrency = $order->conversion()->converted_currency;
            $exchangeRate = (float)$order->conversion()->exchange_rate;

            $this->refundAmount *= $exchangeRate;
        }

        return $this;
    }

    /**
     * @return RefundOrderItem
     * @throws \Exception
     */
    public function refund()
    {
        $this->refundService = (new RefundService($this->order, $this->user, $this->refundAmount, $this->refundCurrency))->refund();
        return $this;
    }

    /**
     * @return RefundOrderItem
     * @throws \Exception
     */
    public function finish()
    {
        $this->finishOrderItem($this->orderItem);
        $this->finishPartialOrderItemRefund();

        $message = 'Order item #'
            . $this->orderItem->id . ' was successfully refunded for order #'
            . $this->order->id;

        $this->setResponse(0, $message);

        // log to action log
        $this->logAction();

        return $this;
    }

    private function finishPartialOrderItemRefund()
    {
        // other wrap up tasks specific for order items
        $this->updateOrderStatus($this->getOrderStatus());
    }

    /**
     * @param $refundQV
     * @return RefundOrderItem
     */
    public function createRefundOrder($refundQV = 0)
    {
        $orderItemQV = $refundQV ? $this->orderItem->qv : 0;
        $this->refundId = Order::addNew(
            $this->user->id,
            -$this->orderItem->itemprice,
            -$this->orderItem->itemprice,
            -$this->orderItem->bv,
            -$orderItemQV,
            -$this->orderItem->cv,
            $this->refundService->getNmiTransactionId(),
            $this->order->payment_methods_id,
            null,
            null,
            null,
            null,
            Order::ORDER_STATUS_PARTIAL_REFUND,
            $this->order->id
        );

        OrderItem::addNew(
            $this->refundId,
            $this->orderItem->productid,
            $this->orderItem->quantity,
            -$this->orderItem->itemprice,
            -$this->orderItem->bv,
            -$this->orderItem->qv,
            -$this->orderItem->cv
        );

        return $this;
    }

    private function getOrderStatus()
    {
        $status = Order::ORDER_STATUS_PARTIALLY_REFUNDED;
        if ($this->order->isRefunded()) {
            $status = Order::ORDER_STATUS_REFUNDED;
        }

        return $status;
    }
}
