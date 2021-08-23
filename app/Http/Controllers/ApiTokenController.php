<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin_superAdmin');
    }

    public function index() {
        $d = array();
        $d['recs'] = \App\ApiToken::getAll();
        return view('admin.api_token.index')->with($d);
    }

    public function generateNewToken() {
        \App\ApiToken::generateNewToken();
        return redirect('/api-token');
    }

    public function toggleActive($recId) {
        \App\ApiToken::toggleActive($recId);
        return redirect('/api-token');
    }

}
