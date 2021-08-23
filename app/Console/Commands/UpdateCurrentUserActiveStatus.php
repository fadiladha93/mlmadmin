<?php

namespace App\Console\Commands;

use App\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class UpdateCurrentUserActiveStatus
 * @package App\Console\Commands
 */
class UpdateCurrentUserActiveStatus extends Command
{
    const MIN_QV_MONTH_VALUE = 100;
    const BATCH_SIZE = 100;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update:current_active_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update users active status';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // make sure we have time to execute the script.
        set_time_limit(0);

        $this->info('Start updating status');

        try {
            $this->updateProcess();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
        }

        $this->info('Finish');
    }

    protected function updateProcess()
    {
        DB::transaction(function () {
            // get the range with UTC timezone because the DB works using it as the default timezone
            $monthAgo = Carbon::now('UTC')->subDays(30)->format('Y-m-d');
            // for premium FC activate for 12 months from enrollment date
            $yearAgo = date('Y-m-d', strtotime("-1 Year"));
            // define date for active status for Turkey
            $currentTime = Carbon::now();
            $endActivePeriod = Carbon::parse('2019-09-01 00:00:00');
            $countryCode = 'TR';

            // set default values
            DB::statement("update users set is_active = 0;");
            // determine active users and switch it to the active status
            DB::statement("
                update users
                set is_active = 1
                where (
                    id in (select userid
                            from orders
                            where date(created_dt) >= :monthAgo
                            group by userid
                            having sum(orderqv) >= :minPqvValue
                    )
                )
                and account_status not in ('TERMINATED', 'SUSPENDED');
                ", [
                    'monthAgo' => $monthAgo,
                    'minPqvValue' => self::MIN_QV_MONTH_VALUE,
                ]
            );

            // for premium FC activate for 12 months from enrollment date
            DB::table('users')
                ->where('current_product_id', Product::ID_PREMIUM_FIRST_CLASS)
                ->where('created_dt', '>', $yearAgo)
                ->update([
                    'is_active' => 1
                ]);

            // set active status for Turkey
            if ($currentTime->lt($endActivePeriod)) {
                DB::statement("
                update users
                set is_active = 1
                where (
                    id in (select u.id
                            from users u
                            left join addresses a on u.id = a.userid
                            where u.country_code = :countryCode
                            or a.countrycode = :countryCode
                    )
                );
                ", [
                        'countryCode' => $countryCode,
                    ]
                );
            }

            // set always active status
            DB::table('users')
                ->whereIn('distid', [
                    'A1357703',
                    'A1637504',
                    'TSA9846698',
                    'TSA3564970',
                    'TSA9714195',
                    'TSA8905585',
                    'TSA2593082',
                    'TSA0707550',
                    'TSA9834283',
                    'TSA5138270',
                    'TSA8715163',
                    'TSA3516402',
                    'TSA8192292',
                    'TSA9856404',
                    'TSA1047539',
                    'TSA7594718',
                    'TSA0002566'
                ])
                ->update([
                    'is_active' => 1
                ]);
        });
    }
}
