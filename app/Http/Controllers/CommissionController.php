<?php

namespace App\Http\Controllers;

use App\Commission;
use App\CommissionDates;
use App\CommissionTemp;
use App\CommissionTempPost;
use App\Jobs\LeadershipCommission;
use App\Jobs\UnilevelCommission;
use App\Order;
use App\Product;
use App\UpdateHistory;
use App\User;
use Carbon\Carbon;
use DataTables;
use DB;
use Validator;

class CommissionController extends Controller {

    public function __construct() {
        set_time_limit(0);
        $this->middleware('auth.admin');
    }

    public function showEngine() {
        $d = array();
        $d['from'] = \App\Commission::getComEngFromDate();
        $d['to'] = \App\Commission::getComEngToDate();
        //
        return view('admin.commission.engine')->with($d);
    }

    public function run() {
        $req = request();
        $vali = $this->validateInput();

        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }

        Commission::setComEngFromDate($req->from);
        Commission::setComEngToDate($req->to);

        $startDate = Carbon::parse($req->from)->startOfDay();
        $endDate = Carbon::parse($req->to)->endOfDay();

        DB::select("select * from calculate_fsb_7levels('" . $startDate . "','" . $endDate . "')");

        return response()->json(['error' => '0', 'url' => url('/commission-detail')]);
    }

    public function TsbComissionRun()
    {
        $req = request();
        $vali = $this->validateInput();

        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        Commission::setComEngFromDate($req->from);
        Commission::setComEngToDate($req->to);

        $startDate = Carbon::parse($req->from)->startOfDay();
        $endDate = Carbon::parse($req->to)->endOfDay();


        return response()->json(['error' => '0', 'url' => url('/tsb-commission-detail')]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateUnilevelCommission()
    {
        $request = request();
        $validation = $this->validateInput();
        if ($validation['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $validation['msg']]);
        }

        try {
            UnilevelCommission::dispatch($request->from, $request->to)->onQueue('default');

            $result['error'] = 0;
            $result['msg'] = 'Commission has been run successfully.';
        } catch (\Exception $e) {
            $result['msg'] = $e->getMessage();
            $result['error'] = 1;
        }

        return response()->json($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateLeadershipCommission()
    {
        $request = request();
        $validation = $this->validateInput();
        if ($validation['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $validation['msg']]);
        }

        try {
            LeadershipCommission::dispatch($request->from, $request->to)->onQueue('default');

            $result['error'] = 0;
            $result['msg'] = 'Commission has been run successfully.';
        } catch (\Exception $e) {
            $result['msg'] = $e->getMessage();
            $result['error'] = 1;
        }

        return response()->json($result);
    }

    public function showCommission_summary()
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        $q = DB::table('vcommission_user_temp')
                ->selectRaw('sum(amount) as total')
                ->whereDate('transaction_date', '>=', $from)
                ->whereDate('transaction_date', '<=', $to)
                ->first();

        $d = array();
        $d['from'] = $from;
        $d['to'] = $to;
        $d['total'] = $q->total;
        return view('admin.commission.summary')->with($d);
    }

    public function showTsbCommissionSummary()
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        $q = \App\TSBCommission::getSumByDateRange($from, $to);
        $d = array();
        $d['from'] = $from;
        $d['to'] = $to;
        $d['total'] = $q;
        return view('admin.commission.tsb-summary')->with($d);
    }

    public function getSummaryDataTable() {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        if ($from && $to) {
            $query = DB::table('vcommission_user_temp')
                ->selectRaw('distid, username, sum(amount) as amount')
                ->whereDate('transaction_date', '>=', $from)
                ->whereDate('transaction_date', '<=', $to)
                ->groupBy('distid', 'username');
        } else {
            $query = DB::table('vcommission_user_temp')
                ->selectRaw('distid, username, sum(amount) as amount')
                ->groupBy('distid', 'username');
        }

        return DataTables::of($query)->toJson();
    }

    public function getTsbSummaryDataTable()
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        if ($from && $to) {
            $query = DB::table('tsb_commission')
                ->select('users.distid', 'users.username', 'tsb_commission.*')
                ->selectRaw('sum(amount) as amount')
                ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                ->whereIn('tsb_commission.status', ['approved', 'pending'])
                ->whereDate('paid_date', '>=', $from)
                ->whereDate('paid_date', '<=', $to)
                ->groupBy('tsb_commission.id', 'users.distid', 'users.username');

        } else {
            $query = DB::table('tsb_commission')
                ->select('users.distid', 'users.username', 'tsb_commission.*')
                ->selectRaw('sum(amount) as amount')
                ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                ->whereIn('tsb_commission.status', ['approved', 'pending'])
                ->groupBy('tsb_commission.id', 'users.distid', 'users.username');
        }
        return DataTables::of($query)->toJson();
    }

    public function showCommission_detail() {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        $q = DB::table('vcommission_user_temp')
                ->selectRaw('sum(amount) as total')
                ->whereDate('transaction_date', '>=', $from)
                ->whereDate('transaction_date', '<=', $to)
                ->first();
        //
        $d = array();
        $d['from'] = $from;
        $d['to'] = $to;
        $d['total'] = $q->total;
        return view('admin.commission.detail')->with($d);
    }

    public function showTsbCommissionDetail()
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        $q = \App\TSBCommission::getSumByDateRange($from, $to);
        //
        $d = array();
        $d['from'] = $from;
        $d['to'] = $to;
        $d['total'] = $q;
        return view('admin.commission.tsb-detail')->with($d);
    }

    public function showCommission_post() {
        /* $from = \App\Commission::getComEngFromDate();
          $to = \App\Commission::getComEngToDate(); */

        $dates = CommissionDates::getPostDate();

        $from = $dates['from'];
        $to = $dates['to'];

        $d = array();
        if (!empty($from) && !empty($to)) {
            $d['from'] = $dates['from'];
            $d['to'] = $dates['to'];

            $q = DB::table('vcommission_user_temp_post')
                    ->selectRaw('sum(amount) as total')
                    ->whereDate('transaction_date', '>=', $from)
                    ->whereDate('transaction_date', '<=', $to)
                    ->first();

            $d['total'] = $q->total;
        }

        return view('admin.commission.detail-post')->with($d);
    }

    public function getDetailDataTable() {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        if ($from && $to) {
            $query = DB::table('vcommission_user_temp')
                ->whereDate('transaction_date', '>=', $from)
                ->whereDate('transaction_date', '<=', $to);
        } else {
            $query = DB::table('vcommission_user_temp');
        }

        return DataTables::of($query)->toJson();
    }


    public function getTsbDetailDataTable()
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        if ($from && $to) {
            $query = DB::table('tsb_commission')
                ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                ->select('tsb_commission.paid_date', 'users.distid', 'users.username', 'tsb_commission.amount', 'tsb_commission.memo')
                ->whereIn('tsb_commission.status', ['pending','approved'])
                ->whereDate('tsb_commission.paid_date', '>=', $from)
                ->whereDate('tsb_commission.paid_date', '<=', $to);
        } else {
            $query = DB::table('tsb_commission')
                ->select('users.distid', 'users.username', 'tsb_commission.*')
                ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                ->select('tsb_commission.paid_date', 'users.distid', 'users.username', 'tsb_commission.amount', 'tsb_commission.memo')
                ->whereIn('tsb_commission.status', ['pending','approved']);
        }
        return DataTables::of($query)->toJson();
    }

    public function getPostDetailDataTable() {
        /* $from = \App\Commission::getComEngFromDate();
          $to = \App\Commission::getComEngToDate(); */

        $dates = CommissionDates::getPostDate();

        $from = $dates['from'];
        $to = $dates['to'];

        $query = DB::table('vcommission_user_temp_post')
                ->whereDate('transaction_date', '>=', $from)
                ->whereDate('transaction_date', '<=', $to);

        return DataTables::of($query)->toJson();
    }

    public function approve() {
        DB::select("select * from calculate_fsb_approve()");
        return response()->json(['error' => '0', 'url' => url('/commission-engine')]);
    }

    public function tsbCommissionApprove() {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        try {
            $ids = DB::table('tsb_commission')
                ->select('tsb_commission.id')
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('tsb_commission.paid_date', '>=', $from)
                ->whereDate('tsb_commission.paid_date', '<=', $to)
                ->pluck('id')->toArray();
            if (count($ids) <= 0) {
                return response()->json(['error' => '1', 'msg' => 'There is no TSB commission to approve...']);
            }
            DB::table('tsb_commission')->whereIn('id', $ids)->where('status', 'pending')->update(['status' => 'approved']);
            return response()->json(['error' => '0', 'msg' => count($ids) . ' TSB Commissions approved. You can post TSB Commission now.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => '1', 'msg' => $ex->getMessage()]);
        }
    }

    public function post() {
        $isEmpty = CommissionTempPost::isEmpty();
        if (!$isEmpty) {
            return response()->json(['error' => '1', 'msg' => 'Not Allowed']);
        }

        CommissionTempPost::insert(CommissionTemp::all()->toArray());

        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        CommissionDates::updateOrCreate(['type' => 'post'], ['start_date' => $from, 'end_date' => $to]);
        return response()->json(['error' => '0', 'msg' => 'Data posted']);
    }


    public function tsbCommissionImportCsv()
    {
        $req = request();
        if ($req->hasFile('tsb_commissions_csv')) {
            $extension = $req->tsb_commissions_csv->getClientOriginalExtension();
            if ($extension != 'csv') {
                return response()->json(['error' => 1, 'msg' => 'Please upload csv file.']);
            }
            $total_process = 0;
            try {
                $fileName = $req->tsb_commissions_csv->getClientOriginalName();
                $fileName = date('Y-m-d-h-i-s') . "-" . $fileName;
                $req->tsb_commissions_csv->move(public_path('/csv/tsb_commissions_csv/'), $fileName);
                $filename = public_path('/csv/tsb_commissions_csv/') . $fileName;
                $fp = fopen($filename, 'r');
                if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($headers) {
                        while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                            if (!empty($line) && $line[0] != 'TOTALS') {
                                $created_date = date('Y-m-d h:i:s', strtotime($line[0]));
                                $paid_at = date('Y-m-d h:i:s', strtotime($line[1]));
                                $commission = str_replace('$', '', trim($line[3]));
                                $ncv = $this->getWholeNum(trim($line[4]));
                                $nbv = $this->getWholeNum(trim($line[5]));
                                $nqv = $this->getWholeNum(trim($line[6]));
                                $tsb_amount = str_replace('$', '', $line[7]);
                                $referring_user_id = trim($line[12]);
                                $reservation = trim($line[14]);
                                $memo = trim($line[15]);

                                if (empty($referring_user_id))
                                    continue;
                                else if (empty($memo))
                                    continue;
                                else if (empty($created_date))
                                    continue;
                                else if (empty($paid_at))
                                    continue;
                                else if (empty($commission))
                                    continue;
                                else if (empty($tsb_amount))
                                    continue;
                                else if (empty($reservation))
                                    continue;
                                else if (!is_numeric($ncv))
                                    continue;
                                else if (!is_numeric($nbv))
                                    continue;
                                else if (!is_numeric($nqv))
                                    continue;

                                $checkUser = \App\SaveOn::select('*')->where('sor_user_id', $referring_user_id)->first();
                                if (!empty($checkUser)) {
                                    $transactionId = 'SOR#' . $reservation;
                                    $hasOrder = DB::table('orders')->where('trasnactionid', $transactionId)->count();
                                    if ($hasOrder)
                                        continue;
                                    $orderCreateDate = date('Y-m-d h:i:s');
                                    $orderId = \App\Order::addNew(
                                        $checkUser->user_id,
                                        (float)$commission,
                                        (float)$commission,
                                        $nbv,
                                        $nqv,
                                        $ncv,
                                        $transactionId,
                                        null,
                                        null,
                                        null,
                                        $createdDate = $orderCreateDate,
                                        $discountCode = '',
                                        $orderStatus = null,
                                        $order_refund_ref = null,
                                        $orderQC = 0,
                                        $orderAC = 0,
                                        $isTSBOrder = 1
                                    );
                                    if ($orderId) {
                                        $total_process++;
                                        DB::table('orderItem')->insert([
                                            'orderid' => $orderId,
                                            'productid' => \App\Product::ID_TRAVEL_SAVING_BONUS,
                                            'quantity' => 1,
                                            'itemprice' => (float)$commission,
                                            'bv' => $nbv,
                                            'cv' => $ncv,
                                            'qv' => $nqv,
                                            'created_date' => date('Y-m-d', strtotime($orderCreateDate)),
                                            'created_time' => date('h:i:s', strtotime($orderCreateDate)),
                                            'created_dt' => date('Y-m-d h:i:s', strtotime($orderCreateDate)),
                                        ]);
                                        DB::table('tsb_commission')->insert([
                                            'order_id' => $orderId,
                                            'user_id' => $checkUser->user_id,
                                            'dist_id' => $checkUser->user_id,
                                            'amount' => (float)$tsb_amount,
                                            'paid_date' => date('Y-m-d h:i:s', strtotime($paid_at)),
                                            'created_at' => date('Y-m-d h:i:s', strtotime($created_date)),
                                            'status' => 'pending',
                                            'memo' => $memo
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                return response()->json(['error' => 0, 'msg' => "Total $total_process records processed..."]);
            } catch (\Exception $ex) {
                return response()->json(['error' => 1, 'msg' => $ex->getMessage()]);
            }
        }
    }

    private function getWholeNum($val)
    {
        $nval = 0;
        if ($val > 0) {
            $val = explode('.', $val);
            if (!empty($val[1]) && $val[1] > 5) {
                $nval = $val[0] + 1;
            } else {
                if ($val[0] == 0) {
                    $nval = 1;
                } else {
                    $nval = $val[0];
                }
            }
        }
        return $nval;
    }

    public function tsbCommissionPost()
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        $query = DB::table('tsb_commission')
            ->whereDate('paid_date', '>=', $from)
            ->whereDate('paid_date', '<=', $to)
            ->where('status', 'approved');
        if ($query->count() <= 0) {
            return response()->json(['error' => '1', 'msg' => 'Before post TSB Commission please approved the TSB Commission.']);
        }
        $commissions = $query->get();
        $total_success = $total_fail = 0;
        foreach ($commissions as $commission) {
            $transactionId = 1;
            $transactionId = \App\EwalletTransaction::addPurchase($commission->user_id, \App\EwalletTransaction::TYPE_TSB_COMMISSION, $commission->amount, 0, $commission->memo);
            if ($transactionId) {
                DB::table('tsb_commission')->where('id', $commission->id)->update(['status' => 'paid']);
                $total_success++;
            } else {
                DB::table('tsb_commission')->where('id', $commission->id)->update(['status' => 'fail']);
                $total_fail++;
            }
        }
        return response()->json(['error' => '0', 'msg' => $total_success . " Users successfully deposit & " . $total_fail . " Users fail to deposit..."]);
    }

    public function approvedCommission() {
        $d = array();
        $d['approve_date'] = \App\Commission::getSearchApprovedDate();
        $d['trans_date'] = \App\Commission::getSearchTranDate();
        //
        return view('admin.commission.approved_search')->with($d);
    }

    public function searchApprovedCommission() {
        $req = request();
        //
        \App\Commission::setSearchApprovedDate($req->approve_date);
        \App\Commission::setSearchTranDate($req->trans_date);
        //
        return response()->json(['error' => '0', 'url' => url('/approved-commission-detail')]);
    }

    public function approvedCommissionDetail() {
        $approvedDate = \App\Commission::getSearchApprovedDate();
        $transactionDate = \App\Commission::getSearchTranDate();
        //
        $d = array();
        $d['approved_date'] = $approvedDate;
        $d['trans_date'] = $transactionDate;
        return view('admin.commission.approved_detail')->with($d);
    }

    public function getApprovedDetailDataTable() {
        $approvedDate = \App\Commission::getSearchApprovedDate();
        $transactionDate = \App\Commission::getSearchTranDate();

        $q = DB::table('v_approved_commission');
        if (!\utill::isNullOrEmpty($transactionDate)) {
            $q->whereDate('transaction_date', $transactionDate);
        }
        if (!\utill::isNullOrEmpty($approvedDate)) {
            $q->whereDate('processed_date', $approvedDate);
        }
        return DataTables::of($q)->toJson();
    }

    public function approvedCommissionSummary() {
        $approvedDate = \App\Commission::getSearchApprovedDate();
        $transactionDate = \App\Commission::getSearchTranDate();
        //
        $d = array();
        $d['approved_date'] = $approvedDate;
        $d['trans_date'] = $transactionDate;
        return view('admin.commission.approved_summary')->with($d);
    }

    public function getApprovedSummaryDataTable() {
        $approvedDate = \App\Commission::getSearchApprovedDate();
        $transactionDate = \App\Commission::getSearchTranDate();

        $q = DB::table('v_approved_commission');
        $q->selectRaw('distid, username, sum(amount) as amount');
        if (!\utill::isNullOrEmpty($transactionDate)) {
            $q->whereDate('transaction_date', $transactionDate);
        }
        if (!\utill::isNullOrEmpty($approvedDate)) {
            $q->whereDate('processed_date', $approvedDate);
        }
        $q->groupBy('distid', 'username');
        return DataTables::of($q)->toJson();
    }

    private function validateInput() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'from' => 'required|date',
                    'to' => 'required|date',
                        ], [
                    'from.required' => 'From date is required',
                    'to.required' => 'To date is required',
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
            if ($req->from > $req->to) {
                $valid = 0;
                $msg = "Invalid date range";
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function expCommissionSummary($sort_col, $asc_desc, $q = null) {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        $req = request()->all();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Commission Engine - Summary (" . $from . " - " . $to . ").csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            $recs = DB::table('vcommission_user_temp')
                    ->selectRaw('distid, username, sum(amount) as amount')
                    ->whereDate('transaction_date', '>=', $from)
                    ->whereDate('transaction_date', '<=', $to)
                    ->groupBy('distid', 'username')
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        } else {
            $recs = DB::table('vcommission_user_temp')
                    ->selectRaw('distid, username, sum(amount) as amount')
                    ->whereDate('transaction_date', '>=', $from)
                    ->whereDate('transaction_date', '<=', $to)
                    ->groupBy('distid', 'username')
                    ->where(function ($sq) use ($q) {
                        $sq->where('distid', 'ilike', "%" . $q . "%")
                        ->orWhere('username', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        }


        $columns = array('Dist ID', 'Username', 'Amount');

        $callback = function () use ($recs, $columns, $req) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->distid, $rec->username, $rec->amount));
            }
            fputcsv($file, array("", "", $req['total']));
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function expTsbCommissionSummary($sort_col, $asc_desc, $q = null)
    {
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        $req = request()->all();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=TSB Commission Engine - Summary (" . $from . " - " . $to . ").csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        if ($q == null) {
            $recs = DB::table('tsb_commission')
                ->select('users.distid', 'users.username', 'tsb_commission.*')
                ->selectRaw('sum(amount) as amount')
                ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('paid_date', '>=', $from)
                ->whereDate('paid_date', '<=', $to)
                ->groupBy('tsb_commission.id', 'users.distid', 'users.username')
                ->orderBy($sort_col, $asc_desc)
                ->get();

        } else {
            $recs = DB::table('tsb_commission')
                ->select('users.distid', 'users.username', 'tsb_commission.*')
                ->selectRaw('sum(amount) as amount')
                ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($sq) use ($q) {
                    $sq->where('users.distid', 'ilike', "%" . $q . "%")
                        ->orWhere('users.username', 'ilike', "%" . $q . "%");
                })
                ->groupBy('tsb_commission.id', 'users.distid', 'users.username')
                ->orderBy($sort_col, $asc_desc)
                ->get();
        }
        $columns = array('Dist ID', 'Username', 'Amount');
        $callback = function () use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $total = 0;
            foreach ($recs as $rec) {
                fputcsv($file, array($rec->distid, $rec->username, $rec->amount));
                $total = $total + $rec->amount;
            }
            fputcsv($file, array("", "Total", $total));
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function expTsbCommissionDetail($sort_column = 'users.distid', $asc_desc = 'asc', $q = null)
    {
        $req = request()->all();
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=TSB Commission Engine - Detail (" . $from . " - " . $to . ").csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        if ($q == null) {
            if ($from && $to) {
                $recs = DB::table('tsb_commission')
                    ->select('tsb_commission.paid_date', 'users.distid', 'users.username', 'tsb_commission.amount', 'tsb_commission.memo')
                    ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                    ->whereDate('tsb_commission.paid_date', '>=', $from)
                    ->whereDate('tsb_commission.paid_date', '<=', $to)
                    ->whereIn('tsb_commission.status', ['approved', 'pending'])
                    ->orderBy($sort_column, $asc_desc)
                    ->get();
            } else {
                $recs = DB::table('tsb_commission')
                    ->select('tsb_commission.paid_date', 'users.distid', 'users.username', 'tsb_commission.amount', 'tsb_commission.memo')
                    ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                    ->whereIn('tsb_commission.status', ['approved', 'pending'])
                    ->orderBy($sort_column, $asc_desc)
                    ->get();
            }
        } else {
            if ($from && $to) {
                $recs = DB::table('tsb_commission')
                    ->join('users', 'tsb_commission.user_id', '=', 'users.id')
                    ->select('tsb_commission.paid_date', 'users.distid', 'users.username', 'tsb_commission.amount', 'tsb_commission.memo')
                    ->whereDate('tsb_commission.paid_date', '>=', $from)
                    ->whereDate('tsb_commission.paid_date', '<=', $to)
                    ->whereIn('tsb_commission.status', ['approved', 'pending'])
                    ->where(function ($sq) use ($q) {
                        $sq->where('users.distid', 'ilike', "%" . $q . "%")
                            ->orWhere('tsb_commission.memo', 'ilike', "%" . $q . "%")
                            ->orWhere('tsb_commission.amount', 'ilike', "%" . $q . "%")
                            ->orWhere('users.username', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_column, $asc_desc)
                    ->get();
            } else {
                $recs = DB::table('tsb_commission')
                    ->select('tsb_commission.paid_date', 'users.distid', 'users.username', 'tsb_commission.amount', 'tsb_commission.memo')
                    ->whereIn('status', ['approved', 'pending'])
                    ->where(function ($sq) use ($q) {
                        $sq->where('paid_date', 'ilike', "%" . $q . "%")
                            ->orWhere('users.distid', 'ilike', "%" . $q . "%")
                            ->orWhere('tsb_commission.memo', 'ilike', "%" . $q . "%")
                            ->orWhere('tsb_commission.amount', 'ilike', "%" . $q . "%")
                            ->orWhere('users.username', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_column, $asc_desc)
                    ->get();
            }
        }
        $columns = array('Date', 'Dist ID', 'Username', 'Amount', 'Memo');
        $callback = function () use ($recs, $columns, $req) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $total = 0;
            foreach ($recs as $rec) {
                fputcsv($file, array($rec->paid_date, $rec->distid, $rec->username, $rec->amount, $rec->memo));
                $total = $total + $rec->amount;
            }
            fputcsv($file, array("", "", "Total", $total, ""));
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function expCommissionDetail($sort_col = 'distid', $asc_desc = 'asc', $q = null)
    {
        $req = request()->all();
        $from = \App\Commission::getComEngFromDate();
        $to = \App\Commission::getComEngToDate();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Commission Engine - Detail (" . $from . " - " . $to . ").csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            if ($from && $to) {
                $recs = DB::table('vcommission_user_temp')
                    ->whereDate('transaction_date', '>=', $from)
                    ->whereDate('transaction_date', '<=', $to)
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
            } else {
                $recs = DB::table('vcommission_user_temp')
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
            }
        } else {
            if ($from && $to) {
                $recs = DB::table('vcommission_user_temp')
                    ->whereDate('transaction_date', '>=', $from)
                    ->whereDate('transaction_date', '<=', $to)
                    ->where(function ($sq) use ($q) {
                        $sq->where('transaction_date', 'ilike', "%" . $q . "%")
                            ->orWhere('distid', 'ilike', "%" . $q . "%")
                            ->orWhere('memo', 'ilike', "%" . $q . "%")
                            ->orWhere('level', 'ilike', "%" . $q . "%")
                            ->orWhere('amount', 'ilike', "%" . $q . "%")
                            ->orWhere('username', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
            } else {
                $recs = DB::table('vcommission_user_temp')
                    ->where(function ($sq) use ($q) {
                        $sq->where('transaction_date', 'ilike', "%" . $q . "%")
                            ->orWhere('distid', 'ilike', "%" . $q . "%")
                            ->orWhere('memo', 'ilike', "%" . $q . "%")
                            ->orWhere('level', 'ilike', "%" . $q . "%")
                            ->orWhere('amount', 'ilike', "%" . $q . "%")
                            ->orWhere('username', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
            }
        }


        $columns = array('Date', 'Dist ID', 'Username', 'Amount', 'Level', 'Memo');

        $callback = function () use ($recs, $columns, $req) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->transaction_date, $rec->distid, $rec->username, $rec->amount, $rec->level, $rec->memo));
            }
            fputcsv($file, array("", "", "", $req['total'], "", ""));
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function adjustmentsView() {
        return view('admin.commission.adjustments');
    }

    public function adjustments() {
        $req = request();
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }

        $userId = \App\User::getByDistId($req->sponsorid)->id;

        $type = $req->amount > 0 ? \App\EwalletTransaction::ADJUSTMENT_ADD : \App\EwalletTransaction::ADJUSTMENT_DEDUCT;
        $adjustmentId = \App\EwalletTransaction::addPurchase($userId, $type, $req->amount, 0, $req->note);
        UpdateHistory::adjustmentAdd($adjustmentId, $req->sponsorid, $req->amount, $req->note);

        return response()->json(['error' => '0', 'msg' => 'Adjusted']);
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'sponsorid' => 'required',
                    'amount' => 'required|numeric',
                    'note' => 'required',
                        ], [
                    'sponsorid.required' => 'Distributor Id is required',
                    'amount.required' => 'Amount is required',
                    'amount.numeric' => 'Amount must be numeric',
                    'amount.min' => 'Minimum amount allowed is 1',
                    'note.required' => 'Note is required',
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
            /* if ($req->amount <= 0) {
              $valid = 0;
              $msg = "Amount must be greater than 0";
              } */
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function getApprovedCommissionDates() {
        $req = request();
        $q = null;
        if ($req->q) {
            $q = $req->q;
        }
        $recs = \App\Commission::getApprovedDates($q);
        return $recs->toJson();
    }

    public function showImportTsbForm()
    {
        return view('admin.commission.tsb-import');
    }

    private function getImportTsbValidator()
    {
        // validators mimes:csv and mimetypes:text/csv do not work correctly (maybe this version of Laravel?)

        $rules = [
            'csvFile' => 'required|file',
            'date' => 'required|date'
        ];

        $messages = [
            'csvFile.required' => 'You must import a file',
            'csvFile.file' => 'You must import a file',
            'date.required' => 'You must choose a date for the orders that will be added',
            'date.'
        ];

        return Validator::make(request()->all(), $rules, $messages);
    }

    private function getImportTsbErrorsOrNull()
    {
        $validator = $this->getImportTsbValidator();

        if ($validator->fails()) {
            return $validator->errors();
        }

        // file must exist and be a file after this point (validator)
        $csvFile = request()->file('csvFile');

        if (strtolower($csvFile->getClientOriginalExtension()) != 'csv') {
            return ['You must upload a CSV file'];
        }

        return null;
    }

    private function parseImportTsbCsv($filePath)
    {
        $csv = array_map('str_getcsv', file($filePath));

        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        $headers = $csv[0];

        array_shift($csv);

        return array($csv, $headers);
    }


    public function createImportTsbOrders($sheetRows, $date)
    {
        $failedDistIds = [];

        foreach ($sheetRows as $sheetRow) {
            $distId = trim($sheetRow['Contract Number']);
            $amount = str_replace('$', '', trim($sheetRow['Amount']));
            $cv = str_replace('$', '', trim($sheetRow['CV']));

            $success = $this->createImportTsbOrder($distId, $amount, $cv, $date);

            if (!$success) {
                $failedDistIds[] = $distId;
            }
        }

        return $failedDistIds;
    }

    private function createImportTsbOrder($distId, $amount, $cv, $date)
    {
        $cv = ceil($cv);
        $qv = ceil($amount);

        $user = User::getByDistId($distId);

        if (!$user) {
            return false;
        }

        $order = new Order();

        $order->fill([
            'userid' => $user->id,
            'ordersubtotal' => (float)$amount,
            'ordertotal' => (float)$amount,
            'ordercv' => $cv,
            'orderbv' => 0,
            'orderqv' => $qv,
            'payment_methods_id' => null,
            'shipping_address_id' => null,
            'statuscode' => 1,
            'processed' => false,
            'created_date' => date('Y-m-d', strtotime($date)),
            'created_time' => '12:00:00',
            'created_dt' => date('Y-m-d 12:00:00', strtotime($date)),
        ]);

        $order->save();

        $order->orderItems()->create([
            'productid' => Product::ID_TRAVEL_SAVING_BONUS,
            'quantity' => 1,
            'itemprice' => (float)$amount,
            'bv' => 0,
            'cv' => $cv,
            'qv' => $qv,
            'created_date' => date('Y-m-d', strtotime($date)),
            'created_time' => '12:00:00',
            'created_dt' => date('Y-m-d 12:00:00', strtotime($date)),
        ]);

        return true;
    }


    private function checkForMissingHeadersImportTsb($headers)
    {
        $errorMessage = 'Header [%s] is not present in sheet';

        $expectedHeaders = [
            'Contract Number',
            'Amount',
            'CV'
        ];

        $errors = [];

        foreach ($expectedHeaders as $header) {
            if (!in_array($header, $headers)) {
                $errors[] = sprintf($errorMessage, $header);
            }
        }

        return $errors;
    }

    public function importTsbFile()
    {
        $errors = $this->getImportTsbErrorsOrNull();

        if ($errors) {
            return view('admin.commission.tsb-import', ['errors' => $errors]);
        }

        $file = request()->file('csvFile');
        $date = request()->post('date');

        list($sheetRows, $headers) = $this->parseImportTsbCsv($file);

        $errors = $this->checkForMissingHeadersImportTsb($headers);

        if ($errors) {
            return view('admin.commission.tsb-import', ['errors' => $errors]);
        }

        $failedDistIds = $this->createImportTsbOrders($sheetRows, $date);

        $numFailed = sizeof($failedDistIds);
        $numTotal = sizeof($sheetRows);
        $numSuccessful = $numTotal - $numFailed;

        if (!empty($failedDistIds)) {
            $errors = ['Dist IDs not found: ' . implode(", ", $failedDistIds)];
            return view('admin.commission.tsb-import', [
                'errors' => $errors,
                'numTotal' => $numTotal,
                'numSuccessful' => $numSuccessful,
                'success' => $numTotal == $numSuccessful
            ]);
        }

        return view('admin.commission.tsb-import', [
            'numTotal' => $numTotal,
            'numSuccessful' => $numSuccessful,
            'success' => $numTotal == $numSuccessful
        ]);
    }

    public function showImportVibeForm()
    {
        return view('admin.commission.vibe-import');
    }

    private function getImportVibeValidator()
    {
        // validators mimes:csv and mimetypes:text/csv do not work correctly (maybe this version of Laravel?)

        $rules = [
            'csvFile' => 'required|file',
            'date' => 'required|date'
        ];

        $messages = [
            'csvFile.required' => 'You must import a file',
            'csvFile.file' => 'You must import a file',
            'date.required' => 'You must choose a date for the orders that will be added'
        ];

        return Validator::make(request()->all(), $rules, $messages);
    }

    private function getImportVibeErrorsOrNull()
    {
        $validator = $this->getImportVibeValidator();

        if ($validator->fails()) {
            return $validator->errors();
        }

        // file must exist and be a file after this point (validator)
        $csvFile = request()->file('csvFile');

        if (strtolower($csvFile->getClientOriginalExtension()) != 'csv') {
            return ['You must upload a CSV file'];
        }

        return null;
    }

    private function parseImportVibeCsv($filePath)
    {
        $csv = array_map('str_getcsv', file($filePath));

        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        $headers = $csv[0];

        array_shift($csv);

        return array($csv, $headers);
    }


    public function createImportVibeOrders($sheetRows, $date)
    {
        $failedDistIds = [];

        foreach ($sheetRows as $sheetRow) {
            $distId = trim($sheetRow['TSA #']);
            $amount = floatval(substr($sheetRow['Amount'], 1));
            $cv = floatval($sheetRow['CV']);

            $success = $this->createImportVibeOrder($distId, $amount, $cv, $date);

            if (!$success) {
                $failedDistIds[] = $distId;
            }
        }

        return $failedDistIds;
    }

    private function createImportVibeOrder($distId, $amount, $cv, $date)
    {
        $user = User::getByDistId($distId);

        if (!$user) {
            return false;
        }

        if ($cv > 0 && $cv < 1) {
            $cv = 1;
        } else {
            $cv = ceil($cv);
        }

        $order = new Order();

        $order->fill([
            'userid' => $user->id,
            'ordersubtotal' => (float)$amount,
            'ordertotal' => (float)$amount,
            'ordercv' => $cv,
            'orderbv' => 0,
            'orderqv' => 0,
            'payment_methods_id' => null,
            'shipping_address_id' => null,
            'statuscode' => 1,
            'processed' => false,
            'created_date' => date('Y-m-d', strtotime($date)),
            'created_time' => '12:00:00',
            'created_dt' => date('Y-m-d 12:00:00', strtotime($date)),
        ]);

        $order->save();

        $order->orderItems()->create([
            'productid' => Product::ID_VIBE_COMMISSION,
            'quantity' => 1,
            'itemprice' => (float)$amount,
            'bv' => 0,
            'cv' => $cv,
            'qv' => 0,
            'created_date' => date('Y-m-d', strtotime($date)),
            'created_time' => '12:00:00',
            'created_dt' => date('Y-m-d 12:00:00', strtotime($date)),
        ]);

        return true;
    }


    private function checkForMissingHeadersImportVibe($headers)
    {
        $errorMessage = 'Header [%s] is not present in sheet';

        $expectedHeaders = [
            'date',
            'durationDistanceAmount',
            'TSA #',
            'Amount',
            'CV'
        ];

        $errors = [];

        foreach ($expectedHeaders as $header) {
            if (!in_array($header, $headers)) {
                $errors[] = sprintf($errorMessage, $header);
            }
        }

        return $errors;
    }

    public function importVibeFile()
    {
        $errors = $this->getImportVibeErrorsOrNull();

        if ($errors) {
            return view('admin.commission.vibe-import', ['errors' => $errors]);
        }

        $file = request()->file('csvFile');
        $date = request()->post('date');

        list($sheetRows, $headers) = $this->parseImportVibeCsv($file);

        $errors = $this->checkForMissingHeadersImportVibe($headers);

        if ($errors) {
            return view('admin.commission.vibe-import', ['errors' => $errors]);
        }

        $failedDistIds = $this->createImportVibeOrders($sheetRows, $date);

        $numFailed = sizeof($failedDistIds);
        $numTotal = sizeof($sheetRows);
        $numSuccessful = $numTotal - $numFailed;

        if (!empty($failedDistIds)) {
            $errors = ['Dist IDs not found: ' . implode(", ", $failedDistIds)];
            return view('admin.commission.vibe-import', [
                'errors' => $errors,
                'numTotal' => $numTotal,
                'numSuccessful' => $numSuccessful,
                'success' => $numTotal == $numSuccessful
            ]);
        }

        return view('admin.commission.vibe-import', [
            'numTotal' => $numTotal,
            'numSuccessful' => $numSuccessful,
            'success' => $numTotal == $numSuccessful
        ]);
    }
}
