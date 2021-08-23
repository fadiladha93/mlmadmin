<?php

namespace App\Jobs;

use App\BinaryCommissionHistory;
use App\Facades\BinaryCommissionManager;
use App\Models\CommissionStatus;
use App\Services\BinaryCommissionService;
use App\Services\JobService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Imtigger\LaravelJobStatus\Trackable;

/**
 * Class BinaryCommission
 * @package App\Jobs
 */
class BinaryCommission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /** @var int  */
    public $tries = 1;

    /** @var Carbon */
    protected $startDate;

    /** @var Carbon */
    protected $endDate;

    /** @var BinaryCommissionHistory */
    protected $commission;

    /** @var boolean */
    protected $isRecalculate;

    /**
     * BinaryCommission constructor.
     * @param $startDate
     * @param $endDate
     * @param bool $isRecalculate
     */
    public function __construct($startDate, $endDate, $isRecalculate = false)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->isRecalculate = $isRecalculate;

        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = CommissionStatus::where('commission_type', BinaryCommissionService::COMMISSION_TYPE)
            ->whereDate('end_date', $this->endDate)
            ->where('status', JobService::STATUS_QUEUED)
            ->first();

        if (!$status) {
            $status = new CommissionStatus();

            $status->commission_type = BinaryCommissionService::COMMISSION_TYPE;
            $status->end_date = $this->endDate;
        }

        $status->status = JobService::EXECUTING_STATUS;

        $status->save();

        BinaryCommissionManager::calculateCommission($this->startDate, $this->endDate, $this->isRecalculate);

        $status->status = JobService::STATUS_FINISHED;
        $status->save();
    }

    public function failed()
    {
        $status = CommissionStatus::where('commission_type', BinaryCommissionService::COMMISSION_TYPE)
            ->whereDate('end_date', $this->endDate)
            ->where('status', JobService::STATUS_QUEUED)
            ->first();

        if ($status) {
            $status->status = JobService::STATUS_FAILED;
            $status->save();
        }
    }

}
