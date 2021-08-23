<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class UnilevelCommissionManager
 * @package App\Facades
 */
class UnilevelCommissionManager extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.unilevel_commission';
    }
}
