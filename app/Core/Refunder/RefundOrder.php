<?php


namespace App\Core\Refunder;

use App\Order;
use App\OrderItem;
use App\Services\RefundService;
use App\User;

class RefundOrder extends AbstractRefunder
{
    /**
     * RefundOrder constructor.
     * @param Order $order
     * @throws \Exception
     */
    public function __construct(Order $order)
    {
        //set the user
        /** @var User $user */
        $user = $order->user()->first();
        if (!$this->user = $user) {
            throw new \Exception('No user found. Stopping refund process');
        }

        // set order
        $this->order = $order;

        // set refund amount
        $this->refundAmount = (float)$order->ordertotal;
        $this->refundCurrency = 'USD';

        $orderConversion = $order->conversion()->first();

        if ($orderConversion) {
            $this->refundAmount = $orderConversion->converted_amount / 100;
            $this->refundCurrency = $orderConversion->converted_currency;
        }
    }

    /**
     * @return RefundOrder
     * @throws \Exception
     */
    public function refund()
    {
        $this->refundService = (new RefundService($this->order, $this->user, $this->refundAmount, $this->refundCurrency))->refund();
        return $this;
    }

    /**
     * @return RefundOrder
     * @throws \Exception
     */
    public function finish()
    {
        if ($this->order->orderItems->isEmpty()) {
            throw new \Exception('Order items not found for order #' . $this->order->id);
        }

        foreach ($this->order->orderItems as $orderItem) {
            $this->finishOrderItem($orderItem);
        }

        // update order status
        $this->updateOrderStatus(Order::ORDER_STATUS_REFUNDED);
        $this->setResponse(0, 'Order #' . $this->order->id . ' was successfully refunded !');

        // log to action log
        $this->logAction();

        return $this;
    }

    /**
     * @param int $refundQV
     * @return RefundOrder
     */
    public function createRefundOrder($refundQV = 0)
    {
        $orderQV = $refundQV ? $this->order->orderqv : 0;
        $this->refundId = Order::addNew(
            $this->order->userid,
            -$this->order->ordersubtotal,
            -$this->order->ordertotal,
            -$this->order->orderbv,
            -$orderQV,
            -$this->order->ordercv,
            $this->refundService->getNmiTransactionId(),
            $this->order->payment_methods_id,
            null,
            null,
            null,
            null,
            Order::ORDER_STATUS_REFUND,
            $this->order->id
        );

        // create order items
        /** @var OrderItem $orderItem */
        foreach ($this->order->orderItems as $orderItem) {
            $orderItemQV = $refundQV ? $orderItem->qv : 0;
            OrderItem::addNew(
                $this->refundId,
                $orderItem->productid,
                $orderItem->quantity,
                -$orderItem->itemprice,
                -$orderItem->bv,
                -$orderItemQV,
                -$orderItem->cv
            );
        }

        return $this;
    }
}
