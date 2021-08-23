<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;

class EwalletCSVController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin');
    }

    public function trasferedList() {
        return view('admin.ewallet_csv.transfered');
    }

    public function getTransferedDataTable() {
        $query = DB::table('v_ewallet_csv');
        return DataTables::of($query)->toJson();
    }

    public function downloadCSV($recId) {
        return response()->download(storage_path("payap_csv/" . $recId . ".csv"));
    }

}
