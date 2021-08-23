<?php

namespace App\Http\Controllers;

use Auth;
use Authy\AuthyApi;
use DataTables;
use DB;
use Validator;
use Log;
use File;
use Response;
use App\TwilioAuthy;
use Illuminate\Http\Request;

class EwalletTransactionController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'index',
                'transferToPayap',
                'dlgTranferHistory',
            'getTransferHistoryDataTable',
            'getWithdrawalsDataTables',
            'transferToIPayOut',
            'download1099',
            'vitalsSubmit',
            'submitTFA',
            'resendTFA',
            'showTaxPDF'
        ]]);
        $this->middleware('auth');
    }


    public function index() {
        $d = array();
        $d['fee'] = \App\EwalletTransaction::TRANSACTION_FEE;
        $payoutType = \App\Helper::getPayoutPaymentMethod(Auth::user()->id, Auth::user()->countrycode);
        $d['error_address'] = false;
        if (empty($payoutType)) {
            $d['payout_type'] = '';
            $d['error_address'] = true;
        } else {
            $d['payout_type'] = $payoutType->type;
        }
        $d['found1099']     = File::exists(storage_path('2019-1099/1099_'.Auth::user()->distid).'.pdf');
        $d['balance']       = Auth::user()->estimated_balance;
        $d['trans']         = \App\EwalletTransaction::getLatestTen(Auth::user()->id);
        $d['show_2fa_modal']= true;
        $d['error2fa']      = false;
        session(['resent_2fa_count' => 0]);
        session(['failed_2fa_count' => 0]);

        return view('affiliate.ewallet.index')->with($d);
    }

    public function transferToPayap() {
        $req = request();
        //user country code
        $countryCode = \App\Address::select('*')->where('countrycode', 'US')->where('userid', Auth::user()->id)->count();
        // validate  SSN, EIN or FID
        if (\utill::isNullOrEmpty(Auth::user()->ssn) && $countryCode > 0) {
            return response()->json(['error' => 0, 'show_payap_config_dlg' => 2]);
        }
//        if (\utill::isNullOrEmpty(Auth::user()->fid)) {
//            return response()->json(['error' => 0, 'show_payap_config_dlg' => 2]);
//        }
        // validate payap_mobile is filled
        $payap_mobile = Auth::user()->payap_mobile;
        if (\utill::isNullOrEmpty($payap_mobile)) {
            return response()->json(['error' => 0, 'show_payap_config_dlg' => 1]);
        }
        // validate payap mobile
        $response = \App\PayAP::verifyAccountNumber(Auth::user()->id, $payap_mobile);
        $response = $response['response'];
        if (!($response->status == "success" && $response->user > 0))
            return response()->json(['error' => 0, 'show_payap_config_dlg' => 1]);
        //
        $vali = $this->validateTransfer();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        // transfer to payap
        $memo = date('Y-m-d');
        $signature = 1;
        $response = \App\PayAP::makePayment(Auth::user()->id, $payap_mobile, 'USD', $req->amount, $memo, $signature);
        $response = $response['response'];
        if (!isset($response->status)) {
            return response()->json(['error' => '1', 'msg' => "Error from Payap. Please try again in few minuites."]);
        } else {
            if ($response->status == "failed") {
                return response()->json(['error' => '1', 'msg' => $response->status]);
            } else if ($response->status == "auth_failed") {
                return response()->json(['error' => '1', 'msg' => $response->status]);
            } else if ($response->status == "success") {
                $tid = $response->tid;
                $remarks = "Payap transaction ID : " . $tid;
                \App\EwalletTransaction::addNewWithdraw(Auth::user()->id, $req->amount, Auth::user()->estimated_balance, $payap_mobile, $remarks);
                return response()->json(['error' => '0', 'url' => 'reload']);
            }
        }
    }


    public function transferToIPayOut()
    {
        $req = request();
        //user country code
        $vali = $this->validateTransfer();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        // transfer to ipayout
        $ipayout_user_ref = \App\IPayOut::getIPayoutByUserId(Auth::user()->id);
        if (empty($ipayout_user_ref)) {
            $user = \App\User::getById(Auth::user()->id);
            $response = \App\Helper::createiPayoutUser($user);
            if ($response['error'] == 1 && $response['msg'] != 'A user with this UserName already exists') {
                return response()->json(['error' => 1, 'msg' => $response['msg']]);
            } else if ($response['error'] == 1 && $response['msg'] == 'A user with this UserName already exists') {
                \App\IPayOut::addUser(Auth::user()->id, time());
            }
        }
        $ipayout_user_ref = \App\IPayOut::getIPayoutByUserId(Auth::user()->id);
        if (empty($ipayout_user_ref)) {
            return response()->json(['error' => 1, 'msg' => 'iPayout account not setup to your account.']);
        }
        $response = \App\Helper::iPayoutCommission(Auth::user()->username, Auth::user()->id, $req->amount);
        if ($response['error'] == 1) {
            return response()->json(['error' => '1', 'msg' => "Error from iPayout. " . $response['msg']]);
        } else {
            $tid = $response['TransactionRefID'];
            $remarks = "iPayout transaction ID : " . $tid;
            \App\EwalletTransaction::addNewWithdraw(Auth::user()->id, $req->amount, Auth::user()->estimated_balance, null, $remarks);
                return response()->json(['error' => '0', 'url' => 'reload']);
        }
    }

    public function pendingList() {
        $d = array();
        $payap_csv_id = 0;
        $payap_rec_count = 0;
        if (!\utill::isNullOrEmpty(session('payap_csv_id'))) {
            $payap_csv_id = session('payap_csv_id');
            $payap_rec_count = session('payap_rec_count');
            // reset session
            session(['payap_csv_id' => "", 'payap_rec_count' => ""]);
        }
        $d['payap_csv_id'] = $payap_csv_id;
        $d['payap_rec_count'] = $payap_rec_count;
        $q = DB::table('v_ewallet_transactions')
                ->selectRaw('sum(amount) as total')
                ->where('type', \App\EwalletTransaction::TYPE_WITHDRAW)
                ->where('csv_generated', 0)
                ->first();
        $d['total'] = $q->total;
        return view('admin.ewallet_transactions.pending')->with($d);
    }

    public function getPendingDataTable() {
        $query = DB::table('v_ewallet_transactions')
                ->where('type', \App\EwalletTransaction::TYPE_WITHDRAW)
                ->where('csv_generated', 0);
        return DataTables::of($query)->toJson();
    }

    public function transfer() {
        $recs = \App\EwalletTransaction::recsToTransfer();
        if ($recs->count() == 0) {
            return response()->json(['error' => '1', 'msg' => 'No records are found to transfer']);
        } else {
            $csvId = \App\EwalletCSV::addNew($recs);
            session(['payap_csv_id' => $csvId, 'payap_rec_count' => $recs->count()]);
            return response()->json(['error' => '0', 'url' => url('/commission/pending')]);
        }
    }

    private function validateTransfer() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'amount' => 'required|numeric',
                        ], [
                    'amount.required' => 'Amount to be transferred is required',
                    'amount.numeric' => 'Amount must be numeric',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
            //
            if ($req->amount < 0) {
                $valid = 0;
                $msg = "Invalid amount";
            }
            $balance = Auth::user()->estimated_balance;
            $availableBalace = $balance - \App\EwalletTransaction::TRANSACTION_FEE;
            if ($availableBalace < 0) {
                $valid = 0;
                $msg = "Insufficient available balance";
            } else if ($availableBalace < $req->amount) {
                $valid = 0;
                $msg = "Amount to be transferred cannot be exceeded " . number_format(floor($availableBalace), 2);
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function dlgTranferHistory() {
        return view('affiliate.ewallet.dlg_transfer_history');
    }

    public function getTransferHistoryDataTable() {
        $query = DB::select('select *, (created_at::timestamp::date)AS date from ewallet_transactions WHERE user_id =' . Auth::user()->id . 'order by id desc');
        return DataTables::of($query)->toJson();
    }

    public function withdrawalsList($from = null, $to = null) {
        $d = array();
        $d['from'] = $from;
        $d['to'] = $to;
        if ($from != null && $to != null) {
            $total_amount = DB::table('v_ewallet_transactions_users')
                            ->select(DB::raw('sum(amount) as total_amount'))
                            ->where('type', \App\EwalletTransaction::TYPE_WITHDRAW)
                            ->where('csv_generated', 2)
                            ->whereBetween(DB::raw('DATE(created_at)'), array($from, $to))->first();
        } else {
            $total_amount = DB::table('v_ewallet_transactions_users')
                            ->select(DB::raw('sum(amount) as total_amount'))
                            ->where('type', \App\EwalletTransaction::TYPE_WITHDRAW)
                            ->where('csv_generated', 2)->first();
        }
        $d['total'] = $total_amount->total_amount;

        return view('admin.commission.withdrawals')->with($d);
    }

    public function getWithdrawalsDataTables() {
        $req = request();
        if ($req->from != "" && $req->to != "") {
            $query = DB::table('v_ewallet_transactions_users')
                    ->where('type', \App\EwalletTransaction::TYPE_WITHDRAW)
                    ->where('csv_generated', 2)
                    ->whereBetween(DB::raw('DATE(created_at)'), array($req->from, $req->to));
        } else {
            $query = DB::table('v_ewallet_transactions_users')
                    ->where('type', \App\EwalletTransaction::TYPE_WITHDRAW)
                    ->where('csv_generated', 2);
        }
        return DataTables::of($query)->toJson();
    }

    /**
     * Verify user before handing out the 1099 pdf
     *
     */
    public function download1099($filename) {
        Log::info("testing ");
        //This method will look for the file and get it from drive
        $path = storage_path('2019-1099/' . $filename);
        try {
            $user = Auth::user();
            $split_file = explode("_",$filename);
            //Check if the TSA on filename match the user's TSA
            if($user->distid === substr($split_file[1], 0, -4)){
                Log::info("user distid ".$user->distid." -- Filename ".substr($split_file[1], 0, -4));
                $file = File::get($path);
                $type = File::mimeType($path);
                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);
                return $response;
            }else{
                abort(404);
            }
        } catch (FileNotFoundException $exception) {
            abort(404);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function vitalsSubmit(Request $request)
    {
        $user = Auth::user();
        $authyApi = new AuthyApi(env('AUTHY_API_KEY'));

        $phoneNumber = $this->getUserPhoneNumberFor2FA($user);

        $response = $authyApi->phoneVerificationStart($phoneNumber, '+1', 'sms', 7);

        $success = $response->ok();
        session()->forget(['success_2fa']);

        return ['success' => $success];
    }

    /**
     * @return array
     */
    public function submitTFA()
    {
        $user = Auth::user();

        $verificationCode = request()->post('verification_code');

        $authyApi = new AuthyApi(env('AUTHY_API_KEY'));

        $phoneNumber = $this->getUserPhoneNumberFor2FA($user);

        $response = $authyApi->phoneVerificationCheck($phoneNumber, '+1', $verificationCode);

        $success = $response->ok();
        //To change the path to the pdf, change the url parameter of the next array
        if ($success) {
            return ['success' => $success, 'url' => '/1099/1099_' . $user->distid . '.pdf', 'target_blank' => 1];
        }

        return ['success' => $success, 'msg' => $response->message()];
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendTFA()
    {
        $user = Auth::user();

        $authyApi = new AuthyApi(env('AUTHY_API_KEY'));
        $phoneNumber = $this->getUserPhoneNumberFor2FA($user);

        $response = $authyApi->phoneVerificationStart($phoneNumber, '+1', 'sms', 7);
        //
        if ($response->ok()) {
            session(['resent_2fa_count' => 0]);

            return response()->json(['error' => '0', 'resent_count' => 0, 'msg' => 'We have re-sent the verification code']);
        }

        $resentCount = session('resent_2fa_count', 0);
        session(['resent_2fa_count' => ++$resentCount]);

        return response()->json(['error' => '1', 'resent_count' => $resentCount, 'msg' => $response->message()]);
    }

    private function getUserPhoneNumberFor2FA($user)
    {
        $phoneNumber = $user->mobilenumber;

        if (!$phoneNumber) {
            $phoneNumber = $user->phonenumber;
        }

        return $phoneNumber;
    }
}
