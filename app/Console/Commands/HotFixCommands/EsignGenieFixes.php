<?php


namespace App\Console\Commands\HotFixCommands;

use Illuminate\Console\Command;
use DB;
use App\User;

//use PDO;
//use App\Address;
//use App\BoomerangInv;
//use App\Helper;
//use App\Order;
//use App\OrderItem;
//use App\PaymentMethod;
//use App\PaymentMethodType;
//use App\Product;
//use App\Subscription;
//use App\UserType;

class EsignGenieFixes extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:EsignGenieFix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This imports and sets users is_tax_confirmed to 1';

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
     * @param $filePath
     * @return array
     */
    private function parseCsv($filePath)
    {
        $csv = array_map('str_getcsv', file($filePath));

        array_walk($csv, function (&$a) use ($csv) {
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

        $signed_docs_to_do = $this->parseCsv(public_path() . '/BitJar_Labs_Completed_Folders.csv');

        foreach ($signed_docs_to_do as $row) {

            $distid = trim($row['DistID']);
            echo $this->make_response_output('--------------------------------- :', $distid);
            $this->set_user_tax_status($distid);
            echo $this->make_response_output('Tax Flag', 'set');

        }

    }

    public function set_user_tax_status($distid, $set_flag = 1)
    {
        $user = User::where('distid', $distid);
        $user->update(['is_tax_confirmed' => $set_flag]);
    }

    public function make_response_output($title, $data, $newline = true)
    {

        return '<pre><strong>' . $title . ":</strong> " . $data . (($newline) ? "" . PHP_EOL : '') . "</pre>";
    }


}
