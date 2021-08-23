<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $subscriptionsBySchedule = env('SUBSCRIPTION_CRON_SCHEDULE', true);

        if ($subscriptionsBySchedule) {
            $schedule->command('command:subscription')->dailyAt('2:00')->withoutOverlapping();
        }

        $ranksBySchedule = env('RANKS_SCHEDULE', true);

        if ($ranksBySchedule) {
            // calculate ranks by both QV and QC (daily at 06:00, 18:00, 12:00, 23:59)
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('3:00');
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('6:00');
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('12:00');
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('15:00');
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('18:00');
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('22:00');
            $schedule->command('ranks:calculate')->timezone('America/Chicago')->dailyAt('23:59');
        }

        // Mailgun commands
        $schedule->command('command:update_mailgun_maillist')->hourly();
        $schedule->command('command:add_to_mailgun_mail_list')->hourly();
        // $schedule->command('command:SorDisableForNotAgreedUsers')->everyFiveMinutes();


        // TODO: this commands should be reviewed
        $schedule->command('command:field_watch_export')->dailyAt('1:00')->withoutOverlapping();
        $schedule->command('command:boomerang_expired_to_inv')->daily();
        $schedule->command('command:set_binary_qualification')->dailyAt('5:00')->withoutOverlapping();

        // check user history every day at 0:30 but save history only for sunday and end of month dates
        $schedule->command('users:update:current_active_status')->everyThirtyMinutes();

        $activityBySchedule = env('ACTIVITY_SCHEDULE', true);

        if ($activityBySchedule) {
            $schedule->command('users:set:activity_history')
                ->timezone('America/Chicago')
                ->dailyAt('0:30');
        }

        // run every Monday at 01:30 (Central Time)
        $schedule->command('command:calculate_binary_commission')
            ->timezone('America/Chicago')
            ->weeklyOn(1, '1:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
