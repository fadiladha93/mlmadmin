<?php

namespace App\Http\Middleware;

use App\Services\CommissionControlCenter\CommissionControlService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use utill;

/**
 * Class CheckCommissionStartDate
 * @package App\Http\Middleware
 */
class CheckCommissionStartDate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('period') && $request->has('fromDate')) {
            $period = $request->get('period');
            $startDate =  Carbon::parse($request->get('fromDate'));

            $errors = $this->validateStartDate($startDate, $period);

            if ($errors) {
                return response()->json($errors, Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }

    /**
     * @param $date
     * @param $period
     * @return array
     */
    public function validateStartDate($date, $period)
    {
        $errors = [];

        if ($period == CommissionControlService::WEEK_REQUEST_KEY) {
            $errors = $this->validateWeekDate($date);
        }

        if ($period == CommissionControlService::MONTH_REQUEST_KEY) {
            $errors = $this->validateMonthDate($date);
        }

        return $errors;
    }

    /**
     * @param Carbon $date
     * @return array
     */
    private function validateWeekDate(Carbon $date)
    {
        if (!$date->isMonday() || $date->eq(utill::getUserCurrentDate()->startOfWeek())) {
            return ['error' => 'Date should be Monday of the previous weeks'];
        }

        return [];
    }

    /**
     * @param Carbon $date
     * @return array
     */
    private function validateMonthDate(Carbon $date)
    {
        $greaterAccessable = utill::getUserCurrentDate()->subMonth(1)->startOfMonth();

        if ($date->greaterThan($greaterAccessable) || !$date->eq($date->copy()->startOfMonth())) {
            return ['error' => 'Date should be the beginning of the previous months'];
        }

        return [];
    }
}
