<?php

namespace App\Jobs;

use App\Facades\RankManager;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Imtigger\LaravelJobStatus\Trackable;

/**
 * Class RankCalculation
 * @package App\Jobs
 */
class RankCalculation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /** @var Carbon */
    private $startDate;

    /** @var Carbon */
    private $endDate;

    /**
     * RankCalculation constructor.
     * @param Carbon $startDate
     * @param Carbon $endDate
     */
    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);

        RankManager::calculateRank($this->startDate, $this->endDate);
    }
}
