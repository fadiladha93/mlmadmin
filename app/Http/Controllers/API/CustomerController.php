<?php

namespace App\Http\Controllers\API;

use App\BoomerangTracker;
use App\Core\BoomerangManager;
use App\Customer;
use App\helpers\HttpStatuses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function storeIgoUser(Request $request)
    {
        if (!$data = $request->all()) {
            return response()->json([
                'message' => 'Invalid data sent'
            ], HttpStatuses::SERVER_ERROR_500);
        }

        if (empty($data['firstname'])
            || empty($data['lastname'])
            || empty($data['phone'])
            || empty($data['email'])
            || empty($data['boomerangTrackerId'])
            || empty($data['boomerangCode'])
        ) {
            return response()->json([
                'message' => 'Missing required parameters'
            ], HttpStatuses::SERVER_ERROR_500);
        }

        // create SOR for customer
        $boomerangTracker = BoomerangTracker::query()->where('id', $data['boomerangTrackerId'])->first();
        $password         = \utill::getRandomString(10);

        $result = \App\SaveOn::SORCreateUserWithToken_customers(
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $data['phone'],
            $password,
            $data['boomerangCode']
        );

        if ($result['status'] !== 'success') {
            return response()->json([
                'status' => 0,
                'message' => $result['msg']
            ], HttpStatuses::BAD_REQUEST_400);
        }

        // create customer
        $customer                 = new Customer();
        $customer->userid         = $boomerangTracker->userid;
        $customer->custid         = \App\Customer::getRandomCustomerId();
        $customer->name           = $data['firstname'] . ' ' . $data['lastname'];
        $customer->mobile         = $data['phone'];
        $customer->email          = $data['email'];
        $customer->boomerang_code = $data['boomerangCode'];
        $customer->created_date   = Carbon::today()->toDateString();
        $customer->sor_default_password = $password;
        $customer->save();

        $isUsed = 1;
        if ((int)$boomerangTracker->mode === (int)BoomerangTracker::MODE_GROUP
            && (int)$boomerangTracker->group_available !== 0
        ) {
            $isUsed = 0;
        }

        // update the boomerang tracker
        $boomerangTracker->customer_id = $customer->id;
        $boomerangTracker->seen        = 1;
        $boomerangTracker->is_used     = $isUsed;

        if ((int)$boomerangTracker->mode === (int)BoomerangTracker::MODE_GROUP) {
            $boomerangTracker->group_available = (int)$boomerangTracker->group_available - 1;
        }

        $boomerangTracker->save();

        try {
            //update the boomerang inventory
            $boomerangManager = new BoomerangManager($boomerangTracker->user_type);
            $boomerangManager->manager->utilizeBoomerang($boomerangTracker->userid);
        } catch (\Exception $ex) {

        }

        // send email
        \MyMail::sendCustomerNewAccount($data['firstname'], $data['lastname'], $data['email'], $password);
        return response()->json([
            'status' => 1,
            'email' => $data['email'],
            'password' => $password,
            'url' => 'https://bookings.igo4less.com'
        ], HttpStatuses::SUCCESS_200);
    }
}
