<?php

namespace App\Services;

use App\User;
use App\UserActivityHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class UserActivityHistoryService
 * @package App\Services
 */
class UserActivityHistoryService
{
    const BATCH_SIZE = 250;

    const ACTIVE_USERS = [
        'A1357703',
        'A1637504',
        'TSA9846698',
        'TSA3564970',
        'TSA9714195',
        'TSA8905585',
        'TSA2593082',
        'TSA0707550',
        'TSA9834283',
        'TSA5138270',
        'TSA8715163',
        'TSA3516402',
        'TSA8192292',
        'TSA9856404',
        'TSA1047539',
        'TSA7594718',
        'TSA0002566'
    ];

    /** @var OrderService */
    protected $orderService;

    /**  @var int */
    protected $userOrdersAmount = 0;

    /**
     * UserActivityHistoryService constructor.
     * @param OrderService $orderService
     * @param BinaryCommissionService $binaryCommissionService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function updateUserActivity(Carbon $startDate, Carbon $endDate)
    {
        $errors = [];

        $isCurrentValueUpdate = $endDate->diffInDays(Carbon::now('UTC')) == 0;

        try {
            DB::table('users')->orderBy('id')
                ->chunk(self::BATCH_SIZE, function ($users) use ($startDate, $endDate, $isCurrentValueUpdate) {
                    foreach ($users as $user) {
                        DB::transaction(function () use ($user, $startDate, $endDate, $isCurrentValueUpdate) {
                            /** @var User $user */
                            $user = User::find($user->id);

                            $isActive = $this->isUserActive($user, $startDate, $endDate);
                            $isActivate = $this->isUserActivate($user);
                            $isBcActive = $this->isUserBcActive($user);

                            $activity = new UserActivityHistory();
                            $activity->user_id = $user->id;
                            $activity->is_active = $this->isUserActive($user, $startDate, $endDate);
                            $activity->is_activate = $this->isUserActivate($user);
                            $activity->is_bc_active = $this->isUserBcActive($user);
                            $activity->created_at = $endDate;

                            $activity->save();

                            if ($isCurrentValueUpdate) {
                                $user->is_active = $isActive;
                                $user->is_activate = $isActivate;
                                $user->is_bc_active = $isBcActive;

                                $user->save();
                            }
                        });
                    }
                });

        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;

    }

    /**
     * @param User $user
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool
     */
    protected function isUserActive(User $user, Carbon $startDate, Carbon $endDate)
    {
        if (in_array($user->distid, self::ACTIVE_USERS)) {
            return true;
        }

        if ($user->isTurkeyInActivePeriod($endDate)) {
            return true;
        }

        if ($user->hasActivePremiumFc($endDate)) {
            return true;
        }

        $this->userOrdersAmount = $this->orderService->getOrdersAmountForUser($user, $startDate, $endDate);

        if ($this->userOrdersAmount >= User::MIN_QV_MONTH_VALUE && $user->isActiveStatus()) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function isUserActivate(User $user)
    {
        if (in_array($user->distid, self::ACTIVE_USERS)) {
            return true;
        }

        if ($this->userOrdersAmount >= User::MIN_QV_WITHOUT_COMMISSIONS && $user->isActiveStatus()) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function isUserBcActive(User $user)
    {
        if (in_array($user->distid, self::ACTIVE_USERS)) {
            return true;
        }

        if ($this->userOrdersAmount >= User::MIN_QV_MONTH_VALUE &&
            $user->binaryCommissions->isNotEmpty() &&
            $user->isActiveStatus()
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param Carbon $date
     * @return mixed
     */
    public function getCountActivityHistoryByDate(Carbon $date)
    {
        return UserActivityHistory::whereDate('created_at', $date)->count();
    }
}
