<?php

namespace App\Console\Commands;

use App\Services\LeadershipCommission;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Class CalculateBinaryCommission
 * @package App\Console\Commands
 */
class CalculateLeadershipCommission extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:calculateLeadership {--fromDate=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Leadership Commission';


    /**
     * @var LeadershipCommission
     */
    private $leadershipCommissionService;

    public function __construct(LeadershipCommission $leadershipCommissionService) {
        $this->leadershipCommissionService = $leadershipCommissionService;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        $fromDate = $this->option('fromDate');

        $data = [
            'fromDate' => $fromDate
        ];

        $rules = [
            'fromDate' => 'required|date_format:Y-m-d'
        ];

        $messages = [
            'fromDate.required' => 'fromDate is required',
            'fromDate.date_format' => 'fromDate should be in Y-m-d (2019-10-01) format',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $errorMessage = implode("\n", $validator->messages()->all());
            $this->error($errorMessage);

            return;
        }

        $fromDateCarbon = Carbon::createFromFormat('Y-m-d', $fromDate);

        if ($fromDateCarbon->day != 1) {
            $this->error('fromDate must be first of the month');
            return;
        }

        $toDate = $fromDateCarbon->format('Y-m-t'); // t is the last day of the month always
        $toDateCarbon = Carbon::createFromFormat('Y-m-d', $toDate);

        $this->info('Start calculation process.');

        $this->leadershipCommissionService->calculateCommission($fromDateCarbon, $toDateCarbon);

        $this->info('Finish calculation process.');
    }
}
