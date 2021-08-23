<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class CloneAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CloneAddress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CloneAddress';

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
        //all address
        $addresses = DB::table('addresses')->where('addrtype', \App\Address::TYPE_BILLING)->get();
        foreach ($addresses as $address) {
            if (!empty($address->countrycode)) {
                echo $address->userid . "\n";
                $primaryAddress = DB::table('addresses')
                    ->where('userid', $address->userid)
                    ->where('addrtype', \App\Address::TYPE_REGISTRATION)
                    ->where('primary', 1)
                    ->first();
                if (empty($primaryAddress)) {
                    //create
                    $req = new \stdClass();
                    $req->address1 = $address->address1;
                    $req->address2 = $address->address2;
                    $req->city = $address->city;
                    $req->stateprov = $address->stateprov;
                    $req->postalcode = $address->postalcode;
                    $req->countrycode = $address->countrycode;
                    $req->apt = $address->apt;
                    $id = \App\Address::addNewRecSecondaryAddressTvUser($address->userid, \App\Address::TYPE_REGISTRATION, 1, $req);
                    echo "Primary address :- " . $id . "\n";
                }
                $shippingAddress = DB::table('addresses')
                    ->where('userid', $address->userid)
                    ->where('addrtype', \App\Address::TYPE_SHIPPING)
                    ->where('primary', 1)
                    ->first();
                if (empty($shippingAddress)) {
                    //create
                    $req = new \stdClass();
                    $req->address1 = $address->address1;
                    $req->address2 = $address->address2;
                    $req->city = $address->city;
                    $req->stateprov = $address->stateprov;
                    $req->postalcode = $address->postalcode;
                    $req->countrycode = $address->countrycode;
                    $req->apt = $address->apt;
                    $id = \App\Address::addNewRecSecondaryAddressTvUser($address->userid, \App\Address::TYPE_SHIPPING, 1, $req);
                    echo "shipping address :- " . $id . "\n";
                }
                echo "\n";
            }
        }
    }
}
