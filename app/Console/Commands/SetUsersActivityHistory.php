<?php

namespace App\Console\Commands;

use App\Services\UserActivityHistoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\User;

/**
 * Class SetUsersActivityHistory
 * @package App\Console\Commands
 */
class SetUsersActivityHistory extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:set:activity_history {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var UserActivityHistoryService */
    protected $activityHistoryService;

    /**
     * Create a new command instance.
     *
     * @param UserActivityHistoryService $activityHistoryService
     */
    public function __construct(UserActivityHistoryService $activityHistoryService)
    {
        $this->activityHistoryService = $activityHistoryService;

        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        // make sure we have time to execute the script.
        set_time_limit(0);

        $this->info('Start process');

        $date = Carbon::now('UTC')->subDay();

        if ($this->hasOption('date') && !empty($this->option('date'))) {
            $date = Carbon::parse($this->option('date'));
        }

        try {
            $lastDayOfMonth = $date->copy()->endOfMonth();

            if ($date->dayOfWeek !== 0 && $date->diffInDays($lastDayOfMonth) !== 0) {
                throw new Exception('Invalid entered date. Please enter Saturday or the last day of the month');
            }

            $startDate = $date->copy()->subMonth()->startOfDay();

            $activityHistory = $this->activityHistoryService->getCountActivityHistoryByDate($date);

            if ($activityHistory > 0) {
                return;
            }

            $errors = $this->activityHistoryService->updateUserActivity($startDate, $date->endOfDay());

            foreach ($errors as $message) {
                $this->error($message);
            }
        } catch (\Exception $e) {
            Log::alert(sprintf('Set user active error: %s', $e->getMessage()));
        }

        $this->info('Finish');
    }
}
