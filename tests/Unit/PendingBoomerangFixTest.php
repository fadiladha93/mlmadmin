<?php


namespace Tests\Unit;

use App\Console\Commands\UpdateInventoryPendingTotals;
use App\User;
use Tests\TestCase;

class PendingBoomerangFixTest extends TestCase
{
    public function testSinglePendingBoomerangInventoryUpdate()
    {
        $testUser = User::where('id', 242)->first();
        $command = new UpdateInventoryPendingTotals();
        $command->processUser($testUser);
    }

    public function testUpdateInventoryPendingTotalsCommand()
    {
        $command = new UpdateInventoryPendingTotals();
        $command->handle();

        assert(true);
    }
}
