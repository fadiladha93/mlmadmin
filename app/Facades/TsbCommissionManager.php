<?php

namespace App\Facades;

use App\Jobs\TsbCommission as TsbCommissionJob;
use App\Services\TsbCommissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * Class BinaryCommissionManager
 * @see TsbCommissionService
 * @see TsbCommissionJob
 * @package App\Facades
 *
 * @method calculateCommission($filename, Carbon $endDate)
 */
class TsbCommissionManager extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.tsb_commission';
    }
}
