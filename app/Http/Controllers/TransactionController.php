<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
use Auth;

class TransactionController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'getTransactionByIntern',
        ]]);
        $this->middleware('auth');
    }

    public function getAdminReportData() {
        $query = DB::table('transaction_detail');
        return DataTables::of($query)->toJson();
    }

    public function exportAdminReportData($sort_col, $asc_desc, $q = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Admin_report_sales.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            $recs = DB::table('transaction_detail')
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        } else {
            $recs = DB::table('transaction_detail')
                    ->where(function($sq) use($q) {
                        $sq->where('customer', 'like', "%" . $q . "%")
                        ->orWhere('sponsor_detail', 'like', "%" . $q . "%")
                        ->orWhere('total', 'like', "%" . $q . "%")
                        ->orWhere('product_info', 'like', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        }

        $columns = array('Customer', 'Product', 'Total', 'Sponsor Detail');

        $callback = function() use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->customer, $rec->product_info, $rec->total, $rec->sponsor_detail));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function viewDetail($recId) {
        $d = array();
        $tran = \App\Transaction::getRecById($recId);
        $cus = \App\Customer::getRecById($tran->customer_id);
        $sponsor = \App\User::getBasicInfo($cus->referrer_id);
        $d['tran'] = $tran;
        $d['sponsor'] = $sponsor;
        $d['customer'] = $cus;
        return view('admin.transaction.detail')->with($d);
    }

    public function getTransactionByIntern() {
        $query = DB::table('transaction_with_user')
                ->where('user_id', Auth::user()->id);
        return DataTables::of($query)->toJson();
    }

}
