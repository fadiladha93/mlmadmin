<?php

namespace App\Http\Controllers;

use App\Country;
use App\DiscountCoupon;
use App\helpers\CurrencyConverter;
use App\PaymentMethodType;
use App\Product;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Validator;

class UserEnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    private function generateDistId()
    {
        return 'TSA' . mt_rand(1000000, 9999999);
    }

    private function getUniqueDistId()
    {
        do {
            $distId = $this->generateDistId();
        } while (User::where('distid', '=', $distId)->exists());

        return $distId;
    }

    private function getSubscriptionPackages()
    {
        return Product::whereIsEnabled(1)
            ->whereIn('id', [52, 1, 2, 3, 4, 13])
            ->get();
    }

    private function getEnrollValidator()
    {
        $rules = [
            'username' => 'required|max:255|regex:/[a-zA-Z][a-zA-Z0-9]+/|unique:users,username',
            'default_password' => 'required',
            'sponsorid' => 'required|exists:users,distid',
            'enrollment_date' => 'required|date',
            'email' => 'required|email|unique:users,email',

            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'date_of_birth' => 'required|date',
            'subscription_product' => 'required|integer|exists:products,id',
            'subscription_package' => 'required|integer|exists:products,id',

            'business_name' => 'sometimes|max:255',
            'phonenumber' => 'required|max:255|unique:users,phonenumber',
            'mobilenumber' => 'sometimes|max:255|unique:users,mobilenumber',

            'address1' => 'required|max:255',
            'apt' => 'sometimes|max:50',
            'countrycode' => 'required|max:2',
            'city' => 'required|max:255',
            'stateprov' => 'max:50',
            'postalcode' => 'required|max:10',

            'payment_method_type' => 'required'
        ];

        $messages = [
            'username.required' => 'Username is required',
            'username.unique' => 'Username is in use. Please choose another.',
            'default_password.required' => 'Default password is required',
            'sponsorid.required' => 'Sponsor is required',
            'enrollment_date.required' => 'Enrollment date is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already in use',
            'email.email' => 'Invalid email address',

            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'date_of_birth.required' => 'Date of birth is required',

            'phonenumber.required' => 'Phone number is required',
            'phonenumber.unique' => 'Phone number is in use',

            'mobilenumber.required' => 'Mobile number is required',
            'mobilenumber.unique' => 'Mobile number is in use',

            'address1.required' => 'Address 1 is required',
            'city.required' => 'City is required',
            'stateprov.required' => 'State/Province is required',
            'postalcode.required' => 'Postal Code is required',
        ];

        return Validator::make(request()->all(), $rules, $messages);
    }

    private function determineMaxPrice($packageId)
    {
        $subscriptionPackages = $this->getSubscriptionPackages();

        $maxPrice = (double)$subscriptionPackages[0]->price;

        if ($packageId > 1) {
            $maxPrice += (double)$subscriptionPackages[$packageId - 1]->price;
        }

        return $maxPrice;
    }

    private function getCreditCardValidator()
    {

        $rules = [
            'credit_card_name' => 'required',
            'credit_card_number' => 'required',
            'expiration_date' => 'required',
            'cvv' => 'required|between:2,4',
        ];

        $messages = [
            'credit_card_name.required' => 'Cardholder Name is required',
            'credit_card_number.required' => 'Credit card number is required',
            'cvv.required' => 'CVV is required',
            'cvv.max' => 'CVV cannot exceed 4 characters',
            'expiration_date.required' => 'Expiration date is required',
        ];


        return Validator::make(request()->all(), $rules, $messages);
    }

    public function enrollForm()
    {
        $eventTicket = Product::find(Product::ID_TICKET);
        $videoTraining = Product::find(56);
        $standBy = Product::find(1);

        return view('admin.user.frmEnroll', [
            'countries' => Country::getAll(),
            'packages' => $this->getSubscriptionPackages(),
            'subscription_products' => Product::getSubscriptionProducts(),
            'distId' => $this->getUniqueDistId(),
            'eventTicketPrice' => $eventTicket->price,
            'videoTrainingPrice' => $videoTraining->price,
            'standByPrice' => $standBy->price
        ]);
    }

    public function enrollAction()
    {
        $validator = $this->getEnrollValidator();

        if ($validator->fails()) {
            return [
                'error' => 1,
                'msg' => $this->generateErrorMessageFromValidator($validator)
            ];
        }

        $package = request()->post('subscription_package');
        $paymentMethodType = request()->post('payment_method_type');

        $creditCardTypes = [
            PaymentMethodType::TYPE_METROPOLITAN,
            PaymentMethodType::TYPE_T1_PAYMENTS,
            PaymentMethodType::TYPE_PAYARC
        ];

        if (in_array($paymentMethodType, $creditCardTypes)) {
            $validator = $this->getCreditCardValidator();

            if ($validator->fails()) {
                return [
                    'error' => 1,
                    'msg' => $this->generateErrorMessageFromValidator($validator)
                ];
            }
        }

        $url = env('ENROLLMENT_BASE_URL') . '/api/users/enroll';
        $guzzleClient = new Client();
        $data = request()->except(['_token']);
        $data['apiToken'] = env('ENROLLMENT_API_TOKEN');

        if (request()->addEventTicket == 'on' && !in_array($package, [1,13])) {
            $data['ticket_product_id'] = 38;
        } else if (request()->addVideoTraining == 'on' && $package == 13) {
            $data['ticket_product_id'] = 56;
        }

        try {
            $response = $guzzleClient->post($url, [
                'form_params' => $data
            ]);
        } catch (RequestException $requestException) {
            if ($requestException->hasResponse()) {
                return $requestException->getResponse()->getBody()->getContents();
            }

            return [
                'error' => 1,
                'msg' => 'Enrollment API issue. Please contact ibuumerang.'
            ];
        }

        $resultJson = $response->getBody()->getContents();
        return json_decode($resultJson, true);
    }

    public function verifyVoucher()
    {
        $rules = [
            'voucher_code' => 'required|alphanum|size:6|exists:discount_coupon,code'
        ];

        $messages = [
            'voucher_code.required' => 'Voucher code is required',
            'voucher_code.alphanum' => 'Voucher code is invalid',
            'voucher_code.size' => 'Voucher code is invalid',
            'voucher_code.exists' => 'Voucher code is invalid'
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => 1,
                'msg' => implode("<br>-", $validator->errors()->get('voucher_code'))
            ]);
        }

        $coupon = DiscountCoupon::whereCode(request()->post('voucher_code'))
            ->where('is_used', '=', '0')
            ->where('is_active', '=', 1)
            ->whereNull('used_by')
            ->first();

        if (!$coupon) {
            return response()->json([
                'error' => 1,
                'msg' => 'Voucher code is invalid or used'
            ]);
        }

        $total = filter_var(request()->post('total'), FILTER_VALIDATE_FLOAT);
        $couponAmount = $coupon->discount_amount;

        if ($total > $couponAmount) {
            return response()->json([
                'error' => 1,
                'msg' => 'Insufficient voucher balance for this order'
            ]);
        }

        return response()->json([
            'error' => 0,
            'msg' => 'Voucher code is valid'
        ]);
    }

    public function convert()
    {
        $rules = [
            'amount' => 'required|numeric|gte:0',
            'country' => 'required|size:2|regex:/[a-zA-Z][a-zA-Z]/'
        ];

        $messages = [
            'amount.required' => 'Amount is required',
            'country.required' => 'Country is required',
            'country.size' => 'Country is invalid',
            'country.regex' => 'Country is invalid'
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);

        if ($validator->fails()) {
            return [
                'error' => 1,
                'msg' => $this->generateErrorMessageFromValidator($validator)
            ];
        }

        $amount = floatval(request()->get('amount')) * 100;
        $country = request()->get('country');

        $currencyResult = CurrencyConverter::convert($amount, $country);

        if (!$currencyResult) {
            return [];
        }

        return response()->json([
            'error' => 0,
            'display_amount' => $currencyResult['display_amount']
        ]);
    }
}
