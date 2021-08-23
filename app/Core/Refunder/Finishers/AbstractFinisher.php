<?php


namespace App\Core\Refunder\Finishers;

use App\Order;
use App\Product;

abstract class AbstractFinisher
{
    /** @var Order  */
    protected $order;

    /** @var Order  */
    protected $product;

    /**
     * RefundEnrollment constructor.
     * @param Order $order
     * @throws \Exception
     */
    public function __construct(Product $product, Order $order)
    {
        $this->product = $product;
        $this->order   = $order;
    }

    public abstract function finish();
}
