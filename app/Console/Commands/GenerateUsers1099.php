<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Address;
use App\EwalletTransaction;
use File;
use Log;
use DB;

class GenerateUsers1099 extends Command
{

    const BATCH_SIZE = 100;
    private $payer;
    private $payer_ein;
    private $fdf_template;
    private $folder_path;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:generate1099';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate 1099 for all the US Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        setlocale(LC_MONETARY, 'en_US');
        $this->payer        = "iBuumerang, LLC\n11807 Westheimer Rd #550-427\nHouston, TX 77001";
        $this->payer_ein    = "83-3578179";
        // $this->folder_path  = "/home/ibuumerang/1099";
        $this->folder_path  = "/home/ubuntu/1099";
        // Load FDF Template
        $this->fdf_template = file_get_contents(storage_path("/1099_lib/1099.fdf"));
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Update Users table if they are citiens
        Log::info('Checking Users\' Citizenship...');
        $this->VerifyCitizenship();

        Log::info('Getting Users to write the PDF');
        $this->loadUsers();
    }

    /*
    * function will check who are the users we should generate the 1099
    * Set flag on users table on field is_us_citizen
    */
    private function VerifyCitizenship(){
        //Update all Users to 1 on is_us_citizen
        User::where('country_code', '=', 'US')
        ->update(['is_us_citizen' => 1]);

        //Update all Users to 0 on is_us_citizen
        User::where('country_code', '<>', 'US')
        ->update(['is_us_citizen' => 0]);
    }

    /*
    * Call this to write 1099 for all the users
    *
    */
    private function loadUsers(){
        $numUsers = User::where('country_code', '=', 'US')->count();
        $numBatches = ceil($numUsers / static::BATCH_SIZE);
        $showEveryThisAmount = ceil(static::BATCH_SIZE / 10);

        $path = storage_path()."/1099_export/";
        File::makeDirectory($path, $mode = 0777, true, true);
        
        $file = storage_path().'/1099_export/1099.csv';
        unlink($file);
        
        $fp = fopen($file, 'a');
        
        $header = array(
            "id",
            "tsa",
            "name",
            "company name",
            "address",
            "city",
            "state",
            "zipcode",
            "ssn",
            "ein",
            "total"
        );
        
        fputcsv($fp, $header);
        
        //for testing purposes
        // User::where('firstname', '=', 'Elite')
        User::where('country_code', '=', 'US')
            ->orderBy('id', 'DESC')
            ->chunk(static::BATCH_SIZE, function ($users, $page) use ($numBatches,
            $numUsers,
            $showEveryThisAmount,
            $fp) {
            $counter = 0;

            foreach ($users as $user) {
                $address = Address::where('userid', $user->id)
                ->where('addrtype',3)->first();
                if($address){
                    // Log::info('Processing user...', ['user' => $user->username, 'address' => $address->address1]);
                    $address_string = $address->address1 ." ".$address->address2;
                    $city_string    = $address->city.", ".$address->stateprov.", ".$address->postalcode;
                    $city           = $address->city;
                    $state          = $address->stateprov;
                    $zip            = $address->postalcode;
                }else{
                    $address_string = "";
                    $city_string    = "";
                    $city           = "";
                    $state          = "";
                    $zip            = "";
                }
                
                $ssn = "Not Found";
                $ein = "Not Found";
                if(strlen($user->ssn) > 8){
                    $ssn = $this->parseSSN($user->ssn);
                }
                if(strlen($user->ein) > 8){
                    $ssn = $this->parseEIN($user->ein);
                }
                $amount = $this->getAmount($user->id);
                
                if($amount>600){
                    $fields = array (
                        $user->id,
                        strtoupper($user->distid),
                        strtoupper($user->firstname." ".$user->lastname),
                        strtoupper($user->business_name),
                        strtoupper($address_string),
                        strtoupper($city),
                        strtoupper($state),
                        strtoupper($zip),
                        strtoupper($ssn),
                        strtoupper($ein),
                        money_format('%i', $amount)
                    );
                    
                    fputcsv($fp, $fields);
                    
                    //$this->writePDF($user, $address_string, $city_string, $amount);
                }
            }
        });
        fclose($fp);
    }

    /*
    *
    */
    private function writePDF($user, $address_string, $city_string, $amount){
        $pdf_fields = [
            '/\bFIELD_1\b/',
            '/\bFIELD_2\b/',
            '/\bFIELD_3\b/',
            '/\bFIELD_4\b/',
            '/\bFIELD_5\b/',
            '/\bFIELD_6\b/',
            '/\bFIELD_7\b/',
            '/\bFIELD_8\b/', //Payer
            '/\bFIELD_9\b/', //Payer EIN
            '/\bFIELD_10\b/', //Recepient EIN
            '/\bFIELD_11\b/', //Recepient Name
            '/\bFIELD_12\b/', //Recepient Address
            '/\bFIELD_13\b/', //Recepient State
            '/\bFIELD_14\b/', //Recepient Account/TSA
            '/\bFIELD_15\b/',
            '/\bFIELD_16\b/',
            '/\bFIELD_17\b/', //Nonemployee Compensation
            '/\bFIELD_18\b/',
            '/\bFIELD_19\b/',
            '/\bFIELD_20\b/',
            '/\bFIELD_21\b/',
            '/\bFIELD_22\b/',
            '/\bFIELD_23\b/',
            '/\bFIELD_24\b/',
            '/\bFIELD_25\b/',
            '/\bFIELD_26\b/',
            '/\bFIELD_27\b/'

        ];
        // $pdf_fields = ['/\bFIELD_1\b/','/\bFIELD_2\b/','/\bFIELD_3\b/','/\bFIELD_4\b/','/\bFIELD_5\b/'];

        // Log::info("User id ".$user->id." amount: ".$amount." --- amount parsed: ".money_format('%i', $amount));
        $repl_values = [
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            strtoupper($this->payer),
            strtoupper($this->payer_ein),
            strtoupper($this->parseSSN($user->ssn)),
            strtoupper($user->firstname." ".$user->lastname),
            strtoupper($address_string),
            strtoupper($city_string),
            strtoupper($user->distid),
            "",
            "",
            "".money_format('%i', $amount)."",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            ""
        ];

        //Replace Info into FDF
        $fdf = preg_replace($pdf_fields, $repl_values, $this->fdf_template);

        // Creating a temporary file for our FDF file.
        $FDFfile = tempnam($this->folder_path."/lib", "fdf");

        file_put_contents($FDFfile, $fdf);

        // Merging the FDF file with the raw PDF form
        exec("pdftk ".$this->folder_path."/lib/ambassador1099.pdf fill_form $FDFfile output ".$this->folder_path."/users/1099_".$user->distid.".pdf flatten");

        // Removing the FDF file as we don't need it anymore
        unlink($FDFfile);
    }

    /*
    * Call this to format SSN
    */
    private function parseSSN($ssn){
        $first3 = substr($ssn, 0, 3);
        $mid2   = substr($ssn, 3, 2);
        $last4  = substr($ssn, -4);
        return $first3."-".$mid2."-".$last4;
    }
    
    /*
    * Call this to format EIN
    */
    private function parseEIN($ein){
        $first2 = substr($ein, 0, 2);
        $last  = substr($ein, 2);
        return $first2."-".$last;
    }

    /*
    * Get the sum of all the deposits from the ewallet
    */
    private function getAmount($user_id){
        $sum = EwalletTransaction::select(DB::raw('SUM (amount) AS total'))
        ->where('user_id', $user_id)
        ->where('type','DEPOSIT')
        ->whereBetween('created_at', ['2019-01-01 00:00:00', '2019-12-31 23:59:59'])
        ->first();
        return money_format('%i', $sum->total);
    }
}
