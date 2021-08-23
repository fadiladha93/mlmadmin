<?php

namespace App\Core;

use App\Core\Refunder\RefundOrder;
use App\Core\Refunder\RefundOrderItem;
use App\Order;
use App\OrderItem;

class Refunder
{
    /**
     * @param Order $order
     * @return RefundOrder
     * @throws \Exception
     */
    public function refundOrder(Order $order)
    {
        return (new RefundOrder($order))->refund();
    }

    /**
     * @param OrderItem $orderItem
     * @return RefundOrderItem
     * @throws \Exception
     */
    public function refundOrderItem(OrderItem $orderItem)
    {
        return (new RefundOrderItem($orderItem))->refund();
    }
}
