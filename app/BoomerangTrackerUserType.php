<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class BoomerangTrackerUserType extends Model
{

    protected $table = "boomerang_tracker_user_type_lookup";
    public $timestamps = false;

    public const USER_TYPE_IGO = 1;
    public const USER_TYPE_VIBE_RIDER = 2;
    public const USER_TYPE_VIBE_DRIVER = 3;
}
