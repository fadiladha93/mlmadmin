<?php

namespace App\Console\Commands;

use App\BinaryCommissionCarryoverHistory;
use App\BinaryCommissionHistory;
use App\Jobs\BinaryCommission;
use App\Jobs\RecalculateCommissions;
use App\Services\CarryoverHistoryService;
use App\Services\CommissionHistoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Class CalculateBinaryCommission
 * @package App\Console\Commands
 */
class CalculateBinaryCommission extends Command
{
    /** @var CarryoverHistoryService */
    private $carryoverHistoryService;

    /** @var CommissionHistoryService */
    private $commissionHistoryService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:calculate_binary_commission {--fromDate=} {--toDate=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Binary Commission';

    /**
     * Create a new command instance.
     *
     * @param CarryoverHistoryService $carryoverHistoryService
     * @param CommissionHistoryService $commissionHistoryService
     */
    public function __construct(
        CarryoverHistoryService $carryoverHistoryService,
        CommissionHistoryService $commissionHistoryService
    ) {
        $this->carryoverHistoryService = $carryoverHistoryService;
        $this->commissionHistoryService = $commissionHistoryService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        $this->info('Start calculation process.');

        $mondayPreviousWeek = Carbon::now('UTC')->startOfWeek()->subDay(7);
        $sundayPreviousWeek = Carbon::now('UTC')->startOfWeek()->subDay(1);

        // default values for the previous week cron-job commission calculation
        $startDate = $mondayPreviousWeek;
        $endDate = $sundayPreviousWeek;

        // options should be set together if exist
        $fromDateOption = $this->option('fromDate');
        $toDateOption = $this->option('toDate');

        if (!empty($fromDateOption) || !empty($toDateOption)) {
            // validate passed option values
            if (!empty($fromDateOption) && !empty($toDateOption)) {
                $startDate = Carbon::parse($fromDateOption);
                $endDate = Carbon::parse($toDateOption);
            } else {
                throw new Exception('options `fromDate` and `toDate` should be set together');
            }
        }

        // include existing days time from the range 00:00:00 - 23:59:59
        $startDate->startOfDay();
        $endDate->endOfDay();

        $this->validateCommissionPeriod($startDate, $endDate);

        $errors = $this->processCalculate($startDate, $endDate);

        foreach ($errors as $message) {
            $this->error($message);
        }

        $this->info('Finish calculation process.');
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array|void
     */
    protected function processCalculate(Carbon $startDate, Carbon $endDate)
    {
        $errors = [];

        try {
            /** @var Collection $executedCommissions */
            $recalculatedCommissions = $this->commissionHistoryService->getNextCommissions($startDate, $endDate);

            if ($recalculatedCommissions->isEmpty()) {
                BinaryCommission::dispatch($startDate, $endDate)->onQueue('default');

                return $errors;
            }

            /** @var BinaryCommissionHistory $currentCommission */
            $currentCommission = $this->commissionHistoryService->getCommissionByDate($startDate, $endDate);

            if (!$currentCommission) {
                throw new Exception('Please enter the date of existing commission');
            }

            /** @var BinaryCommissionHistory $previousCommission */
            $previousCommission = $this->commissionHistoryService->getPreviousCommission($startDate, $endDate);

            if ($previousCommission) {
                /** @var Collection $isExistCarryoverHistory */
                $carryoverHistory = $this->carryoverHistoryService->getCountCarryoverHistoryByCommission($previousCommission);

                if ($carryoverHistory == 0) {
                    throw new Exception('Please migrate carryover values before the start calculations');
                    // TODO: should be uncommented if we need to re-calculate previous commissions
                    //$recalculatedCommissions = BinaryCommissionHistory::orderBy('start_date', 'asc')->get();
                }
            }

            $this->clearCommissionHistory($recalculatedCommissions);

            $executedJobs = $this->getRecalculateCommissionJobs($recalculatedCommissions);

            RecalculateCommissions::dispatch($executedJobs)->onQueue('default');
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        return $errors;
    }

    /**
     * @param Collection $recalculatedCommissions
     */
    protected function clearCommissionHistory(Collection $recalculatedCommissions)
    {
        $recalculateCommissionIds = $recalculatedCommissions->map(
            function (BinaryCommissionHistory $binaryCommission) {
                return $binaryCommission->id;
            });

        BinaryCommissionCarryoverHistory::whereIn('bc_history_id', $recalculateCommissionIds->toArray())
            ->delete();

        BinaryCommissionHistory::whereIn('id', $recalculateCommissionIds->toArray())
            ->delete();
    }

    /**
     * @param Collection $recalculatedCommissions
     * @return array
     */
    protected function getRecalculateCommissionJobs(Collection $recalculatedCommissions)
    {
        $executedJobs = [];

        $recalculatedCommissions = $recalculatedCommissions->toArray();

        foreach ($recalculatedCommissions as $commission) {
            $startDate = Carbon::parse($commission['start_date']);
            $endDate = Carbon::parse($commission['end_date']);

            $executedJobs[] = (new BinaryCommission($startDate, $endDate, true))->onQueue('default');
        }

        return $executedJobs;
    }

    /**
     * @param static $endDate
     * @param static $startDate
     * @throws Exception
     */
    private function validateCommissionPeriod(Carbon $startDate, Carbon $endDate)
    {
        if ($endDate->lt($startDate)) {
            throw new Exception('End date should be greater than start');
        }

        if (!$startDate->isMonday()) {
            throw new Exception('Start date should be Monday');
        }

        if (!$endDate->isSunday()) {
            throw new Exception('End date should be Sunday');
        }

        if ($endDate->diffInDays($startDate) !== 6) {
            throw new Exception('Between start/end dates should be only 7 days');
        }
    }
}
