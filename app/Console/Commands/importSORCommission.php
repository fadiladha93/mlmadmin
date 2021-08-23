<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ImportSORCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importSORCommission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import SOR Commission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function getWholeNum($val)
    {
        $nval = 0;
        if ($val > 0) {
            $val = explode('.', $val);
            if (!empty($val[1]) && $val[1] > 5) {
                $nval = $val[0] + 1;
            } else {
                if ($val[0] == 0) {
                    $nval = 1;
                } else {
                    $nval = $val[0];
                }
            }
        }
        return $nval;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $filename = public_path() . '/csv/Boomerangs Class-ibuumerang SOR commissions MARCH - JULY 2019.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if (!empty($line)) {
                        $created_date = trim($line[0]);
                        $paid_at = trim($line[1]);
                        $created_date = $paid_at;
                        $created_date = date('Y-m-d h:i:s');
                        $refering_user_id = trim($line[12]);
                        if (empty($refering_user_id))
                            continue;
                        $commission = trim($line[3]);
                        $commission = str_replace('$', '', $commission);
                        $cv = trim($line[4]);
                        $ncv = $this->getWholeNum($cv);
                        $bv = trim($line[5]);
                        $nbv = $this->getWholeNum($bv);
                        $qv = trim($line[6]);
                        $nqv = $this->getWholeNum($qv);
                        $tsbamount = trim($line[7]);
                        $tsbamount = str_replace('$', '', $tsbamount);
                        $reservation = trim($line[14]);
                        $checkUser = \App\SaveOn::select('*')->where('sor_user_id', $refering_user_id)->first();
                        if (!empty($checkUser)) {
                            $transactionId = 'SOR#' . $reservation;
                            $hasOrder = DB::table('orders')->where('trasnactionid', $transactionId)->count();
                            if ($hasOrder)
                                continue;


                            $order = DB::table('orders')->insert([
                                'userid' => $checkUser->user_id,
                                'trasnactionid' => $transactionId,
                                'ordersubtotal' => (float)$commission,
                                'ordertotal' => (float)$commission,
                                'ordercv' => $ncv,
                                'orderbv' => $nbv,
                                'orderqv' => $nqv,
                                'payment_methods_id' => null,
                                'shipping_address_id' => null,
                                'statuscode' => 1,
                                'processed' => false,
                                'created_date' => date('Y-m-d', strtotime($created_date)),
                                'created_time' => date('h:i:s', strtotime($created_date)),
                                'created_dt' => date('Y-m-d h:i:s', strtotime($created_date)),
                            ]);
                            if ($order) {
                                $lastOrder = DB::table('orders')->orderBy('id', 'desc')->first();
                                DB::table('orderItem')->insert([
                                    'orderid' => $lastOrder->id,
                                    'productid' => \App\Product::ID_TRAVEL_SAVING_BONUS,
                                    'quantity' => 1,
                                    'itemprice' => (float)$commission,
                                    'bv' => $nbv,
                                    'cv' => $ncv,
                                    'qv' => $nqv,
                                    'created_date' => date('Y-m-d', strtotime($created_date)),
                                    'created_time' => date('h:i:s', strtotime($created_date)),
                                    'created_dt' => date('Y-m-d h:i:s', strtotime($created_date)),
                                ]);
                                DB::table('tsb_commission')->insert([
                                    'order_id' => $lastOrder->id,
                                    'user_id' => $checkUser->user_id,
                                    'dist_id' => $checkUser->user_id,
                                    'amount' => $tsbamount,
                                    'calculation_date' => date('2019-09-20 00:00:00'),
                                    'start_date' => date('2019-08-01 00:00:00'),
                                    'end_date' => date('2019-08-31 00:00:00'),
                                    'status' => 'pending',
                                    'memo' => 'TSB Bonus Mar - July'
                                ]);
                            }
                        } else {
                            echo $refering_user_id . "  -  not found  \n";
                        }
                    }
                }
            }
        }

    }
}
