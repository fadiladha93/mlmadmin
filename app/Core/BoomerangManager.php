<?php


namespace App\Core;


use App\BoomerangTracker;
use App\Core\BoomerangManager\AbstractBoomerangManager;
use App\Core\BoomerangManager\IgoBoomerangManager;
use App\Core\BoomerangManager\VibeDriverBoomerangManager;
use App\Core\BoomerangManager\VibeRiderBoomerangManager;
use App\Core\BoomerangManager\BillgeniusBoomerangManager;

class BoomerangManager
{
    /** @var AbstractBoomerangManager  */
    public $manager;

    /**
     * BoomerangManager constructor.
     * @param $boomerangUserType
     * @throws \Exception
     */
    public function __construct($boomerangUserType)
    {
        switch ($boomerangUserType) {
            case BoomerangTracker::BOOMERANG_USER_TYPE_IGO:
                $this->manager = new IgoBoomerangManager();
                break;
            case BoomerangTracker::BOOMERANG_USER_TYPE_VIBE_RIDER:
                $this->manager = new VibeRiderBoomerangManager();
                break;
            case BoomerangTracker::BOOMERANG_USER_TYPE_VIBE_DRIVER:
                $this->manager = new VibeDriverBoomerangManager();
                break;
            case BoomerangTracker::BOOMERANG_USER_TYPE_BILLGENIUS:
                $this->manager = new BillgeniusBoomerangManager();
                break;
            default:
                throw new \Exception('Sorry unable to process unknown user type');
        }
    }
}
