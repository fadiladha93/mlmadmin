<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TwilioAuthyController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin');
        //
        $this->middleware(function ($request, $next) {
            if (!(\App\User::admin_super_admin() || 
                    \App\User::admin_super_exec() ||
                    \App\User::admin_cs_exec() ||
                    \App\User::admin_cs_manager()
                    )) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect('/');
                }
            }
            return $next($request);
        });
    }

    public function toggle() {
        $req = request();
        $userId = $req->user_id;
        //
        $user = \App\User::find($userId);
        if (empty($user))
            return response()->json(['error' => '1', 'msg' => 'User not found']);
        else {
            if ($user->secondary_auth_enabled == 1) {
                $user->secondary_auth_enabled = 0;
            } else {
                $res = \App\TwilioAuthy::register($user->email, $user->mobilenumber, $user->phone_country_code);
                if ($res['error'] == 0) {
                    $user->secondary_auth_enabled = 1;
                    $user->authy_id = $res['authy_id'];
                } else {
                    return response()->json(['error' => '1', 'msg' => 'Error from Authy:<br/>' . $res['msg']]);
                }
            }
            $user->save();
        }
        //
        return response()->json(['error' => '0', 'url' => 'reload']);
    }

}
