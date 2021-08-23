<?php


namespace App\Core\Refunder\Finishers;

class VoucherFinisher extends AbstractFinisher
{
    public function finish()
    {
        $this->deactivateVoucher();
        return $this;
    }

    private function deactivateVoucher()
    {
        $discountCoupon = $this->order->getAssociatedDiscountCoupon();
        $discountCoupon->is_used   = 1;
        $discountCoupon->is_active = 0;
        $discountCoupon->save();
    }
}
