<?php

namespace App\Http\Controllers\External;

use App\Address;
use App\helpers\HttpStatuses;
use App\Order;
use App\PaymentMethod;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use utill;

class PaymentController extends Controller
{
    public function indexByUserId(Request $request, $userId)
    {
        if (!$data = $request->all()) {
            return response()->json([
                'error' => 'Invalid data sent'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        if (($paymentMethods = PaymentMethod::query()->where('userId', $userId)->get())->isEmpty()) {
            return response()->json([
                'error' => 'Unable to find the payment methods for user'
            ], HttpStatuses::SERVER_ERROR_500);
        }

        return response()->json([
            'data' => $paymentMethods
        ], HttpStatuses::SUCCESS_200);
    }

    public function authorizePayment(Request $request)
    {
        try {
            if (!$data = $request->all()) {
                return response()->json([
                    'error' => 'Invalid data sent'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (empty($data['userId']) || empty($data['paymentMethodId'])) {
                return response()->json([
                    'error' => 'Verification failed, some mandatory fields are missing. Please refer to the documentation'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (!$user = User::query()->find($data['userId'])) {
                return response()->json([
                    'error' => 'User not found. Unable to process this transaction'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            // check if user has credit card in file
            if (!$user->hasActiveCreditCard()) {
                return response()->json([
                    'error' => 'User does not have a valid credit card on file. Cannot continue transaction'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (!$paymentMethod = PaymentMethod::query()->find($data['paymentMethodId'])) {
                return response()->json([
                    'error' => 'Payment method not found. Unable to process this transaction'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if ($paymentMethod->pay_method_type === PaymentMethod::PAYMENT_METHOD_TYPE_EWALLET) {
                $response = \App\PayAP::verifyAccountNumber($user->id, $user->payap_mobile);

                if (!($response['response']->status == "success" && $response['response']->user > 0)) {
                    return response()->json([
                        'error' => 'Unable to verify the e-wallet account!'
                    ]);
                }

                // check amounts and auth
                $amount = $this->increaseAmount($data['amount']);
                if ($user->estimated_balance < $amount) {
                    $newAmount = $amount - $user->estimated_balance;
                    $response = $this->processPayment($paymentMethod, $newAmount);

                    $amount = $user->estimated_balance;
                    return response()->json([
                        'data' => $response
                    ], HttpStatuses::SUCCESS_200);
                }

                $user->estimated_balance -= $amount;
                $user->save();
            }

            $response = $this->processPayment($paymentMethod, $data['amount']);
            if (filter_var($response['error'], FILTER_VALIDATE_BOOLEAN)
                && empty($response['authorization'])
            ) {
                return response()->json([
                    'error' => 1,
                    'message' => "Authorization Failed:" . $response['msg']
                ], HttpStatuses::SERVER_ERROR_500);
            }

            return response()->json([
                'data' => $response['response']
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], HttpStatuses::SERVER_ERROR_500);
        }
    }

    public function capture(Request $request)
    {
        try {
            if (!$data = $request->all()) {
                return response()->json([
                    'error' => 'Invalid data sent'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (empty($data['userId']) || empty($data['paymentMethodId'])) {
                return response()->json([
                    'error' => 'Verification failed, some mandatory fields are missing'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (!$user = User::query()->find($data['userId'])) {
                return response()->json([
                    'error' => 'User not found. Unable to process this transaction'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            // check if user has credit card in file
            if (!$user->hasActiveCreditCard()) {
                return response()->json([
                    'error' => 'User does not have a valid credit card on file. Cannot continue transaction'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if (!$paymentMethod = PaymentMethod::query()->find($data['paymentMethodId'])) {
                return response()->json([
                    'error' => 'Payment method not found. Unable to process this transaction'
                ], HttpStatuses::BAD_REQUEST_400);
            }

            if ($paymentMethod->pay_method_type === PaymentMethod::PAYMENT_METHOD_TYPE_EWALLET) {
                $response = \App\PayAP::verifyAccountNumber($user->id, $user->payap_mobile);

                if (!($response['response']->status == "success" && $response['response']->user > 0)) {
                    return response()->json([
                        'error' => 'Unable to verify the e-wallet account!'
                    ]);
                }

                \App\EwalletTransaction::addNewWithdraw(
                    $user->id,
                    $data['amount'],
                    $user->estimated_balance,
                    $user->payap_mobile,
                    ''
                );
            } else {
                $address = Address::query()->find($paymentMethod->bill_addr_id);

                $response = \App\NMIGateway::processPayment(
                    $paymentMethod->firstname,
                    $paymentMethod->lastname,
                    $paymentMethod->expMonth,
                    $paymentMethod->expYear,
                    $paymentMethod->cvv,
                    self::increaseAmount($data['amount']),
                    $address->address1,
                    $address->city,
                    $address->stateprov,
                    $address->postalcode,
                    $address->countrycode,
                    $paymentMethod->pay_method_type,
                    \App\NMIGateway::TRANSACTION_TYPE_CAPTURE
                );
            }

            // create order
            $product = Product::query()->where('productname', 'Vibe Ride Payment')->first();
            $orderId = Order::addNew($user->id, $product->price, $product->price, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);

            return response()->json([
                'data' => $orderId
            ], HttpStatuses::SUCCESS_200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], HttpStatuses::SERVER_ERROR_500);
        }
    }

    public function store(Request $request)
    {
        if (!$data = $request->all()) {
            return response()->json([
                'error' => 'Invalid data sent'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        if (empty($data['token'])
            || empty($data['firstname'])
            || empty($data['lastname'])
            || empty($data['expMonth'])
            || empty($data['expYear'])
            || empty($data['cvv'])
            || empty($data['address1'])
            || empty($data['city'])
            || empty($data['stateprov'])
            || empty($data['postalCode'])
            || empty($data['countryCode'])
            || empty($data['isPrimaryAddress'])
        ) {
            return response()->json([
                'error' => 'Verification failed, some mandatory fields are missing. Please refer to the documentation'
            ], HttpStatuses::BAD_REQUEST_400);
        }

        //create address
        $address = Address::query()->create([
            'userid'      => $data['userId'],
            'addrtype'    => Address::TYPE_BILLING,
            'primary'     => $data['isPrimaryAddress'],
            'address1'    => $data['address1'],
            'address2'    => !empty($data['address2']) ? $data['address2'] : null,
            'city'        => $data['city'],
            'stateprov'   => $data['stateprov'],
            'postalcode'  => $data['postalCode'],
            'countrycode' => $data['countryCode'],
            'apt'         => !empty($data['apt']) ? $data['apt'] : null
        ]);

        if (!$address) {
            return response()->json([
                'error' => 'Unable to save billing address. Transaction cancelled!'
            ]);
        }

        try {
            $paymentMethod = PaymentMethod::query()->create([
                'userID'    => $data['userId'],
                'firstname' => $data['firstname'],
                'lastname'  => $data['lastname'],
                'token'     => $data['token'],
                'expMonth'  => $data['expMonth'],
                'expYear'   => $data['expYear'],
                'cvv'       => $data['cvv'],
                'created_at' => utill::getCurrentDateTime(),
                'updated_at' => utill::getCurrentDateTime(),
                'primary'    => 1,
                'pay_method_type' => 9,
                'bill_addr_id' => $address->id
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }

        return response()->json([
            'data' => $paymentMethod
        ], HttpStatuses::SUCCESS_200);
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param $amount
     * @return array
     * @throws \Exception
     */
    private function processPayment(PaymentMethod $paymentMethod, $amount)
    {
        if (!$address = Address::query()->find($paymentMethod->bill_addr_id)) {
            throw new \Exception('Associated card billing address not found for payment id #' . $paymentMethod->id);
        }

        $amount = self::increaseAmount($amount);

        $response = \App\NMIGateway::processPayment(
            $paymentMethod->token,
            $paymentMethod->firstname,
            $paymentMethod->lastname,
            $paymentMethod->expMonth,
            $paymentMethod->expYear,
            $paymentMethod->cvv,
            $amount,
            $address->address1,
            $address->city,
            $address->stateprov,
            $address->postalcode,
            $address->countrycode,
            $paymentMethod->pay_method_type,
            \App\NMIGateway::TRANSACTION_TYPE_AUTHORIZATION
        );

        return $response;
    }

    private function increaseAmount($amount)
    {
        return number_format($amount * 1.1, 0, '.', '');
    }
}
