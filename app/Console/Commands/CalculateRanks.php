<?php

namespace App\Console\Commands;

use App\Jobs\RankCalculation;
use App\Logging\BuumLogger;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

/**
 * Class CalculateRanks
 * @package App\Console\Commands
 */
class CalculateRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ranks:calculate {--fromDate=} {--toDate=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate ranks.';

    /**
     * @throws Exception
     */
    public function handle()
    {
        set_time_limit(0);
        (new BuumLogger('Ranks Calculation Started', BuumLogger::LOG_TYPE_INFO))->toAll();

        // default values for cron-job rank calculation
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();

        // options should be set together if exist
        $fromDateOption = $this->option('fromDate');
        $toDateOption = $this->option('toDate');

        if (!empty($fromDateOption) || !empty($toDateOption)) {
            // validate passed option values
            if (!empty($fromDateOption) && !empty($toDateOption)) {
                $startDate = Carbon::parse($fromDateOption);
                $endDate = Carbon::parse($toDateOption)->endOfDay();
            } else {
                throw new Exception('options `fromDate` and `toDate` should be set together');
            }
        }

        if ($endDate->lt($startDate)) {
            throw new Exception('End date should be greater than start');
        }

        RankCalculation::dispatch($startDate, $endDate)->onQueue('default')->delay(now()->addSeconds(5));

        (new BuumLogger('Ranks Calculation Dispatched', BuumLogger::LOG_TYPE_INFO))->toAll();
    }
}
