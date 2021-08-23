<?php

namespace App\Services;

use App\Address;
use App\Facades\UserTransferManager;
use App\Helper;
use App\IDecide;
use App\IPayOut;
use App\SaveOn;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;

class UserTransferService
{
    private $userIdFieldByTable = [
//        'addresses' => 'userid',
        'bc_carryover_history' => 'user_id',
        'binary_commission' => 'user_id',
        'binary_plan' => ['user_id', 'sponsor_id'],
        'boomerang_history' => 'user_id',
        'boomerang_inv' => 'userid',
        'boomerang_tracker' => 'userid',
        'commission' => 'user_id',
        'ewallet_transactions' => 'user_id',
        'leadership_commission' => 'user_id',
        'orders' => 'userid',
        'payment_methods' => 'userID',
        'rank_history' => 'users_id',
        'replicated_preferences' => 'user_id',
        'tsb_commission' => 'user_id',
        'unilevel_commission' => 'user_id',
        'update_history' => 'updated_by',
        'update_sponsor_history' => 'user_id',
        'user_activity_history' => 'user_id',
        'user_rank_history' => 'user_id',
        'user_statistic' => 'user_id',
        'week_detail' => 'user_id',
        'week_summary' => 'user_id',
        'discount_coupon' => ['used_by', 'generated_for']
    ];

    /**
     * @param object $user User instance with model
     * @return object
     */
    private function createNewUser($user)
    {
        unset($user->id);
        $user->distid = User::getRandomTSA();

        $id = DB::table('users')->insertGetId((array)$user);
        $user->id = $id;

        return $user;
    }

    private function updateRelatedTables($userId, $newUserId)
    {
        foreach ($this->userIdFieldByTable as $table=>$userIdField) {
            if (is_array($userIdField)) {
                foreach ($userIdField as $field) {
                    DB::table($table)->where($field, $userId)
                        ->update([$field => $newUserId]);
                }
            } else {
                DB::table($table)->where($userIdField, $userId)
                    ->update([$userIdField => $newUserId]);
            }
        }
    }

    private function disableOldServiceAccounts($userId, $distId, $username, $productId)
    {
        IDecide::disableUser($userId);
        SaveOn::disableUser($productId, $distId, SaveOn::USER_ACCOUNT_REST);

        $params = array(
            'fn' => 'eWallet_UpdateUserAccountStatus',
            'UserName' => $username,
            'Status' => 'Closed'
        );

        IPayOut::curl($params);
    }

    /**
     * @param \stdClass $user
     */
    private function createNewServiceAccounts($user)
    {
        Helper::createiPayoutUser($user);

    }

    private function updateSponsorIds($distId, $newDistId)
    {
        DB::table('users')->where('sponsorid', $distId)
            ->update(['sponsorid' => $newDistId]);
    }

    private function terminateUserAccount($userId)
    {
        DB::table('users')
            ->where('id', $userId)
            ->update(['account_status' => User::ACC_STATUS_TERMINATED]);
    }

    public function transferUser($userId)
    {
        $user = DB::table('users')
            ->where('id', $userId)
            ->first();

        if (!$user) {
            return false;
        }

        $distId = $user->distid;

        $newUser = $this->createNewUser($user);

        $this->updateSponsorIds($distId, $newUser->distid);

        $this->updateRelatedTables($userId, $newUser->id);

        $this->terminateUserAccount($userId);

        $isProduction = in_array(strtolower(env('APP_ENV')), ['prod', 'production']);

        if ($isProduction) {
            $this->disableOldServiceAccounts($userId, $distId, $user->username, $user->current_product_id);
            $this->createNewServiceAccounts($user);
        }

        return $newUser->id;
    }
}
