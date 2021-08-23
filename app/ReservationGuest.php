<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationGuest extends Model
{
    public $incrementing = false;
    protected $table = "reservations_quests";
    public $timestamps = false;

    protected $fillable = [
        'reservation_id',
        'adults',
        'childrens'
    ];
}
