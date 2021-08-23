<?php


namespace App\Core\Refunder\Finishers;

use App\Product;
use App\BoomerangInv;
use \App\DiscountCoupon;

class UpgradeFinisher extends AbstractFinisher
{
    /**
     * @return UpgradeFinisher
     * @throws \Exception
     */
    public function finish()
    {
        $this->processDowngrade();
        return $this;
    }

    /**
     * @return UpgradeFinisher
     * @throws \Exception
     */
    private function processDowngrade()
    {
        if (!$downgradeProductId = $this->getDowngradePackage()) {
            throw new \Exception('Downgrade package not found');
        }

        // decrement boomerang count
        BoomerangInv::query()
            ->where('userid', $this->order->userid)
            ->decrement('available_tot', $this->product->num_boomerangs);

        // save on transfer
        $referringUserSORID = \App\SaveOn::getSORUserId($this->order->userid);
        if (!empty($referringUserSORID)) {
            $sorRes = \App\SaveOn::transfer($this->order->userid, $referringUserSORID, $downgradeProductId);
            $sorResponse = $sorRes['response'];
            if (!empty($sorResponse->status_code) && $sorResponse->status_code == 200) {
                \App\SaveOn::where('user_id', $this->order->userid)->update(['product_id' => $downgradeProductId]);
            }
        }

        // update the discount coupon
        DiscountCoupon::query()
            ->where('code', $this->order->coupon_code)
            ->where('used_by', $this->order->userid)
            ->update([
                'is_used' => 0,
                'used_by' => null
            ]);

        return $this;
    }

    /**
     * @return int|null
     */
    private function getDowngradePackage()
    {
        switch ($this->product->id) {
            case Product::ID_UPG_STAND_FIRST:
                return Product::ID_NCREASE_ISBO;
            case Product::ID_UPG_STAND_BUSINESS:
                return Product::ID_NCREASE_ISBO;
            case Product::ID_UPG_STAND_COACH:
                return Product::ID_NCREASE_ISBO;
            case Product::ID_UPG_COACH_BUSINESS:
                return Product::ID_BASIC_PACK;
            case Product::ID_UPG_COACH_FIRST:
                return Product::ID_BASIC_PACK;
            case Product::ID_UPG_BUSINESS_FIRST:
                return Product::ID_VISIONARY_PACK;
            default:
                return null;
        }
    }
}
