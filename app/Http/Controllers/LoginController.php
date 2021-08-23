<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use DB;
use Hash;
use session;
use Validator;

class LoginController extends Controller {

    public function frmAffliateLogin() {
        if (Auth::check())
            return redirect('/');
        else
            return view('affiliate.user.login');
    }

    public function frmAdminLogin() {
        if (Auth::check())
            return redirect('/');
        else
            return view('admin.user.login');
    }

    public function affliateLogin() {
        $req = request();
        $username = $req->username;
        $password = $req->password;
        $rememberMe = $req->remember == "on" ? true : false;
        return $this->login($username, $password, \App\UserType::TYPE_DISTRIBUTOR, $rememberMe);
    }

    public function adminLogin() {
        $req = request();
        $email = $req->email;
        $password = $req->password;
        $rememberMe = $req->remember == "on" ? true : false;
        return $this->login($email, $password, \App\UserType::TYPE_ADMIN, $rememberMe);
    }

    public function forgotPassword() {
        return view('affiliate.user.dlg_forgot_password');
    }

    public function sendPasswordResettingEmail() {
        
        $req = request();
        $q = $req->distid;
        if (\utill::isNullOrEmpty($q))
            return response()->json(['error' => '1', 'msg' => 'Please enter your distributor ID or Username']);
        else {
            //$user = \App\User::getByDistId($distid);
            $user = \App\User::getByDistIdOrUsername($q);
            if (empty($user)) {
                return response()->json(['error' => '1', 'msg' => 'Invalid distributor ID or Username']);
            } else {
                if (\utill::isNullOrEmpty($user->email)) {
                    return response()->json(['error' => '1', 'msg' => 'Email not found,<br/>please contact us at mail.countdown4freedom.com']);
                } else {
                    $token = \App\PasswordResetTokens::createNew($user->email);
                    $resettingUrl = url('/reset-password/' . $token);
                    \MyMail::sendResettingPassword($user->email, $user->firstname . " " . $user->lastname, $resettingUrl);
                    return response()->json(['error' => '0', 'msg' => 'Please check your inbox for password resetting email']);
                }
            }
        }
    }

    public function frmResetPassword($token) {
        $email = \App\PasswordResetTokens::getEmailByToken($token);
        if ($email == null) {
            $msg = array('error' => 1, 'msg' => 'Invalid password resetting URL<br/>or expired URL.');
        } else {
            $msg = array('error' => 0, 'type' => 'reset-password', 'token' => $token);
        }
        return redirect('/login')->withErrors($msg);
    }

