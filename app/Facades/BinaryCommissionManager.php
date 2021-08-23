<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class BinaryCommissionManager
 * @package App\Facades
 */
class BinaryCommissionManager extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.binary_commission';
    }
}
