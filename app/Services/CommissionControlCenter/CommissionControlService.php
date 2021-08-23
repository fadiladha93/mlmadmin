<?php

namespace App\Services\CommissionControlCenter;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CommissionControlService
 * @package App\Services\CommissionControlCenter
 */
class CommissionControlService
{
    const WEEK_REQUEST_KEY = 'week';
    const MONTH_REQUEST_KEY = 'month';

    const FSB_KEY = 'fsb';
    const BINARY_KEY = 'dual-team';
    const UNILEVEL_KEY = 'unilevel';
    const LEADERSHIP_KEY = 'leadership';
    const TSB_KEY = 'tsb';
    const PROMO_KEY = 'promo';
    const VIBE_KEY = 'vibe';

    /** @var CalculateCommissionService */
    private $calculateCommissionService;

    /** @var PostCommissionService */
    private $postCommissionService;

    /** @var PayoutCommissionService */
    private $payoutCommissionService;

    /**
     * CommissionControlService constructor.
     * @param CalculateCommissionService $calculateCommissionService
     * @param PostCommissionService $postCommissionService
     * @param PayoutCommissionService $payoutCommissionService
     */
    public function __construct(
        CalculateCommissionService $calculateCommissionService,
        PostCommissionService $postCommissionService,
        PayoutCommissionService $payoutCommissionService
    ) {
        $this->calculateCommissionService = $calculateCommissionService;
        $this->postCommissionService = $postCommissionService;
        $this->payoutCommissionService = $payoutCommissionService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateCommission(Request $request)
    {
        $params = json_decode($request->post('params'));

        $startDate =  Carbon::parse($params->fromDate);
        $commission = $params->commission;
        $isConfirmAction = $params->isConfirmAction;
        $isConfirmRecalculate = $params->isConfirmRecalculate;

        $responseData = $this->calculateCommissionService->calculate($commission, $startDate, $isConfirmAction, $isConfirmRecalculate);

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function commissionDetails(Request $request)
    {
        $startDate =  Carbon::parse($request->get('fromDate'));
        $commission = $request->get('commission');

        $responseData = $this->postCommissionService->getCommissionDetails($commission, $startDate);

        return response()->json($responseData);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postCommission(Request $request)
    {
        $startDate =  Carbon::parse($request->get('fromDate'));
        $commission = $request->get('commission');
        $verificationCode = $request->get('verificationCode');

        $data = $this->postCommissionService->post($commission, $startDate, $verificationCode);

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function payoutCommission(Request $request)
    {
        $startDate =  Carbon::parse($request->get('fromDate'));
        $commission = $request->get('commission');
        $verificationCode = $request->get('verificationCode');

        $data = $this->payoutCommissionService->payout($commission, $startDate, $verificationCode);

        return response()->json($data);
    }
}
