<?php


namespace App\Core\Refunder\Finishers;

use App\BoomerangInv;

class IbuumerangPacksFinisher extends AbstractFinisher
{
    public function finish()
    {
        $this->subtractUserBoomerangs();
        return $this;
    }

    private function subtractUserBoomerangs()
    {
        $inventory = BoomerangInv::where('userid', $this->order->userid)->first();
        $inventory->available_tot = $inventory->available_tot - $this->product->num_boomerangs;
        $inventory->save();
    }
}
