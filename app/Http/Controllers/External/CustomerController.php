<?php

namespace App\Http\Controllers\External;

use App\Customer;
use App\helpers\HttpStatuses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        if (!$data = $request->only((new Customer())->getFillable())) {
            return response()->json([
                'message' => 'Invalid data sent'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        try {
            $customer = Customer::query()->create([
                'userid' => null,
                'name'   => !empty($data['name']) ? $data['name'] : null,
                'email'  => !empty($data['email']) ? $data['email'] : null,
                'mobile' => !empty($data['mobile']) ? $data['mobile'] : null,
                'boomerang_code' => null,
                'created_date'   => Carbon::today()->toDateString(),
                'sync_with_mailgun' => 1,
                'custid'            => null
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], HttpStatuses::SERVER_ERROR_500);
        }

        return response()->json([
            'data' => $customer
        ], HttpStatuses::SUCCESS_200);
    }
}
