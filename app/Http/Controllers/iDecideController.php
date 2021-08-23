<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Validator;

class iDecideController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'createForTV',
                'resetPassword',
            'resetEmail',
            'createNewAccountByUser'
        ]]);
        $this->middleware('auth.affiliate');
    }

    public function createNewAccountByUser()
    {
        $hasAgree = DB::table('product_terms_agreement')->select('*')->where('user_id', Auth::user()->id)->first();
        if (empty($hasAgree)) {
            \App\ProductTermsAgreement::addAgreement('idecide', Auth::user()->id);
        } else if ($hasAgree->agree_idecide != 1) {
            DB::table('product_terms_agreement')->where('user_id', Auth::user()->id)->update([
                'agree_idecide' => 1,
                'agreed_idecide_at' => date('Y-m-d h:i:s'),
            ]);
        }
        $idecide = \App\IDecide::where('user_id', Auth::user()->id)->first();
        if (!empty($idecide)) {
            $response = \App\IDecide::SSOLogin($idecide);
            return response()->json($response);
        }
        $response = \App\IDecide::createAccount(Auth::user()->id, 'IDECIDE - createNewAccount');
        if ($response['error'] == 0) {
            $idecideUserRec = DB::table('idecide_users')
                ->where('user_id', Auth::user()->id)
                ->first();
            $response = \App\IDecide::SSOLogin($idecideUserRec);
            return response()->json($response);
        } else {
            return response()->json($response);
        }
    }

    public function createNewAccount() {
        if (\App\AdminPermission::fn_create_idecide()) {
            $req = request();
            $userId = $req->user_id;
            return $this->createAccount($userId, 'IDECIDE - createNewAccount');
        } else {
            return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
        }
    }

    public function createForTV() {
        return $this->createAccount(Auth::user()->id, 'IDECIDE - createForTV');
    }

    private function createAccount($userId, $logInfo) {
        $response = \App\IDecide::createAccount($userId, $logInfo);
        return response()->json($response);
    }

    public function toggleStatus() {
        $req = request();
        $userId = $req->user_id;
        $idecideRec = \App\IDecide::getIDecideUserInfo($userId);
        if (!empty($idecideRec)) {
            $status = $idecideRec->status;
            if ($status == 1) {
                $res = \App\Helper::deActivateIdecideUser($userId);
                if ($res['error'] == 1)
                    return response()->json(['error' => 1, 'msg' => $res['msg']]);
                else
                    return response()->json(['error' => 0, 'url' => 'reload']);
            } else {
                $res = \App\Helper::reActivateIdecideUser($userId);
                if ($res['error'] == 1)
                    return response()->json(['error' => 1, 'msg' => $res['msg']]);
                else
                    return response()->json(['error' => 0, 'url' => 'reload']);
            }
        }
        return response()->json(['error' => 1, 'msg' => 'iDecide account not found']);
    }

    public function resetPassword() {
        $req = request();
        $userId = Auth::user()->id;
        $newPassword = $req->idecide_new_pass;
        if (\utill::isNullOrEmpty($newPassword)) {
            return response()->json(['error' => 1, 'msg' => "Please enter new iDecide password"]);
        }
        //
        $idecideUserRec = DB::table('idecide_users')
                ->where('user_id', $userId)
                ->first();
        if (empty($idecideUserRec)) {
            return response()->json(['error' => 1, 'msg' => "Idecide account not found"]);
        }
        //
        if ($idecideUserRec->generated_integration_id > 0) {
            $responseBody = \App\IDecide::updateUserPassword($idecideUserRec->generated_integration_id, $newPassword);
        } else {
            $responseBody = \App\IDecide::updateUserPassword($userId, $newPassword);
        }
        //
        $response = $responseBody['response'];
        $request = $responseBody['request'];
        if (!empty($response->success) && $response->success == 1) {
            //password reset success
            \App\IDecide::where('user_id', $userId)->update(['password' => $newPassword]);
            return response()->json(['error' => 0, 'msg' => 'iDecide password changed']);
        } else {
            //password reset failure
            $errors = implode('<br>', $response->errors);
            return response()->json(['error' => 1, 'msg' => $errors]);
        }
    }

    public function resetEmail() {
        $req = request();
        //
        $vali = $this->validateResetEmail();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }

        $userId = Auth::user()->id;
        $newEmail = $req->idecide_email;
        //
        $idecideUserRec = DB::table('idecide_users')
                ->where('user_id', $userId)
                ->first();
        if (empty($idecideUserRec)) {
            return response()->json(['error' => 1, 'msg' => "Idecide account not found"]);
        }
        //
        if ($idecideUserRec->generated_integration_id > 0) {
            $responseBody = \App\IDecide::updateUserEmailAddress($idecideUserRec->generated_integration_id, $newEmail);
        } else {
            $responseBody = \App\IDecide::updateUserEmailAddress($userId, $newEmail);
        }
        //
        $response = $responseBody['response'];
        $request = $responseBody['request'];
        if (!empty($response->success) && $response->success == 1) {
            return response()->json(['error' => 0, 'msg' => 'iDecide email changed']);
        } else {
            //password reset failure
            $errors = implode('<br>', $response->errors);
            return response()->json(['error' => 1, 'msg' => $errors]);
        }
    }

    private function validateResetEmail() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'idecide_email' => 'required|email',
                        ], [
                    'idecide_email.required' => 'Email is required',
                    'idecide_email.email' => 'Invalid email format',
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

//    public function syncIdecide() {
//        set_time_limit(0);
//        $recs = \App\IDecide::where('api_log', 0)
//                ->get();
//        foreach ($recs as $rec) {
//            $userRec = DB::table('users')
//                    ->select('username', 'firstname', 'lastname')
//                    ->where('id', $rec->user_id)
//                    ->first();
//            if (!empty($userRec)) {
//                //echo $userRec->email . "<br/>";
//                //$res = \App\IDecide::updateUserEmailAddress($rec->user_id, $userRec->email);
//                //print_r($res);
//                echo $userRec->username . "<br/>";
//                //$res = \App\IDecide::updateBusinessNumber($rec->user_id, $userRec->username);
//                $res = \App\IDecide::updateIDecideUser($rec->user_id, $userRec->username, $userRec->firstname, $userRec->lastname);
//                print_r($res);
//                echo "<br/><br/>";
//            }
//        }
//    }
//
//    public function syncIdecide() {
//        set_time_limit(0);
//        foreach (glob(storage_path('/idecide/*.csv')) as $filename) {
//            $file = fopen($filename, 'r');
//            $row = 0;
//            while (($line = fgetcsv($file)) !== FALSE) {
//                if ($row > 0) {
//                    $this->sync($line);
//                }
//                $row++;
//            }
//            fclose($file);
//            //unlink($filename);
//        }
//        dd('done');
//    }

    private function sync($row) {
        $idecideUserId = $row[0];
        $integrationId = $row[1];
        $businessNumber = $row[2];
        $email = $row[7];
        //
        if (\utill::isNullOrEmpty($businessNumber) && !\utill::isNullOrEmpty($integrationId)) {
            $userRec = DB::table('users')
                    ->where('id', $integrationId)
                    ->first();
            if (!empty($userRec)) {
                if (!\utill::isNullOrEmpty($userRec->username)) {
                    echo "updating: " . $integrationId . " - " . $userRec->username . "<br/>";
                    //\App\IDecide::updateBusinessNumber($integrationId, $userRec->username);
                    \App\IDecide::updateIDecideUser($integrationId, $userRec->username, $userRec->firstname, $userRec->lastname);
                }
            }
        }
    }

//
//    private function sync($row) {
//        $idecideUserId = $row[0];
//        $integrationId = $row[1];
//        $businessNumber = $row[2];
//        $email = $row[7];
//        //
//        if (!\utill::isNullOrEmpty($idecideUserId) && !\utill::isNullOrEmpty($integrationId)) {
//            $idecideUser = \App\IDecide::where('user_id', $integrationId)->first();
//            if (empty($idecideUser)) {
//                echo "insert into idecide: " . $idecideUserId . "<br/>";
//                // insert into idecide
//                $r = new \App\IDecide();
//                $r->user_id = $integrationId;
//                $r->idecide_user_id = $idecideUserId;
//                $r->api_log = 0;
//                $r->save();
//                //
//                $userRec = DB::table('users')
//                        ->where('id', $integrationId)
//                        ->first();
//                if (!empty($userRec)) {
//                    // update email at idecide
//                    if (!\utill::isNullOrEmpty($email) && $userRec->email != $email) {
//                        \App\IDecide::updateUserEmailAddress($integrationId, $email);
//                    }
//                    // update business at idecide
//                    if (!\utill::isNullOrEmpty($businessNumber) && $userRec->username != $businessNumber) {
//                        \App\IDecide::updateBusinessNumber($integrationId, $businessNumber);
//                    }
//                }
//            } else {
//                echo "update idecide user id: " . $idecideUserId . "<br/>";
//                // update idecide user id
//                $idecideUser->idecide_user_id = $idecideUserId;
//                $idecideUser->save();
//            }
//        }
//    }
}
