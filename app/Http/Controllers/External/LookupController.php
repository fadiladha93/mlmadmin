<?php

namespace App\Http\Controllers\External;

use App\helpers\External\Lookup;
use App\helpers\HttpStatuses;
use App\Http\Controllers\Controller;

class LookupController extends Controller
{
    public function index($type = null, $value = null)
    {
        if (empty($type) || empty($value)) {
            return response()->json([
                'message' => 'Invalid data sent. The type and value cannot be null'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        try {
            $response = (new Lookup($type, $value))->build()->get();
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], HttpStatuses::SERVER_ERROR_500);
        }

        return response()->json([
            'data' => $response,
        ], HttpStatuses::SUCCESS_200);
    }
}
