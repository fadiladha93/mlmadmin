<?php

namespace App\Services\CommissionControlCenter;

use App\BinaryCommission;
use App\CommissionDates;
use App\CommissionTemp;
use App\CommissionTempPost;
use App\Services\BinaryCommissionService;
use App\Services\JobService;
use App\Services\LeadershipCommission;
use App\Services\TsbCommissionService;
use App\Services\UnilevelCommission;
use App\Services\VibeCommissionService;
use App\TwilioAuthy;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PostCommissionService
 * @package App\Services\CommissionControlCenter
 */
class PostCommissionService
{
    const DETAILS_METHODS_MAP = [
        CommissionControlService::FSB_KEY => 'detailsFsbCommission',
        CommissionControlService::BINARY_KEY => 'detailsBinaryCommission',
        CommissionControlService::UNILEVEL_KEY => 'detailsUnilevelCommission' ,
        CommissionControlService::LEADERSHIP_KEY => 'detailsLeadershipCommission',
        CommissionControlService::TSB_KEY => 'detailsTsbCommission',
        CommissionControlService::PROMO_KEY => 'detailsPromoCommission',
        CommissionControlService::VIBE_KEY => 'detailsVibeCommission'
    ];

    const POST_METHODS_MAP = [
        CommissionControlService::FSB_KEY => 'postFsbCommission',
        CommissionControlService::BINARY_KEY => 'postBinaryCommission',
        CommissionControlService::UNILEVEL_KEY => 'postUnilevelCommission' ,
        CommissionControlService::LEADERSHIP_KEY => 'postLeadershipCommission',
        CommissionControlService::TSB_KEY => 'postTsbCommission',
        CommissionControlService::PROMO_KEY => 'postPromoCommission',
        CommissionControlService::VIBE_KEY => 'postVibeCommission'
    ];

    /** @var BinaryCommissionService */
    private $binaryCommissionService;

    /** @var UnilevelCommission */
    private $unilevelCommissionService;

    /** @var JobService */
    private $jobService;

    /**
     * PostCommissionService constructor.
     * @param BinaryCommissionService $binaryCommissionService
     * @param UnilevelCommission $unilevelCommissionService
     * @param LeadershipCommission $leadershipCommissionService
     * @param JobService $jobService
     */
    public function __construct(
        BinaryCommissionService $binaryCommissionService,
        UnilevelCommission $unilevelCommissionService,
        LeadershipCommission $leadershipCommissionService,
        JobService $jobService
    ) {
        $this->binaryCommissionService = $binaryCommissionService;
        $this->unilevelCommissionService = $unilevelCommissionService;
        $this->leadershipCommissionService = $leadershipCommissionService;
        $this->jobService = $jobService;
    }

    /**
     * @param $commissionType
     * @param Carbon $startDate
     * @return mixed
     */
    public function getCommissionDetails($commissionType, Carbon $startDate)
    {
        $method = self::DETAILS_METHODS_MAP[$commissionType];

        if (!method_exists($this, $method)) {
            return ['message' => 'Commission is not available'];
        }

        return $this->$method($startDate);
    }

