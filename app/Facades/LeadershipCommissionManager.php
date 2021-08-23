<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class LeadershipCommissionManager
 * @package App\Facades
 */
class LeadershipCommissionManager extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.leadership_commission';
    }
}
