<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class TerminatedUserSitesDeactivate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deactivate_saveon_for_terminated_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = DB::table('users')
            ->join('sor_tokens', 'users.id', '=', 'sor_tokens.user_id')
            ->select('users.*', 'sor_tokens.status as s_status')
            ->where('users.account_status', 'TERMINATED')
            ->where('sor_tokens.status', '!=', 0)
            ->get();
        foreach ($users as $user) {
            $response = \App\Helper::deActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, \App\SaveOn::USER_DISABLE_FOR_SUBSCRIPTION_FAIL);
            if (!empty($response)) {
                echo $user->distid . " - " . $response['msg'] . "\n";
            }
        }
        echo "Done";
    }
}