    public function resetPassword() {
        $req = request();
        $token = $req->token;
        $email = \App\PasswordResetTokens::getEmailByToken($token);
        if ($email == null)
            return response()->json(['error' => 1, 'msg' => "Invalid password resetting token"]);
        //
        $vali = $this->validateNewPassword();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            DB::table('users')
                    ->where('email', $email)
                    ->update([
                        'password' => password_hash($req->pass_1, PASSWORD_BCRYPT),
                        'default_password' => '',
                        'email_verified' => 1
            ]);
            return response()->json(['error' => '0', 'msg' => 'Password changed successfully']);
        }
    }

    private function attemptVibeLogin($username, $password, $userType, $rememberMe = false)
    {
        // 51 is the product ID for Vibe import users
        $user = User::where(['username' => $username, 'password' => md5($password), 'current_product_id' => 51])->first();

        if ($user) {
            Auth::login($user, $rememberMe);
            return true;
        }

        return false;
    }

    private function login($username, $password, $userType, $rememberMe = false) {
        if ($userType == \App\UserType::TYPE_DISTRIBUTOR) {
            if (!Auth::attempt(array('username' => strtolower($username), 'password' => $password, 'usertype' => $userType), $rememberMe)) {
                if (!Auth::attempt(array('distid' => $username, 'password' => $password, 'usertype' => $userType), $rememberMe)) {

                    $result = $this->attemptVibeLogin($username, $password, $userType, $rememberMe);

                    if (!$result) {
                        return response()->json(['error' => '1', 'msg' => "Invalid username or distributor ID or password"]);
                    }
                }
            }
        } else if ($userType == \App\UserType::TYPE_ADMIN) {
            if (!Auth::attempt(array('email' => strtolower($username), 'password' => $password, 'usertype' => $userType), $rememberMe)) {
                return response()->json(['error' => '1', 'msg' => "Invalid email or password"]);
            }
        }

        if ($userType == \App\UserType::TYPE_DISTRIBUTOR) {
            if (Auth::user()->account_status == \App\User::ACC_STATUS_PENDING) {
                Auth::logout();
                return response()->json(['error' => '1', 'msg' => "This account has restricted access.  Please contact customer service at <a href='mailto:support@ibuumerang.com'>support@ibuumerang.com</a>"]);
//                return response()->json(['error' => '1', 'msg' => "Your account is not approved yet.<br/>We will send an email once we approved your account"]);
            }
            if (Auth::user()->account_status == \App\User::ACC_STATUS_SUSPENDED) {
//                session()->put(['suspended_user_id' => Auth::user()->id]);
//                Auth::logout();
//                return response()->json(['error' => '1', 'msg' => "Your account is suspended.<br/>Please contact us"]);
//                $v = (string)view('affiliate.user.account.suspend_account_reactivate');
//                return response()->json(['error' => 0, 'v' => $v]);
//                return response()->json(['error' => '1', 'msg' => "Your account is suspended. Please contact customer service at <a href='mailto:support@ibuumerang.com'>support@ibuumerang.com</a>"]);
            }
            if (Auth::user()->account_status == \App\User::ACC_STATUS_TERMINATED) {
                \App\Helper::deActivateIdecideUser(Auth::user()->id);
                \App\Helper::deActivateSaveOnUser(Auth::user()->id, Auth::user()->current_product_id, Auth::user()->distid, \App\SaveOn::USER_TERMINATED_NOTE);
                Auth::logout();
                return response()->json(['error' => '1', 'msg' => "This account has restricted access.  Please contact customer service at <a href='mailto:support@ibuumerang.com'>support@ibuumerang.com</a>"]);
            }
        }
        //
        if ($userType == \App\UserType::TYPE_ADMIN && Auth::user()->secondary_auth_enabled == 1) {
            // send token via sms
            $res = \App\TwilioAuthy::sendToken(Auth::user()->email);
            //
            $url = '2fa/' . base64_encode(Auth::user()->email);
            Auth::logout();
            return response()->json(['error' => '0', 'url' => URL('/' . $url)]);
        }
        return response()->json(['error' => '0', 'url' => URL('/')]);
    }

    public function logout() {
        session(['login_from_admin' => ""]);
        $redirecTo = url('/login');
        if (\App\User::isAdmin())
            $redirecTo = url('/admin');
        Auth::logout();
        return redirect($redirecTo);
    }

    public function loginToAdminPanel() {
        if (!\utill::isNullOrEmpty(session('login_from_admin'))) {
            Auth::loginUsingId(session('login_from_admin'));
            session(['login_from_admin' => ""]);
            return redirect('/');
        } else {
            return redirect('/');
        }
    }

    private function validateNewPassword() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'pass_1' => 'required|min:6',
                    'pass_2' => 'same:pass_1',
                        ], [
                    'pass_1.required' => 'New password is required',
                    'pass_1.min' => 'New password must be at least 6 charactors',
                    'pass_2.same' => 'Passwords do not match',
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
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function login_iGo4Less() {
        $req = request();
        $username = $req->username;
        $password = $req->password;
        //
        $loginOkey = false;
        $user = DB::table('users')
                ->where('username', $username)
                ->first();
        if (!empty($user)) {
            if (Hash::check($password, $user->password)) {
                $loginOkey = true;
            }
        }

        if ($loginOkey) {

            $currentProductId = $user->current_product_id;
            if ($currentProductId == 13)
                $currentProductId = 4;
            if ($currentProductId == 14)
                $currentProductId = 3;

            $endPoint = config('api_endpoints.SORGetLoginToken');
            $saveOnAPI = new \SOR($currentProductId);
            $postData = array(
                "ContractNumber" => $user->distid
            );
            try {
                $responseBody = $saveOnAPI->_post($endPoint, $postData, false);
            } catch (\Exception $exception) {
                $responseBody = (string) $exception->getResponse()->getBody(true);
            }
            if (strpos($responseBody, "LoginToken") !== false) {
                $token = str_replace("LoginToken:", "", $responseBody);
                $token = str_replace('"', '', $token);
                $url = 'https://members.igo4less.com/vacationclub/logincheck.aspx?Token=' . $token;
                return response()->json(['status' => 'success', 'url' => $url]);
            } else {
                return response()->json(['status' => 'error', 'msg' => $responseBody]);
            }
        } else {
            return response()->json(['status' => 'error', 'msg' => 'Invalid username / password']);
        }
    }

    public function twoFAForm($base64email) {
        $d['email'] = base64_decode($base64email);
        return view('admin.user.2faLogin')->with($d);
    }

    public function twoFALogin() {
        $req = request();
        $email = $req->email;
        $token = $req->token;
        //
        $vali = $this->validate2FALogin();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            $res = \App\TwilioAuthy::verifyToken($email, $token);
            if ($res['verified'] == 1) {
                Auth::loginUsingId($res['user_id']);
                return response()->json(['error' => '0', 'url' => url('/')]);
            } else {
                return response()->json(['error' => '1', 'msg' => $res['msg']]);
            }
        }
    }

    private function validate2FALogin() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'token' => 'required',
                        ], [
                    'token.required' => 'Access token is required',
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
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

}
