<?php

namespace App\Console\Commands;

use App\BoomerangInv;
use App\BoomerangTracker;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateInventoryPendingTotals extends Command
{
    const BATCH_PROCESSING_NUMBER = 100;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-inventory-pending-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command calculates the boomerang pending totals
    from the boomerang tracker table and updates the inventory pending totals table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currentCount = 1;
        $userCount    = User::count();
        \Log::info("Started inventory pending totals update for $userCount users \n");

        User::chunk(self::BATCH_PROCESSING_NUMBER, function ($users) use ($userCount, $currentCount) {
            foreach ($users as $user) {
                $this->processUser($user, $userCount, $currentCount);
            }
        });

        \Log::info("Finished inventory pending totals update for $userCount  users \n");
    }

    public function processUser($user, $userCount = 1, &$currentCount = 1)
    {
        \Log::info('Processing user ' . $currentCount++ . ' of ' . $userCount);

        if (\DB::table('tmpinventorypendingtotals')->where('user_id', $user->id)->first()) {
            \Log::info("User has previously been processed. Skipping... \n");
            return;
        }

        \Log::info('Processing pending inventory update for user #' . $user->id);

        if (!$boomerangInventory = BoomerangInv::where('userid', $user->id)->first()) {
            \Log::critical('No boomerang inventory record found for user #' . $user->id);
            return;
        }

        $today = date('Y-m-d');
        $boomerangTrackers = BoomerangTracker::query()
            ->where('userid', $user->id)
            ->where('is_used', 0)
            ->where('exp_dt', '>', $today)
            ->get();

        $currentPendingCount = 0;
        foreach ($boomerangTrackers as $boomerangTracker) {
            if ($boomerangTracker->mode == 1) {
                $currentPendingCount++;
                continue;
            }

            $currentPendingCount += (int)$boomerangTracker->group_available;
        }

        \Log::info('Previous pending count for user #' . $user->id . ' is ' . $boomerangInventory->pending_tot);
        \Log::info('Calculated pending count for user #' . $user->id . ' is ' . $currentPendingCount);

        $oldPendingTotal = !empty($boomerangInventory->pending_tot) ? (int)$boomerangInventory->pending_tot : 0;

        \DB::table('tmpinventorypendingtotals')->insert([
            'user_id' => $user->id,
            'boomerang_inventory_id' => $boomerangInventory->id,
            'old_pending_total' => $oldPendingTotal,
            'new_pending_total' => $currentPendingCount
        ]);

        $boomerangInventory->pending_tot = $currentPendingCount;
        $boomerangInventory->save();

        \Log::info('Pending total update complete for user #' . $user->id . "\n");
    }
}
