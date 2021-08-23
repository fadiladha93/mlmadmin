<?php

namespace App\Jobs;

use App\Facades\TsbCommissionManager;
use App\Models\CommissionStatus;
use App\Services\JobService;
use App\Services\TsbCommissionService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Imtigger\LaravelJobStatus\Trackable;

class TsbCommission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /** @var int  */
    public $tries = 1;

    private $startDate;
    private $endDate;
    private $filename;

    /**
     * Create a new job instance.
     *
     * @param $startDate
     * @param $endDate
     * @param $filename
     */
    public function __construct($startDate, $endDate, $filename)
    {
        set_time_limit(0);

        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filename = $filename;

        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('Starting TSB Calculate Commission Job...');

        $status = CommissionStatus::where('commission_type', TsbCommissionService::COMMISSION_TYPE)
            ->whereDate('end_date', $this->endDate)
            ->where('status', JobService::STATUS_QUEUED)
            ->first();


        if (!$status) {
            Log::debug('No CommissionStatus found.  Adding one and setting to executing status');
            $status = new CommissionStatus();

            $status->commission_type = TsbCommissionService::COMMISSION_TYPE;
            $status->end_date = $this->endDate;
        } else {
            Log::debug('CommissionStatus found. Setting to executing status');
        }

        $status->status = JobService::EXECUTING_STATUS;
        $status->save();

        Log::debug('Starting TSB Calculate Commission process...');
        TsbCommissionManager::calculateCommission($this->filename, $this->endDate);
        Log::debug('Finished TSB Calculate Commission process...');

        $status->status = JobService::STATUS_FINISHED;
        $status->save();

        Log::debug('Finish TSB Calculate Commission Job...');
    }
}
