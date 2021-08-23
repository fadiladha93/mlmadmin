<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionTempPost extends Model
{
    protected $table = 'commission_temp_post';

    public $timestamps = false;

    public static function isEmpty()
    {
        $count = self::count();

        return $count ? false : true;
    }
}
