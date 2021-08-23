<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class SorService
 * @package App\Services
 */
class SorService
{
    public function deActivateUser($userId, $note)
    {
        if (!$sorRec = \App\SaveOn::getSORUserInfo($userId)) {
            return response()->json(['error' => 1, 'msg' => 'SOR account not found']);
        }

        $userInfo = DB::table('users')
            ->select('current_product_id', 'email', 'distid')
            ->where('id', $userId)
            ->first();

        if ((int)$sorRec->status) {
            $res = \App\Helper::deActivateSaveOnUser(
                $userId,
                $userInfo->current_product_id,
                $userInfo->distid,
                $note
            );

            $response = response()->json(['error' => 0, 'url' => 'reload']);

            if (filter_var($res['error'], FILTER_VALIDATE_BOOLEAN)) {
                $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
            }

            return $response;
        }

        return response()->json([
            'error' => 1,
            'msg' => \App\SaveOn::USER_ALREADY_IN_INACTIVE_STATUS]
        );
    }

    public function toggleStatus($userId, $note) {
        if (!$sorRec = \App\SaveOn::getSORUserInfo($userId)) {
            return response()->json(['error' => 1, 'msg' => 'SOR account not found']);
        }

        $userInfo = DB::table('users')
            ->select('current_product_id', 'email', 'distid')
            ->where('id', $userId)
            ->first();

        if ((int)$sorRec->status) {
            $res = \App\Helper::deActivateSaveOnUser(
                $userId,
                $userInfo->current_product_id,
                $userInfo->distid,
                $note
            );

            $response = response()->json(['error' => 0, 'url' => 'reload']);

            if ((int)$res['error']) {
                $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
            }

            return $response;
        }

        $res = \App\Helper::reActivateSaveOnUser(
            $userId,
            $userInfo->current_product_id,
            $userInfo->distid,
            $note
        );

        $response = response()->json(['error' => 0, 'url' => 'reload']);

        if ((int)$res['error']) {
            $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
        }

        return $response;
    }

    public function setStatus($userId, $status, $note) {
        if (!$sorRec = \App\SaveOn::getSORUserInfo($userId)) {
            return response()->json(['error' => 1, 'msg' => 'SOR account not found']);
        }

        $userInfo = DB::table('users')
            ->select('current_product_id', 'email', 'distid')
            ->where('id', $userId)
            ->first();

        if ((int)$sorRec->status) {
            $res = \App\Helper::deActivateSaveOnUser(
                $userId,
                $userInfo->current_product_id,
                $userInfo->distid,
                $note
            );

            $response = response()->json(['error' => 0, 'url' => 'reload']);

            if ((int)$res['error']) {
                $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
            }

            return $response;
        }

        $res = \App\Helper::reActivateSaveOnUser(
            $userId,
            $userInfo->current_product_id,
            $userInfo->distid,
            $note
        );

        $response = response()->json(['error' => 0, 'url' => 'reload']);

        if ((int)$res['error']) {
            $response = response()->json(['error' => 1, 'msg' => $res['msg']]);
        }

        return $response;
    }
}
