<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
use Auth;
use Validator;

class BTreeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('affiliate.btree.index');
    }
}
