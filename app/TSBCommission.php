<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TSBCommission extends Model
{
    protected $table = 'tsb_commission';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sourceUser()
    {
        return $this->belongsTo('App\User', 'dist_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo('App\OrderItem');
    }

    public static function isCommissionAlreadyCalculated($userId, $paidDate)
    {
        return static::query()
                    ->where('user_id', '=', $userId)
                    ->where('paid_date', '=', $paidDate)
                    ->exists();
    }
    /**
     * @param $user
     * @param $level
     * @param $date
     * @return mixed
     */
    public static function getCommissionRow($user, $level, $date)
    {
        return TSBCommission::where('user_id', $user->id)
            ->where('level', $level)
            ->whereDate('displaying_date', $date)
            ->first();
    }


    /**
     * @param $user
     * @param $level
     * @param $date
     * @return mixed
     */
    public static function get($user, $level, $date)
    {
        return TSBCommission::where('user_id', $user->id)
            ->where('level', $level)
            ->whereDate('displaying_date', $date)
            ->first();
    }

    public static function getByDateRange($start_date, $end_date)
    {
        return TSBCommission::whereDate('start_date', '>=', $start_date)
            ->whereDate('end_date', '<=', $end_date)
            ->get();
    }

    public static function getSumByDateRange($start_date, $end_date)
    {
        return TSBCommission::
            whereDate('paid_date', '>=', $start_date)
            ->whereDate('paid_date', '<=', $end_date)
            ->whereIn('status',['pending','approved'])
            ->sum('amount');
    }
}
