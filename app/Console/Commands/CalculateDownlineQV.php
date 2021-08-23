<?php

namespace App\Console\Commands;

use App\UserRankHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CalculateDownlineQV extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:calculate_downline_qv {--fromDate=} {--toDate=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Process
     * @throws Exception
     */
    public function handle()
    {
        // options should be set together if exist
        $fromDateOption = $this->option('fromDate');
        $toDateOption = $this->option('toDate');

        $startDate = null;
        $endDate = null;

        try {
            if (!empty($fromDateOption) || !empty($toDateOption)) {
                // validate passed option values
                if (!empty($fromDateOption) && !empty($toDateOption)) {
                    $startDate = Carbon::parse($fromDateOption);
                    $endDate = Carbon::parse($toDateOption);

                    $this->validateCommissionPeriod($startDate, $endDate);
                } else {
                    throw new Exception('options `fromDate` and `toDate` should be set together');
                }
            }
            // Process the query
            UserRankHistory::calculateDownlineQV($startDate, $endDate);
        } catch (\Exception $e) {
            Log::error(sprintf("Invalid calculate_downline_qv period: %s", $e->getMessage()));
        }
    }

    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @throws Exception
     */
    private function validateCommissionPeriod(Carbon $startDate, Carbon $endDate)
    {
        if ($endDate->lt($startDate)) {
            throw new Exception('End date should be greater than start');
        }

        if (!$startDate->startOfMonth()) {
            throw new Exception('Start date should be the first day of month');
        }

        if (!$endDate->endOfMonth()) {
            throw new Exception('End date should be the last day of month');
        }
    }
}
