<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;

class ApiRequestController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin_superAdmin');
    }

    public function index() {
        $d = array();
        return view('admin.api_requests.index')->with($d);
    }

    public function getDataTable() {
        $query = DB::table('api_requests');
        return DataTables::of($query)->toJson();
    }

}
