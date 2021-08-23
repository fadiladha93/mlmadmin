<?php

namespace App\Services\CommissionControlCenter;

use App\BinaryCommission;
use App\EwalletTransaction;
use App\Facades\BinaryCommissionManager;
use App\Jobs\CommissionPayment\LeadershipPayCommissionJob;
use App\Jobs\CommissionPayment\UnilevelPayCommissionJob;
use App\PromoCommission;
use App\Services\BinaryCommissionService;
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
 * Class PayoutCommissionService
 * @package App\Services\CommissionControlCenter
 */
class PayoutCommissionService
{
    const PAYOUT_METHODS_MAP = [
        CommissionControlService::FSB_KEY => 'payoutFsbCommission',
        CommissionControlService::BINARY_KEY => 'payoutBinaryCommission',
        CommissionControlService::UNILEVEL_KEY => 'payoutUnilevelCommission' ,
        CommissionControlService::LEADERSHIP_KEY => 'payoutLeadershipCommission',
        CommissionControlService::TSB_KEY => 'payoutTsbCommission',
        CommissionControlService::PROMO_KEY => 'payoutPromoCommission',
        CommissionControlService::VIBE_KEY => 'payoutVibeCommission'
    ];

    /** @var BinaryCommissionService */
    private $binaryCommissionService;

    /** @var UnilevelCommission */
    private $unilevelCommissionService;

    /** @var LeadershipCommission */
    private $leadershipCommissionService;

    /** @var TsbCommissionService */
    private $tsbCommissionService;

    /**
     * @var VibeCommissionService
     */
    private $vibeCommissionService;

    /**
     * PostCommissionService constructor.
     * @param BinaryCommissionService $binaryCommissionService
     * @param UnilevelCommission $unilevelCommission
     * @param LeadershipCommission $leadershipCommission
     * @param TsbCommissionService $tsbCommissionService
     * @param VibeCommissionService $vibeCommissionService
     */
    public function __construct(
        BinaryCommissionService $binaryCommissionService,
        UnilevelCommission $unilevelCommission,
        LeadershipCommission $leadershipCommission,
        TsbCommissionService $tsbCommissionService,
        VibeCommissionService $vibeCommissionService
    ) {
        $this->binaryCommissionService = $binaryCommissionService;
        $this->unilevelCommissionService = $unilevelCommission;
        $this->leadershipCommissionService = $leadershipCommission;
        $this->tsbCommissionService = $tsbCommissionService;
        $this->vibeCommissionService = $vibeCommissionService;
    }

    /**
     * @param $commissionType
     * @param Carbon $startDate
     * @param $verificationCode
     * @return array
     */
    public function payout($commissionType, Carbon $startDate, $verificationCode)
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

        $method = self::PAYOUT_METHODS_MAP[$commissionType];

        if (!method_exists($this, $method)) {
            return ['message' => 'Commission is not available'];
        }

        return $this->$method($startDate);
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutFsbCommission(Carbon $startDate)
    {
        DB::selectFromWriteConnection("select * from calculate_fsb_approve()");

        return ['message' => 'Commission payout was successfully completed'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutBinaryCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfWeek();

        $commission = $this->binaryCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == BinaryCommission::PAID_STATUS) {
            return ['message' => 'This commission is already payed'];
        }

        if ($commission->status !== BinaryCommission::POSTED_STATUS) {
            return ['message' => 'This commission is not posted yet'];
        }

        BinaryCommissionManager::payCommission($endDate);

        return ['message' => 'Commission payout was successfully completed'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutUnilevelCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission = $this->unilevelCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == UnilevelCommission::PAID_STATUS) {
            return ['message' => 'This commission is already payed'];
        }

        if ($commission->status !== UnilevelCommission::POSTED_STATUS) {
            return ['message' => 'This commission is not posted yet'];
        }

        UnilevelPayCommissionJob::dispatch($endDate)->onQueue('default');

        return ['message' => 'Commission payout was successfully run'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutLeadershipCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission = $this->leadershipCommissionService->getCommissionByDate($endDate);

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == LeadershipCommission::PAID_STATUS) {
            return ['message' => 'This commission is already payed'];
        }

        if ($commission->status !== LeadershipCommission::POSTED_STATUS) {
            return ['message' => 'This commission is not posted yet'];
        }

        LeadershipPayCommissionJob::dispatch($endDate)->onQueue('default');

        return ['message' => 'Commission payout was successfully run'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutPromoCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission  = DB::table('promo_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->first();

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == TsbCommissionService::PAID_STATUS) {
            return ['message' => 'This commission is already payed'];
        }

        if ($commission->status !== TsbCommissionService::POSTED_STATUS) {
            return ['message' => 'This commission is not posted yet'];
        }

        $commissions = DB::table('promo_commission')
            ->selectRaw('id, user_id, amount::numeric::float8 as amount, promo')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->where('status', '=', PromoCommission::POSTED_STATUS)
            ->get();

        foreach ($commissions as $commission) {
            EwalletTransaction::addCommission($commission->user_id, $commission->amount, 'PROMO', $commission->promo);
        }

        $ids = array_column($commissions->toArray(), 'id');

        DB::table('promo_commission')
            ->whereIn('id', $ids)
            ->update([
                'status' => PromoCommission::PAID_STATUS
            ]);

        return ['message' => 'This commission has been paid successfully.'];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutTsbCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission  = DB::table('tsb_commission')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->first();

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == TsbCommissionService::PAID_STATUS) {
            return ['message' => 'This commission is already payed'];
        }

        if ($commission->status !== TsbCommissionService::POSTED_STATUS) {
            return ['message' => 'This commission is not posted yet'];
        }

        list($success, $failure) = $this->tsbCommissionService->payoutCommission($startDate, $endDate);

        return ['message' => "Commission payout was successfully run ($success successful, $failure fail(s))"];
    }

    /**
     * @param Carbon $startDate
     * @return array
     */
    public function payoutVibeCommission(Carbon $startDate)
    {
        $endDate = $startDate->copy()->endOfMonth();

        $commission  = DB::table('vibe_commissions')
            ->whereDate('paid_date', '>=', $startDate)
            ->whereDate('paid_date', '<=', $endDate)
            ->first();

        if (!$commission) {
            return ['message' => 'This commission is not calculated yet'];
        }

        if ($commission->status == VibeCommissionService::PAID_STATUS) {
            return ['message' => 'This commission is already payed'];
        }

        if ($commission->status !== VibeCommissionService::POSTED_STATUS) {
            return ['message' => 'This commission is not posted yet'];
        }

        list($success, $failure) = $this->vibeCommissionService->payoutCommission($startDate, $endDate);

        return ['message' => "Commission payout was successfully run ($success successful, $failure fail(s))"];
    }
}
