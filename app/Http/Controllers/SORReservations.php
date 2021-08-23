<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SORReservations extends Controller
{

    public function sorCommission(Request $request)
    {
        if ($request->isJson()) {
            if (empty($request->json()->all())) {
                return response()->json(['msg' => 'Invalid json']);
            }
        }
        $data = $request->getContent();
        file_put_contents('sor_commission.txt', $data, FILE_APPEND);
        $this->addReservations($data);
        return response()->json(['msg' => 'Reservation data successfully loaded']);
    }

    private function addReservations($data)
    {
        $commissions = json_decode($data, true);
        foreach ($commissions as $commission) {
            $hasReservation = \App\Reservation::where('sor_member_id', $commission['S0R member ID'])->where('save_on_res_id', $commission['SaveOn Res ID'])->count();
            if ($hasReservation == 0) {
                $reservation = \App\Reservation::create([
                    'arrival_date' => $commission['Arrival Date'],
                    'book_date' => $commission['Book Date'],
                    'club_commission' => $commission['Club Commission'],
                    'club_margin' => $commission['Club margin'],
                    'confirmation_number' => $commission['Confirmation Number'],
                    'contract_number' => $commission['Contract Number'],
                    'departure_date' => $commission['Departure Date'],
                    'email_address' => $commission['Email Address'],
                    'guest_first_name' => $commission['Guest First Name'],
                    'guest_last_name' => $commission['Guest Last Name'],
                    'location' => $commission['Location'],
                    'number_of_rooms' => $commission['Number of Rooms'],
                    'other_id' => $commission['Other ID'],
                    'reservation_type' => $commission['Reservation Type'],
                    'resort' => $commission['Resort'],
                    'retail_saving' => $commission['Retail Savings'],
                    'room_type' => $commission['Room Type'],
                    'sor_member_id' => $commission['S0R member ID'],
                    'save_on_res_id' => $commission['SaveOn Res ID'],
                    'status' => $commission['Status'],
                    'total_charge' => $commission['Total charge'],
                    'user_type' => $commission['User Type'],
                    'vacation_club' => $commission['Vacation Club'],
                ]);
                foreach ($commission['Number Of Guests'] as $nofGuests) {
                    $adults = 0;
                    $childrens = 0;
                    if (isset($nofGuests['Adult(s)'])) {
                        $adults = $nofGuests['Adult(s)'];
                    }
                    if (isset($nofGuests['Children'])) {
                        $childrens = $nofGuests['Children'];
                    }
                    \App\ReservationGuest::create(['reservation_id' => $reservation->id, 'adults' => $adults, 'childrens' => $childrens]);
                }
            }
        }
    }

}
