<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class UserTransferManager
 * @package App\Facades
 * @method transferUser($userId)
 */
class UserTransferManager extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.user_transfer';
    }
}
