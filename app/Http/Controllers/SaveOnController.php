<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class SaveOnController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
            'createNewAccountByUser'
        ]]);
        $this->middleware('auth.affiliate');
    }

    public function createNewAccountByUser()
    {
        $hasAgree = DB::table('product_terms_agreement')->select('*')->where('user_id', Auth::user()->id)->first();
        if (empty($hasAgree)) {
            \App\ProductTermsAgreement::addAgreement('sor', Auth::user()->id);
        } else if ($hasAgree->agree_sor != 1) {
            DB::table('product_terms_agreement')->where('user_id', Auth::user()->id)->update([
                'agree_sor' => 1,
                'agreed_sor_at' => date('Y-m-d h:i:s'),
            ]);
        }
        //check existing account
        $sor = \App\SaveOn::where('user_id', Auth::user()->id)->first();
        $product = \App\Product::getProduct(Auth::user()->current_product_id);
        if (!empty($sor)) {
            if ($sor->status == \App\SaveOn::DEACTIVE && Auth::user()->account_status == \App\User::ACC_STATUS_APPROVED) {
                //enable sor
                $disabledUserRsponse = \App\SaveOn::enableUser($product->id, Auth::user()->distid, \App\SaveOn::USER_ACCOUNT_REST);
                if ($disabledUserRsponse['status'] == 'success' && $disabledUserRsponse['enabled'] == 'true') {
                    \App\SaveOn::where('sor_user_id', $sor->sor_user_id)->update(['status' => \App\SaveOn::ACTIVE]);
                }
            }
            $response = \App\SaveOn::SSOLogin($product->id, Auth::user()->distid);
            return response()->json($response);
        }
        $userAddress = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_REGISTRATION);
        if (empty($userAddress)) {
            $userAddress = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_BILLING);
            if (empty($userAddress)) {
                return response()->json(['error' => '1', 'msg' => 'Address information is missing']);
            }
        }
        $sorRes = \App\SaveOn::SORCreateUser(Auth::user()->id, $product->id, $userAddress);
        $lastId = \App\Helper::logApiRequests(Auth::user()->id, 'SOR - createNewSORAccount', config('api_endpoints.SORCreateUser'), $sorRes['request']);
        \App\Helper::logApiResponse($lastId->id, json_encode($sorRes['response']));
        $sorResponse = $sorRes['response'];
        if (isset($sorResponse->Account) && isset($sorResponse->Account->UserId)) {
            $request = $sorRes['request'];
            \App\SaveOn::insert(['api_log' => $lastId->id, 'user_id' => Auth::user()->id, 'product_id' => $product->id, 'sor_user_id' => $sorResponse->Account->UserId, 'sor_password' => $request['Password'], 'status' => 1]);
            $response = \App\SaveOn::SSOLogin($product->id, Auth::user()->distid);
            return response()->json($response);
        } else {
            return response()->json(['error' => '1', 'msg' => 'Error when create new SOR<br/>Error: ' . $sorResponse->Message]);
        }
    }


    public function transfer() {
        if (!\App\AdminPermission::fn_create_sor()) {
            return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
        }
        $req = request();
        $vali = $this->validateTransfer();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        //
        $userId = $req->user_id;
        $transferToProductId = $req->sor_transfer_to;
        //
        $sorUserId = \App\SaveOn::getSORUserId($userId);
        if ($sorUserId == null) {
            return $this->createNewSORAccount($userId, $transferToProductId);
        } else {
            return $this->transferUser($userId, $sorUserId, $transferToProductId);
        }
    }

    private function createNewSORAccount($userId, $transferToProductId) {
        $product = \App\Product::getProduct($transferToProductId);
        $userAddress = \App\Address::getRec($userId, \App\Address::TYPE_REGISTRATION);
        if (empty($userAddress)) {
            $userAddress = \App\Address::getRec($userId, \App\Address::TYPE_BILLING);
            if (empty($userAddress)) {
                return response()->json(['error' => '1', 'msg' => 'Address information is missing']);
            }
        }
        //
        $sorRes = \App\SaveOn::SORCreateUser($userId, $transferToProductId, $userAddress);
        $lastId = \App\Helper::logApiRequests($userId, 'SOR - createNewSORAccount', config('api_endpoints.SORCreateUser'), $sorRes['request']);
        \App\Helper::logApiResponse($lastId->id, json_encode($sorRes['response']));
        $sorResponse = $sorRes['response'];
        if (isset($sorResponse->Account) && isset($sorResponse->Account->UserId)) {
            $request = $sorRes['request'];
            \App\SaveOn::insert(['api_log' => $lastId->id, 'user_id' => $userId, 'product_id' => $transferToProductId, 'sor_user_id' => $sorResponse->Account->UserId, 'sor_password' => $request['Password'], 'status' => 1]);
            // update boomerangs, on success of SOR
            \App\BoomerangInv::addToInventory($userId, $product->num_boomerangs);
            //
            return response()->json(['error' => '0', 'url' => 'reload']);
        } else {
            return response()->json(['error' => '1', 'msg' => 'Error when create new SOR<br/>Error: ' . $sorResponse->Message]);
        }
    }

    private function transferUser($userId, $sorUserId, $transferToProductId) {
        $sorRes = \App\SaveOn::transfer($userId, $sorUserId, $transferToProductId);
        $sorResponse = $sorRes['response'];
        if (!empty($sorResponse->status_code) && $sorResponse->status_code == 200) {
            \App\SaveOn::where('user_id', $userId)->update(['product_id' => $transferToProductId]);
            return response()->json(['error' => 0, 'url' => 'reload']);
        } else {
            return response()->json(['error' => 1, 'msg' => "Error when transfer.<br/>" . $sorResponse]);
        }
    }

    private function validateTransfer() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'sor_transfer_to' => 'required',
                        ], [
                    'sor_transfer_to.required' => 'Please select a package',
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

    public function toggleStatus() {
        $req = request();
        $userId = $req->user_id;
        $sorRec = \App\SaveOn::getSORUserInfo($userId);
        if (!empty($sorRec)) {
            $status = $sorRec->status;
            $userInfo = DB::table('users')
                    ->select('current_product_id', 'email', 'distid')
                    ->where('id', $userId)
                    ->first();
            $note = "Account status changed via admin panel";
            if ($status == 1) {
                $res = \App\Helper::deActivateSaveOnUser($userId, $userInfo->current_product_id, $userInfo->distid, $note);
                if ($res['error'] == 1)
                    return response()->json(['error' => 1, 'msg' => $res['msg']]);
                else
                    return response()->json(['error' => 0, 'url' => 'reload']);
            } else {
                $res = \App\Helper::reActivateSaveOnUser($userId, $userInfo->current_product_id, $userInfo->distid, $note);
                if ($res['error'] == 1)
                    return response()->json(['error' => 1, 'msg' => $res['msg']]);
                else
                    return response()->json(['error' => 0, 'url' => 'reload']);
            }
        }
        return response()->json(['error' => 1, 'msg' => 'SOR account not found']);
    }

}
