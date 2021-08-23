<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Imtigger\LaravelJobStatus\Trackable;

/**
 * Class RecalculateCommissions
 * @package App\Jobs
 */
class RecalculateCommissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /**
     * @var array
     */
    protected $executedJobs;

    /**
     * Create a new job instance.
     *
     * @param array $executedJobs
     */
    public function __construct(array $executedJobs)
    {
        $this->executedJobs = $executedJobs;

        $this->prepareStatus();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->chain($this->executedJobs);
    }
}
