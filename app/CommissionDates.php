<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionDates extends Model
{
    protected $table = 'commission_dates';

    protected $fillable = [
        'type',
        'start_date',
        'end_date'
    ];

    public $timestamps = false;

    public static function getPostDate()
    {
        return [
            'from' => self::where('type', 'post')->first() ? self::where('type', 'post')->first()->start_date : '',
            'to' => self::where('type', 'post')->first() ? self::where('type', 'post')->first()->end_date : '',
        ];
    }
}