    /**
     * @param $commissionType
     * @param Carbon $startDate
     * @param $verificationCode
     * @return array
     */
    public function post($commissionType, Carbon $startDate, $verificationCode)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->secondary_auth_enabled) {
            if (!$verificationCode) {
                $response = TwilioAuthy::sendToken($user->email);

                if ($response['msg']) {
                    return ['verifyTokenError' => $response['msg']];
                }

                return ['verifyToken' => true];

            } else {
                $response = TwilioAuthy::verifyToken($user->email, $verificationCode);

                if ($response['msg']) {
                    return ['verifyTokenError' => $response['msg']];
                }
            }
        }

        $method = self::POST_METHODS_MAP[$commissionType];

        if (!method_exists($this, $method)) {
            return ['message' => 'Commission is not available'];
        }

        return $this->$method($startDate);
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function detailsFsbCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        $q = DB::table('vcommission_user_temp')
            ->selectRaw('sum(amount) as total, count(amount) as tsapaid, max(amount) as maxpaid')
            ->whereDate('transaction_date', '>=', $startDate)
            ->whereDate('transaction_date', '<=', $endDate)
            ->first();

        if (!$q->total) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $q->tsapaid,
            'averagecheck' => round($q->total / $q->tsapaid, 2),
            'highestcheck' => $q->maxpaid,
            //'highestchecktsa' => $maxPayout->user->distid,
            'totalcommission' => round($q->total, 2)
        ];

        return ['details' => $data];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function detailsPromoCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commissionExists = DB::table('promo_commission')
                ->whereDate('paid_date', '>=', $startDate)
                ->whereDate('paid_date', '<=', $endDate)
                ->whereIn('status', [TsbCommissionService::CALCULATED_STATUS, TsbCommissionService::POSTED_STATUS])
                ->count() > 0;

        if (!$commissionExists) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $query = DB::table('promo_commission')
            ->selectRaw('count(1) as count, max(amount)::numeric::float8 as highest_check, sum(amount)::numeric::float8 as total')
            ->whereIn('status', [TsbCommissionService::CALCULATED_STATUS, TsbCommissionService::POSTED_STATUS])
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->first();

        $highestCheckQuery = DB::table('promo_commission')
            ->select(['users.distid'])
            ->whereIn('status', [TsbCommissionService::CALCULATED_STATUS, TsbCommissionService::POSTED_STATUS])
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->join('users', 'users.id', '=', 'promo_commission.user_id')
            ->orderByDesc('amount')
            ->first();


        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $query->count,
            'averagecheck' => round( $query->total / $query->count, 2),
            'highestcheck' => number_format($query->highest_check, 2),
            'highestchecktsa' => $highestCheckQuery->distid,
            'totalcommission' => number_format($query->total, 2)
        ];

        return ['details' => $data];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function detailsVibeCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commissionStatus = $this->jobService->getJobStatus(
            $endDate,
            VibeCommissionService::COMMISSION_TYPE
        );

        if ($commissionStatus == JobService::STATUS_QUEUED) {
            return ['message' => 'TSB commission is in the queue to be calculated'];
        }

        if ($commissionStatus == JobService::EXECUTING_STATUS) {
            return ['message' => 'TSB commission is currently being calculated'];
        }

        $commissionExists = DB::table('vibe_commissions')
                ->whereDate('paid_date', '>=', $startDate)
                ->whereDate('paid_date', '<=', $endDate)
                ->whereIn('status', [VibeCommissionService::CALCULATED_STATUS, VibeCommissionService::POSTED_STATUS])
                ->count() > 0;

        if (!$commissionExists) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $query = DB::table('vibe_commissions')
            ->selectRaw('count(1) as tsapaid, max(direct_payout) as highestcheck, sum(direct_payout) as totalcommission')
            ->whereIn('status', [VibeCommissionService::CALCULATED_STATUS, VibeCommissionService::POSTED_STATUS])
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->first();

        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $query->tsapaid,
            'averagecheck' => round($query->totalcommission / $query->tsapaid, 2),
            'highestcheck' => $query->highestcheck,
            //'highestchecktsa' => $maxPayout->user->distid,
            'totalcommission' => round($query->totalcommission, 2)
        ];

        return ['details' => $data];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function detailsTsbCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commissionStatus = $this->jobService->getJobStatus(
            $endDate,
            TsbCommissionService::COMMISSION_TYPE
        );

        if ($commissionStatus == JobService::STATUS_QUEUED) {
            return ['message' => 'TSB commission is in the queue to be calculated'];
        }

        if ($commissionStatus == JobService::EXECUTING_STATUS) {
            return ['message' => 'TSB commission is currently being calculated'];
        }

        $commissionExists = DB::table('tsb_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->whereIn('status', [TsbCommissionService::CALCULATED_STATUS, TsbCommissionService::POSTED_STATUS])
            ->count() > 0;

        if (!$commissionExists) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $query = DB::table('tsb_commission')
            ->selectRaw('count(1) as tsapaid, max(amount) as highestcheck, sum(amount) as totalcommission')
            ->whereIn('status', [TsbCommissionService::CALCULATED_STATUS, TsbCommissionService::POSTED_STATUS])
            ->whereDate('tsb_commission.paid_date', '>=', $startDate)
            ->whereDate('tsb_commission.paid_date', '<=', $endDate)
            ->first();

        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $query->tsapaid,
            'averagecheck' => round($query->totalcommission / $query->tsapaid, 2),
            'highestcheck' => $query->highestcheck,
            //'highestchecktsa' => $maxPayout->user->distid,
            'totalcommission' => round($query->totalcommission, 2)
        ];

        return ['details' => $data];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function detailsBinaryCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        $commission = $this->binaryCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            BinaryCommissionService::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Dual team commission is being calculated'];
        }

        $details = $this->binaryCommissionService->getCommissionSummery($endDate);
        $maxPayout = $this->binaryCommissionService->getMaxPayoutForCommission($endDate);
        $averagePayout  = $details->total / $details->tsa_count;

        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $details->tsa_count,
            'averagecheck' => round($averagePayout, 2),
            'highestcheck' => $maxPayout->amount_earned,
            'highestchecktsa' => $maxPayout->user->distid,
            'totalcommission' => $details->total
        ];

        return ['details' => $data];
    }

    protected function detailsUnilevelCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission = $this->unilevelCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            UnilevelCommission::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Unilevel commission is being calculated'];
        }

        $count = $this->unilevelCommissionService->getCommissionUsersCount($endDate);
        $paid = $this->unilevelCommissionService->getPaidAmount($endDate);
        $averagePayout  = $paid / $count;

        $highest = $this->unilevelCommissionService->getMaxPayoutForCommission($endDate);

        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $count,
            'averagecheck' => round($averagePayout, 2),
            'highestcheck' => round($highest->total, 2),
            'highestchecktsa' => User::getById($highest->user_id)->distid,
            'totalcommission' => round($paid, 2)
        ];

        return ['details' => $data];
    }

    protected function detailsLeadershipCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission = $this->leadershipCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            LeadershipCommission::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Leadership commission is being calculated'];
        }

        $count = $this->leadershipCommissionService->getCommissionUsersCount($endDate);
        $paid = $this->leadershipCommissionService->getPaidAmount($endDate);
        $averagePayout  = $paid / $count;

        $highest = $this->leadershipCommissionService->getMaxPayoutForCommission($endDate);

        $data = [
            'startdate' => $startDate->toDateString(),
            'closedate' => $endDate->toDateString(),
            'tsapaid' => $count,
            'averagecheck' => round($averagePayout, 2),
            'highestcheck' => round($highest->total, 2),
            'highestchecktsa' => User::getById($highest->user_id)->distid,
            'totalcommission' => round($paid, 2),
        ];

        return ['details' => $data];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function postFsbCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        $isEmpty = CommissionTempPost::isEmpty();
        if (!$isEmpty) {
            return ['message' => 'Not allowed'];
        }

        //This was giving error with the size of the array
        //CommissionTempPost::insert(CommissionTemp::all()->toArray());
        CommissionTemp::where('id','>',0)->chunk(500, function ($commissions, $page) {
            CommissionTempPost::insert($commissions->toArray());
        });

        CommissionDates::updateOrCreate(['type' => 'post'], ['start_date' => $startDate, 'end_date' => $endDate]);

        return ['message' => 'The commission was posted successfully'];
    }

    protected function postPromoCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $query = DB::table('promo_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', TsbCommissionService::CALCULATED_STATUS);

        if ($query->count() == 0) {
            return ['message' => 'This commission is not calculated yet'];
        }

        DB::table('promo_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', TsbCommissionService::CALCULATED_STATUS)
            ->update(['status' => TsbCommissionService::POSTED_STATUS]);

        return ['message' => 'Promo Commission has been posted'];
    }

    protected function postTsbCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $query = DB::table('tsb_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', TsbCommissionService::CALCULATED_STATUS);

        if ($query->count() == 0) {
            return ['message' => 'This commission is not calculated yet'];
        }

        DB::table('tsb_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', TsbCommissionService::CALCULATED_STATUS)
            ->update(['status' => TsbCommissionService::POSTED_STATUS]);

        return ['message' => 'TSB Commission has been posted'];
    }

    protected function postVibeCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $query = DB::table('vibe_commissions')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', VibeCommissionService::CALCULATED_STATUS);

        if ($query->count() == 0) {
            return ['message' => 'This commission is not calculated yet'];
        }

        DB::table('vibe_commissions')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', VibeCommissionService::CALCULATED_STATUS)
            ->update(['status' => VibeCommissionService::POSTED_STATUS]);

        return ['message' => 'VIBE Commission has been posted'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function postBinaryCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            BinaryCommissionService::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Dual team commission is being calculated'];
        }

        $commission = $this->binaryCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == BinaryCommission::PAID_STATUS) {
            return ['message' => 'This commission is already paid'];
        }

        if ($commission->status == BinaryCommission::POSTED_STATUS) {
            return ['message' => 'This commission is already posted'];
        }

        DB::table('binary_commission')
            ->where('week_ending', $endDate)
            ->where('status', BinaryCommission::CALCULATED_STATUS)
            ->update(['status' => BinaryCommission::POSTED_STATUS]);

        return ['message' => 'The commission was posted successfully'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function postUnilevelCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            UnilevelCommission::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Unilevel commission is being calculated'];
        }

        $commission = $this->unilevelCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == UnilevelCommission::PAID_STATUS) {
            return ['message' => 'This commission is already paid'];
        }

        if ($commission->status == UnilevelCommission::POSTED_STATUS) {
            return ['message' => 'This commission is already posted'];
        }

        DB::table('unilevel_commission')
            ->where('end_date', $endDate)
            ->where('status', UnilevelCommission::CALCULATED_STATUS)
            ->update(['status' => UnilevelCommission::POSTED_STATUS]);

        return ['message' => 'The commission was posted successfully'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    protected function postLeadershipCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $executingCommission = $this->jobService->getJobStatuses(
            $endDate,
            LeadershipCommission::COMMISSION_TYPE,
            [JobService::EXECUTING_STATUS]
        );

        if ($executingCommission->isNotEmpty()) {
            return ['message' => 'Leadership commission is being calculated'];
        }

        $commission = $this->leadershipCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == LeadershipCommission::PAID_STATUS) {
            return ['message' => 'This commission is already paid'];
        }

        if ($commission->status == LeadershipCommission::POSTED_STATUS) {
            return ['message' => 'This commission is already posted'];
        }

        DB::table('leadership_commission')
            ->where('end_date', $endDate)
            ->where('status', LeadershipCommission::CALCULATED_STATUS)
            ->update(['status' => LeadershipCommission::POSTED_STATUS]);

        return ['message' => 'The commission was posted successfully'];
    }
}
