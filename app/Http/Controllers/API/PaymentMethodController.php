<?php

namespace App\Http\Controllers\API;

use App\helpers\HttpStatuses;
use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (!$data = $request->only((new PaymentMethod())->getFillable())) {
            return response()->json([
                'message' => 'Invalid data sent'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        if (!$paymentMethod = PaymentMethod::query()->find($id)) {
            return response()->json([
                'message' => 'Resource not found'
            ], HttpStatuses::RESOURCE_NOT_FOUND_404);
        }

        try {
            $paymentMethod = $paymentMethod->update($data);

            return response()->json([
                'data' => $paymentMethod
            ], HttpStatuses::SUCCESS_200);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], HttpStatuses::UNKNOWN_ERROR);
        }
    }
}
