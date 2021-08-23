<?php

namespace App\Http\Controllers;

use App\Services\BinaryCommissionService;
use App\Services\CommissionControlCenter\CommissionControlService;
use App\Services\JobService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Class CommissionControlCenter
 * @package App\Http\Controllers
 */
class CommissionControlCenter extends Controller
{
    const ROOT_SPONSOR_UID = 242;

    const REQUEST_EXECUTION_TIME = 60 * 30;

    const WEEK_REQUEST_KEY = 'week';
    const MONTH_REQUEST_KEY = 'month';

    /** @var BinaryCommissionService */
    private $binaryCommissionService;

    /** @var CommissionControlService */
    private $commissionControlService;

    /** @var JobService */
    private $jobService;

    /**
     * CommissionControlCenter constructor.
     * @param BinaryCommissionService $binaryCommissionService
     * @param CommissionControlService $commissionControlService
     * @param JobService $jobService
     */
    public function __construct(
        BinaryCommissionService $binaryCommissionService,
        CommissionControlService $commissionControlService,
        JobService $jobService
    ) {
        $this->binaryCommissionService = $binaryCommissionService;
        $this->commissionControlService = $commissionControlService;
        $this->jobService = $jobService;

        $this->middleware('auth.admin_superAdmin');
        set_time_limit(self::REQUEST_EXECUTION_TIME);
    }

    /**
     * @param $type
     * @return Factory|View
     */
    public function index($type)
    {
        switch ($type) {
            case 'calculate':
                return view('admin.commission_control_center.calculate');
            case 'adjustment':
                return view('admin.commission_control_center.adjustment');
            case 'audit':
                return view('admin.commission_control_center.audit');
            case 'posting':
                return view('admin.commission_control_center.posting');
            case 'payout':
                return view('admin.commission_control_center.payout');
            case 'progress':
                $processing = $this->jobService->getJobStatuses(
                    null,
                    null,
                    [JobService::STATUS_QUEUED, JobService::EXECUTING_STATUS]
                );

                return view('admin.commission_control_center.progress', [
                    'processing' => $processing,
                ]);
            default:
                return view('admin.errors.404');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request)
    {
        $response = $this->commissionControlService->calculateCommission($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function commissionPeriod(Request $request)
    {
        $period = $request->get('period');

        $data = ['commission' => $this->getCommissionsByPeriod($period)];

        return response()->json($data);
    }

    /**
     * @param $period
     * @return mixed
     */
    public function getCommissionsByPeriod($period)
    {
        $commissionMap = [
            self::WEEK_REQUEST_KEY => 'fsb',
            self::MONTH_REQUEST_KEY => 'unilevel'
        ];

        return $commissionMap[$period];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function commissionSummary(Request $request)
    {
        $response = $this->commissionControlService->commissionDetails($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request)
    {
        $response = $this->commissionControlService->postCommission($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return JsonResponse|Request
     */
    public function payout(Request $request)
    {
        $request = $this->commissionControlService->payoutCommission($request);

        return $request;
    }
}
