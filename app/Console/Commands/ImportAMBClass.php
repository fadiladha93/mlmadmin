<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ImportAMBClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importAMBClass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import AMB Class';

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
        $filename = public_path() . '/csv/AMB Class-ibuumerang SOR commissions MARCH - JULY 2019 2.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                $x = 0;
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if (!empty($line) && $x > 0) {
                        $distid = trim($line[11]);
                        $created_date = trim($line[0]);
                        $paid_at = trim($line[1]);
                        $created_date = $paid_at;
                        $commission = trim($line[3]);
                        $commission = str_replace('$', '', $commission);
                        $cv = trim($line[4]);
                        $ncv = $this->getWholeNum($cv);

                        $bv = trim($line[5]);
                        $nbv = $this->getWholeNum($bv);
                        $qv = trim($line[6]);
                        $nqv = $this->getWholeNum($bv);

                        $reservation = trim($line[14]);
                        $checkUser = \App\User::select('*')->where('distid', $distid)->first();
                        if (!empty($checkUser) && !empty($distid)) {
                            $transactionId = 'AMB#' . $reservation;
                            $hasOrder = DB::table('orders')->where('trasnactionid', $transactionId)->count();
                            if ($hasOrder)
                                continue;
                            $order = DB::table('orders')->insert([
                                'userid' => $checkUser->id,
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
                                $order = DB::table('orders')->orderBy('id', 'desc')->limit(1)->first();
                                DB::table('orderItem')->insert([
                                    'orderid' => $order->id,
                                    'productid' => \App\Product::ID_TRAVEL_SAVING_BONUS,
                                    'quantity' => 1,
                                    'itemprice' => (float)$commission,
                                    'bv' => $ncv,
                                    'cv' => $ncv,
                                    'qv' => $nqv,
                                    'created_date' => date('Y-m-d', strtotime($created_date)),
                                    'created_time' => date('h:i:s', strtotime($created_date)),
                                    'created_dt' => date('Y-m-d h:i:s', strtotime($created_date)),
                                ]);
                            }
                        } else {
                            echo $distid . "  -  not found  \n";
                        }
                    }
                    $x++;
                }
            }
        }
    }
}
