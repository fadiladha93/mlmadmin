<?php

namespace App\Http\Controllers\External;

use App\helpers\HttpStatuses;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function store(Request $request)
    {
        if (!$data = $request->all()) {
            return response()->json([
                'message' => 'Invalid data sent'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        if (empty($data['firstname'])
            || empty($data['lastname'])
            || empty($data['email'])
            || empty($data['phonenumber'])
            || empty($data['username'])
            || empty($data['password'])
            || empty($data['countryCode'])
        ) {
            return response()->json([
                'message' => 'Invalid data sent. Missing some mandatory fields. Please confirm with the documentation'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        try {
            $user = User::query()->create([
                'firstname'         => $data['firstname'],
                'lastname'          => $data['lastname'],
                'email'             => $data['email'],
                'phonenumber'       => $data['phonenumber'],
                'username'          => $data['username'],
                'usertype'          => User::USER_TYPE_CUSTOMER,
                'is_business'       => 0,
                'mobilenumber'      => !empty($data['mobile']) ? $data['mobile'] : null,
                'sync_with_mailgun' => 1,
                'password'          => password_hash($data['password'], PASSWORD_BCRYPT),
                'current_month_qv'  => 0,
                'current_month_pqv' => 0,
                'country_code'      => $data['countryCode'],
                'current_month_tsa' => 0,
                'current_month_cv'  => 0,
                'binary_q_l'        => 0,
                'binary_q_r'        => 0,
                'is_activate'       => 0,
                'is_bc_active'      => 0
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], HttpStatuses::SERVER_ERROR_500);
        }

        return response()->json([
            'data' => $user
        ], HttpStatuses::SUCCESS_200);
    }
}
