<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = "reservations";
    public $timestamps = false;
    protected $fillable = [
        'arrival_date',
        'book_date',
        'club_commission',
        'club_margin',
        'confirmation_number',
        'contract_number',
        'departure_date',
        'email_address',
        'guest_first_name',
        'guest_last_name',
        'location',
        'number_of_rooms',
        'other_id',
        'reservation_type',
        'resort',
        'retail_saving',
        'room_type',
        'sor_member_id',
        'save_on_res_id',
        'status',
        'total_charge',
        'user_type',
        'vacation_club',
    ];
}
