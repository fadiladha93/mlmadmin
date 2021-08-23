<?php

namespace App\Console\Commands;

use App\SubscriptionHistory;
use App\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class ChangeNextSubscriptionDateCommand
 * @package App\Console\Commands
 */
class ChangeNextSubscriptionDateCommand extends Command
{
    const TABLE_HEADER_ROW = 0;

    // csv-file rows order
    const USER_ID_ROW = 1;
    const AGENT_TSA_ROW = 2;
    const ORDER_ID_ROW = 3;
    const ORDER_ITEM_ID_ROW = 4;

    const FILE_PATH = './public/csv/adjustments/reset_duplicate_orders.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:change:subscription_date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for changing dates on the subsriptions of double-billed';

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
        set_time_limit(0);

        $this->info('Start changing subscription dates for users from csv-file');

        $errors = $this->process();

        foreach ($errors as $message) {
            $this->error($message);
        }

        $this->info('Finish');
    }

    /**
     * @return array
     */
    private function process()
    {
        $errors = [];

        $handle = fopen(self::FILE_PATH, 'r');
        if ($handle !== false) {
            $row = 0;
            while ($handle !== false) {
                try {
                    $rowData = fgetcsv($handle);

                    // data validation first
                    if ($row === self::TABLE_HEADER_ROW) {
                        $row++;
                        continue;
                    }

                    if ($rowData === false) {
                        break;
                    }

                    /** @var User $user */
                    $user = DB::table('users')->find($rowData[self::USER_ID_ROW]);

                    if (!$user) {
                        continue;
                    }

                    $doubleSubscription = DB::table('subscription_history')
                        ->where('user_id', $user->id)
                        ->whereBetween('attempted_date', ['2019-07-01', '2019-07-31'])
                        ->where('status', 1)
                        ->whereDate('next_attempt_date', '>=', '2019-08-01')
                        ->whereNull('is_reactivate')
                        ->get();

                    if ($doubleSubscription->isEmpty()) {
                        continue;
                    }


                    if (count($doubleSubscription) > 1) {
                        $mainSubscription = $doubleSubscription->filter(function ($subscription) {
                            return $subscription->attempted_date >= '2019-07-01' && $subscription->next_attempt_date <= '2019-08-31';
                        })->first();

                        if (!$mainSubscription) {
                            continue;
                        }

                        DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'next_subscription_date' => $mainSubscription->next_attempt_date
                            ]);
                    }

                    unset($rowData);
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }

                $row++;
            }
        }

        fclose($handle);

        return $errors;
    }
}
