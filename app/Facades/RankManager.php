<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class RankManager
 * @package App\Facades
 */
class RankManager extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'ibu.service.achieved_ranks';
    }
}
