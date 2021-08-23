<?php

namespace App\Console\Commands\CommissionPayment;

use DB;
use Illuminate\Console\Command;

class PayTSBCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:payTSBCommission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'payTSBCommission description';

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
        $commissions = DB::table('tsb_commission')->get();
        foreach ($commissions as $commission) {
            if ($commission->status == 'pending') {
                echo $commission->user_id . "\n";
                //check ewllet
                $transactionId = \App\EwalletTransaction::addPurchase($commission->user_id, \App\EwalletTransaction::TYPE_TSB_COMMISSION, $commission->amount, 0, 'TSB Bonus Mar - July');
                if ($transactionId) {
                    DB::table('tsb_commission')->where('id', $commission->id)->update(['status' => 'paid']);
                } else {
                    DB::table('tsb_commission')->where('id', $commission->id)->update(['status' => 'fail']);
                }
            }
        }
    }
}
