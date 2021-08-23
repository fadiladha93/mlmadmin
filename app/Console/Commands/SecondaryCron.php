<?php

namespace App\Console\Commands;

use App\Services\SecondarySubscriptionCronService;
use Illuminate\Console\Command;

class SecondaryCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'once:secondarycron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One off secondary cron - do not run again';

    public function handle()
    {
        $cron = new SecondarySubscriptionCronService();
        $cron->run();
    }

}
