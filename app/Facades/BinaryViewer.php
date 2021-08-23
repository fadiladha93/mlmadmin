<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class HoldingTank
 * @package App\Facades
 */
class BinaryViewer extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.binary_viewer';
    }
}