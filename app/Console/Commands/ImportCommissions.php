<?php

namespace App\Console\Commands;

use App\Order;
use App\Product;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ImportCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importCommissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import Commissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->addArgument('date', InputArgument::REQUIRED);
    }

    private function parseCsv($filePath)
    {
        $csv = array_map('str_getcsv', file($filePath));

        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        array_shift($csv);

        return $csv;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);

        $date = $this->argument('date');
        $commissions = $this->parseCsv(public_path() . '/commissions.csv');


        foreach ($commissions as $commission) {
            $distId = trim($commission['ContractNumber']);
            $amount = str_replace('$', '', trim($commission['Amount']));
            $cv = str_replace('$', '', trim($commission['CV']));

            $this->createOrder($distId, $amount, $cv, $date);
        }
    }

    private function createOrder($distId, $amount, $cv, $date)
    {
        $cv = ceil($cv);
        $qv = ceil($amount);

        $user = User::getByDistId($distId);

        if (!$user) {
            return;
        }

        $order = new Order();

        $order->fill([
            'userid' => $user->id,
            'ordersubtotal' => (float)$amount,
            'ordertotal' => (float)$amount,
            'ordercv' => $cv,
            'orderbv' => 0,
            'orderqv' => $qv,
            'payment_methods_id' => null,
            'shipping_address_id' => null,
            'statuscode' => 1,
            'processed' => false,
            'created_date' => date('Y-m-d', strtotime($date)),
            'created_time' => '12:00:00',
            'created_dt' => date('Y-m-d 12:00:00', strtotime($date)),
        ]);

        $order->save();

        $order->orderItems()->create([
            'productid' => Product::ID_TRAVEL_SAVING_BONUS,
            'quantity' => 1,
            'itemprice' => (float)$amount,
            'bv' => 0,
            'cv' => $cv,
            'qv' => $qv,
            'created_date' => date('Y-m-d', strtotime($date)),
            'created_time' => '12:00:00',
            'created_dt' => date('Y-m-d 12:00:00', strtotime($date)),
        ]);
    }
}
