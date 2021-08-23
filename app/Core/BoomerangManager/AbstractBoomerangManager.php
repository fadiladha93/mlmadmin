<?php


namespace App\Core\BoomerangManager;


use App\BoomerangInv;
use App\BoomerangTracker;

abstract class AbstractBoomerangManager
{
    protected $isActiveManager = true;

    /** @var BoomerangInv */
    private $boomerangInventory;

    /** @var BoomerangTracker */
    private $boomerangTracker;

    /**
     * @param string $userId
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $mobile
     * @param string $expirationDate
     * @param string $userTypeText
     * @return AbstractBoomerangManager
     * @throws \Exception
     */
    public function createBoomerang($userId, $firstname, $lastname, $email, $mobile, $expirationDate, $userTypeText)
    {
        $boomerangInv = \App\BoomerangInv::getInventory($userId);
        if (empty($boomerangInv) || $boomerangInv->available_tot == 0) {
            throw new \Exception('There are no available Boomerangs');
        }

        $userType = BoomerangTracker::$matchTextToTypes[$userTypeText];

        $this->boomerangTracker = BoomerangTracker::addNewInd(
            $userId,
            $firstname,
            $lastname,
            $email,
            $mobile,
            $expirationDate,
            $userType
        );

        // update the boomerang inventory
        $this->boomerangInventory = BoomerangInv::where('userid', $userId)->first();
        if ($this->isActiveManager) {
            //update the record
            $this->boomerangInventory->update([
                'pending_tot' => $this->boomerangInventory->pending_tot + 1,
                'available_tot' => $this->boomerangInventory->available_tot - 1
            ]);
        }

        return $this;
    }

    /**
     * @param string $userId
     * @return AbstractBoomerangManager
     */
    public function utilizeBoomerang($userId)
    {
        if (!$this->isActiveManager) {
            return $this;
        }

        $boomerangInventory = BoomerangInv::where('userid', $userId)->first();
        $boomerangInventory->pending_tot = (int)$boomerangInventory->pending_tot - 1;
        $boomerangInventory->save();

        return $this;
    }

    /**
     * @param BoomerangTracker $boomerangTracker
     * @return AbstractBoomerangManager
     */
    public function revertExpiredBoomerang($boomerangTracker)
    {
        if (!$this->isActiveManager) {
            return $this;
        }

        BoomerangInv::addBackToInventory($boomerangTracker->userid, 1);
        BoomerangTracker::deleteRec($boomerangTracker->id);

        return $this;
    }

    /**
     * @return BoomerangInv
     */
    public function getBoomerangInventory()
    {
        return $this->boomerangInventory;
    }

    /**
     * @return BoomerangTracker
     */
    public function getBoomerangTracker()
    {
        return $this->boomerangTracker;
    }
}
