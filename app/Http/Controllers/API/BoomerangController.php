<?php

namespace App\Http\Controllers\API;

use App\BoomerangTracker;
use App\Customer;
use App\helpers\HttpStatuses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoomerangController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $id)
    {

        if (!$bommerangTracker = BoomerangTracker::getRecordById($id)) {
            return response()->json([
                'message' => 'Unable to find the resource'
            ], HttpStatuses::RESOURCE_NOT_FOUND_404);
        }

        return response()->json($bommerangTracker, HttpStatuses::SUCCESS_200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCode($code)
    {
        if (empty($code)) {
            return response()->json([
                'message' => 'Boomerang code is missing'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        if (!BoomerangTracker::isAvailable($code)) {
            return response()->json([
                'message' => 'Please enter a valid code'
            ], HttpStatuses::RESOURCE_NOT_FOUND_404);
        }

        if (!$tracker = BoomerangTracker::getValidCodeRecord($code)) {
            return response()->json([
                'message' => 'Please enter a valid code'
            ], HttpStatuses::RESOURCE_NOT_FOUND_404);
        }

        return response()->json($tracker, HttpStatuses::SUCCESS_200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmUser(Request $request)
    {
        try {
            if (!$data = $request->all()) {
                response()->json([
                    'message' => 'Bad request sent'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (
                empty($data['firstname'])
                || empty($data['lastname'])
                || empty($data['phone'])
                || empty($data['email'])
                || empty($data['boomerangTrackerId'])
                || empty($data['boomerangCode'])
            ) {
                return response()->json([
                    'message' => 'Missing required parameters'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            // get boomerang tracker
            $boomerangTracker = BoomerangTracker::query()->find($data['boomerangTrackerId']);

            // create customer
            $customer                 = new Customer();
            $customer->userid         = $boomerangTracker->userid;
            $customer->custid         = \App\Customer::getRandomCustomerId();
            $customer->name           = $data['firstname'] . ' ' . $data['lastname'];
            $customer->mobile         = $data['phone'];
            $customer->email          = $data['email'];
            $customer->boomerang_code = $data['boomerangCode'];
            $customer->created_date   = Carbon::today()->toDateString();
            $customer->save();

            $isUsed = 1;
            if (
                (int)$boomerangTracker->mode === (int)BoomerangTracker::MODE_GROUP
                && (int)$boomerangTracker->group_available !== 0
            ) {
                $isUsed = 0;
            }

            // update the boomerang tracker
            $boomerangTracker->customer_id     = $customer->id;
            $boomerangTracker->seen            = 1;
            $boomerangTracker->is_used         = $isUsed;
            $boomerangTracker->save();

            return response()->json([
                'data' => true
            ], HttpStatuses::SUCCESS_200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Sorry an unknown error occurred!'
            ], HttpStatuses::SERVER_ERROR_500);
        }
    }
}
