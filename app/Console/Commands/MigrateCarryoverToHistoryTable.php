<?php

namespace App\Console\Commands;

use App\BinaryCommissionCarryoverHistory;
use App\BinaryCommissionHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class MigrateCarryoverToHistoryTable
 * @package App\Console\Commands
 */
class MigrateCarryoverToHistoryTable extends Command
{
    const BATCH_SIZE = 100;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:carryover:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate current user carryover to Carryover History table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start create binary commission history');

        $data = [];

        $commissions = DB::table('binary_commission')
            ->select('week_ending')
            ->groupBy('week_ending')
            ->orderBy('week_ending', 'asc')
            ->get();

        foreach ($commissions as $commission) {
            $endDate = $commission->week_ending;

            $startDate = Carbon::parse($endDate)->subDay(6)->startOfDay();

            $data[] = [
                'start_date' => $startDate,
                'end_date' => $endDate
            ];
        }

        BinaryCommissionHistory::insert($data);

        $this->info('Binary commission history was created successfully');

        $this->info('Start migrate current user carryover to Carryover History table');

        $lastCommission = BinaryCommissionHistory::all()->last();

        DB::table('users')->orderBy('id')->chunk(self::BATCH_SIZE, function ($users) use ($lastCommission) {
            foreach ($users as $user) {
                $carryover = new BinaryCommissionCarryoverHistory();
                $carryover->user_id = $user->id;
                $carryover->right_carryover = $user->current_right_carryover;
                $carryover->left_carryover = $user->current_left_carryover;
                $carryover->commissionHistory()->associate($lastCommission);

                $carryover->save();
            }
        });

        $this->info('Finish');
    }
}
