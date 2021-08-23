<?php

namespace App\Jobs;

use App\Facades\UnilevelCommissionManager;
use App\Models\CommissionStatus;
use App\Services\JobService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Imtigger\LaravelJobStatus\Trackable;

class UnilevelCommission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /** @var int  */
    public $tries = 1;

    protected $fromDate;
    protected $toDate;

    /**
     * Create a new job instance.
     *
     * @param $fromDate
     * @param $toDate
     */
    public function __construct($fromDate, $toDate)
    {
        set_time_limit(0);

        $this->fromDate = $fromDate;
        $this->toDate = $toDate;

        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = CommissionStatus::where('commission_type', \App\Services\UnilevelCommission::COMMISSION_TYPE)
            ->whereDate('end_date', $this->toDate)
            ->where('status', JobService::STATUS_QUEUED)
            ->first();

        if (!$status) {
            $status = new CommissionStatus();

            $status->commission_type = \App\Services\UnilevelCommission::COMMISSION_TYPE;
            $status->end_date = $this->toDate;
        }
        $status->status = JobService::EXECUTING_STATUS;

        $status->save();

        UnilevelCommissionManager::calculateCommission(
            $this->fromDate,
            $this->toDate
        );

        $status->status = JobService::STATUS_FINISHED;
        $status->save();
    }

    public function failed($e)
    {
        \Log::info($e->getMessage());
        $status = CommissionStatus::where('commission_type', \App\Services\UnilevelCommission::COMMISSION_TYPE)
            ->whereDate('end_date', $this->toDate)
            //->where('status', JobService::STATUS_QUEUED)
            ->first();

        if ($status) {
            $status->status = JobService::STATUS_FAILED;
            $status->save();
        }
    }
}
