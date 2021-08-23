<?php


namespace Tests\Unit;

use App\Logging\BuumLogger;
use Tests\TestCase;

class BuumLoggerTest extends TestCase
{
    public function testLogToConsole()
    {
        (new BuumLogger('Ranks Calculation Started', BuumLogger::LOG_TYPE_INFO))->toConsole();
    }

    public function testLogToAll()
    {
        (new BuumLogger('Ranks Calculation Started', BuumLogger::LOG_TYPE_INFO))->toAll();
    }

    public function testLogToSlack()
    {
        (new BuumLogger('Ranks Calculation Started', BuumLogger::LOG_TYPE_INFO))->toSlack();
    }

    public function testRanksJobsLogging()
    {
        try {
            $startDate = \Carbon\Carbon::create('2020', '01', '01');
            $endDate   = \Carbon\Carbon::create('2020', '01', '31');
            $calculateRanks = new \App\Jobs\RankCalculation($startDate, $endDate);
            $calculateRanks->handle();
        } catch (\Exception $ex) {
            // do nothing
        }
    }
}
