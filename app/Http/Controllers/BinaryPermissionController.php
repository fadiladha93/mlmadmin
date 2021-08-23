<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BinaryPermissionController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin_superAdmin');
    }

    public function index() {
        $d['rec'] = \App\BinaryPermission::find(1);
        return view('admin.binary_permission.index')->with($d);
    }

    public function saveRec() {
        $req = request();
        $rec = \App\BinaryPermission::find(1);
        $rec->permit_to = $req->permit_to;
        $rec->mode = $req->mode;
        $rec->save();
        return response()->json(['error' => 0, 'msg' => 'Saved']);
    }

}
