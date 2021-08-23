<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class IDecideService
 * @package App\Services
 */
class IDecideService
{
    public function deActivateUser($userId, $note)
    {
        if (!$idecideRec = \App\IDecide::getIDecideUserInfo($userId)) {
            return response()->json(['error' => 1, 'msg' => 'iDecide account not found']);
        }

        $userInfo = DB::table('users')
            ->select('current_product_id', 'email', 'distid')
            ->where('id', $userId)
            ->first();

        if ((int)$idecideRec->status) {
            $res      = \App\Helper::deActivateIdecideUser($userId);
            $response = response()->json(['error' => 0, 'url' => 'reload']);

            if ((int)$res['error']) {
                $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
            }

            return $response;
        }

        return response()->json(['error' => 1, 'msg' => \App\IDecide::USER_ALREADY_IN_INACTIVE_STATUS]);
    }


    public function toggleStatus($userId)
    {
        $idecideRec = \App\IDecide::getIDecideUserInfo($userId);

        if (empty($idecideRec)) {
            return response()->json(['error' => 1, 'msg' => 'iDecide account not found']);
        }

        $status = $idecideRec->status;

        if ($status == 1) {
            $res = \App\Helper::deActivateIdecideUser($userId);

            $response = response()->json(['error' => 0, 'url' => 'reload']);

            if ($res['error'] == 1) {
                $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
            }

            return $response;
        }

        $res = \App\Helper::reActivateIdecideUser($userId);

        $response = response()->json(['error' => 0, 'url' => 'reload']);

        if ($res['error'] == 1) {
            $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
        }

        return $response;
    }
}
