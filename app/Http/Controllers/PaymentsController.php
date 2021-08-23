<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\GeoIP;
use App\PaymentMethod;
use App\UserActivityHistory;
use Illuminate\Http\Request;
use App\Models\UserActivityLog;
use App\Models\UserPaymentMethod;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'bitPayCallBack',
            'bitPayRefundCallBack',
            'skrilCancel',
            'skrilCallback',
        ]]);
        $this->middleware('auth.affiliate');
    }

    public function bitPayRefundCallBack(Request $request)
    {
        \App\Helper::bitPayRefundPayment($request);
    }
    public function bitPayCallBack(Request $request)
    {
        \App\Helper::bitPayPayment($request);
    }

    public function skrilCancel(Request $request)
    {
    }

    public function skrilCallback(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            file_put_contents('skrill_success1.txt', json_encode($data), FILE_APPEND);
        } catch (\Exception $ex) {
            file_put_contents('skrill_error1.txt', print_r($data, true));
        }

        try {
            $response = new SkrillStatusResponse($_POST);
            file_put_contents('skrill_success2.txt', print_r($response, true));
        } catch (SkrillException $e) {
            # something bad in request
            file_put_contents('skrill_error2.txt', print_r($data, true));
        }

        /*
        SkrillStatusResponse model contains attributes only for required Skrill response parameters
        To get all of them use:
        */
        $allParams = $response->getRaw();
        file_put_contents('skrill_success3.txt', json_encode($allParams));
        die;
        if ($response->verifySignature('your Skrill secret word') && $response->isProcessed()) {
            # bingo! You need to return anything with 200 OK code! Otherwise, Skrill will retry request
        }

        # Or:

        if ($response->isFailed()) {
            # Note that you should enable receiving failure code in Skrill account before
            # It will not provided with default settings
            $errorCode = $response->getFailedReasonCode();
        }

        /*
        Also you can retrieve any Skrill response parameter and make extra validation you want.
        To see all Skrill response parameters just view SkrillStatusResponse class attributes
        For example:
        */
        if ($response->getPayToEmail() !== 'mymoneybank@mail.com') {
            // hum, it's very strange ...
        }

        /* Also you can log Skrill response data using simple built-in logger */
        $response->log('/path/to/writable/file');
    }

    public function getLookupPayment()
    {
        return view('admin.payments.lookupPayment');
    }

    public function postLookupPayment(Request $request)
    {
        $rules = ['numbers4' => 'required|numeric|min:4'];
        $rules = ['numbers6' => 'required|numeric|min:6'];

        $messages = [
            'required' => 'This :attribute field is required',
            'numeric' => 'This :attribute field must be a numeric'
        ];

        $validator = Validator::make($request->only('numbers4', 'numbers6'), $rules, $messages)->validate();

        $payments = UserPaymentMethod::getPaymentsWithNumbers($request->numbers6, $request->numbers4);

        $query['numbers4'] = $request->numbers4;
        $query['numbers6'] = $request->numbers6;

        return view('admin.payments.lookupPayment', compact('payments', 'query'));
    }

    public function markAsDeleted($id)
    {

        if ($userPaymentMethod = UserPaymentMethod::findOrFail($id)) {
            $old_data = $userPaymentMethod->toJson();
            $userPaymentMethod->is_deleted = true;
            $userPaymentMethod->active = false;
            $userPaymentMethod->is_primary = false;
            $userPaymentMethod->is_restricted = true;
            $userPaymentMethod->save();

            $userPaymentMethod->delete();
            $new_data = $userPaymentMethod->toJson();
            $log = new UserActivityLog();
            $response = GeoIP::getInformationFromIP(request()->ip());
            $log->ip_address = request()->ip();
            $log->user_id = auth()->user()->id;
            $log->ip_details = $response;
            $log->old_data = $old_data;
            $log->new_data = $new_data;
            $log->action = 'DELETE Payment Method '.$id;
            $log->save();

            return response()->json($userPaymentMethod);
        };
    }

    public function getLookupPaymentAllTransaction(Request $request)
    {
        $first = $request->first;
        $last  = $request->last;
        $id    = $request->id;

        if ((int)$id) {
            $userPaymentMethodsId = [$id];
        } else {
            $payments = UserPaymentMethod::getPaymentsWithNumbers($first, $last);
            $userPaymentMethodsId = $payments->pluck('id')->toArray();
        }

        $orders = \App\Order::whereIn('user_payment_methods_id', $userPaymentMethodsId)
            ->leftJoin('statuscode', 'statuscode.id', '=', 'orders.statuscode')
            ->select('orders.*', 'statuscode.status_desc')
            ->get();

        return view('admin.payments.view-transactions', [
            'orders' => $orders,
            'first'  => $first,
            'last'   => $last
        ]);
    }

}
