<?php

namespace App\Console\Commands;

use App\Services\SubscriptionCronService;
use App\SubscriptionHistory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Console\Command;

class SubscriptionCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:subscription {--startDate=} {--endDate=}';

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

    public function handle()
    {
        $startDateOption = $this->option('startDate');
        $endDateOption = $this->option('endDate');

        $startDate = null;
        $endDate = null;

        if ((empty($startDateOption) && !empty($startDateOption)) || (empty($endDateOption) && !empty($startDateOption))) {
            $this->error('startDate and endDate must be set together');
            return;
        }

        $startDate = Carbon::parse($startDateOption);
        $endDate = Carbon::parse($endDateOption);

        if (!$startDate) {
            $this->error('Invalid start date: ' . $startDateOption);
        }

        if (!$endDate) {
            $this->error('Invalid end date: ' . $endDateOption);
        }

        if (!$startDate || !$endDate) {
            return;
        }

        $dateRange = $this->generateDateRange($startDate, $endDate);

        $cron = new SubscriptionCronService();

        foreach ($dateRange as $date) {
            $cron->run($date);
        }
    }

    private function generateDateRange(Carbon $startDate, Carbon $endDate)
    {
        $dates = [];

        for($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

}
