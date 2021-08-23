<?php

namespace App\Core\Refunder\Finishers;

use App\Order;
use App\BoomerangInv;

class EnrollmentFinisher extends AbstractFinisher
{
    /** @var Order  */
    protected $order;

    /** @var Order  */
    protected $product;

    /**
     * @return EnrollmentFinisher
     */
    public function finish()
    {
        $this->subtractBoomerang();
        return $this;
    }

    /**
     * @return EnrollmentFinisher
     */
    private function subtractBoomerang()
    {
        BoomerangInv::query()
            ->where('userid', $this->order->userid)
            ->decrement('available_tot', $this->product->num_boomerangs);

        return $this;
    }
}
