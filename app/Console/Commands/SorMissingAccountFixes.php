<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class SorMissingAccountFixes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SorMissingAccountFixes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command SOR Missing Account Fixes';

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
        $users = DB::select(DB::raw("
        select users.distid from product_terms_agreement 
left join sor_tokens on product_terms_agreement.user_id = sor_tokens.user_id
join users on product_terms_agreement.user_id = users.id
where product_terms_agreement.agree_sor = 1 
and users.distid IN ('TSA7345266','TSA1745547','TSA9345769','TSA0544233') 
and sor_tokens.sor_user_id is null
"));

        $users = DB::select(DB::raw("select users.id,distid,users.email,username,firstname,lastname,account_status from users 
left join sor_tokens on users.id = sor_tokens.user_id 
where users.is_tv_user = 0 and usertype=2 and sor_tokens.sor_user_id is null order by users.id asc 
"));
        $contructNumbers = [];
        foreach ($users as $user) {
            $contructNumbers[] = ["ContractNumber" => $user->distid];
        }
        $responses = \App\SaveOn::searchSorUser($contructNumbers, 1);
//        file_put_contents("missing-found-sor.json", $responses);
//        $responses = json_decode($responses);
        die;
        $responses = [];
        if (empty($responses)) {
            foreach ($contructNumbers as $contructNumber) {
                if (!empty($contructNumber['ContractNumber'])) {
                    $user = \App\User::getByDistId(trim($contructNumber['ContractNumber']));
                    if (!empty($user) && $user->is_tv_user == 0) {
                        echo $user->id . " " . $user->distid . "\n";
                        $userAddress = \App\Address::getRec($user->id, \App\Address::TYPE_REGISTRATION);
                        if (empty($userAddress)) {
                            $userAddress = \App\Address::getRec($user->id, \App\Address::TYPE_BILLING);
                            if (empty($userAddress)) {
                                echo "Address information is missing\n";
                                continue;
                            }
                        }
                        $sorRes = \App\SaveOn::SORCreateUser($user->id, $user->current_product_id, $userAddress);
                        $lastId = \App\Helper::logApiRequests($user->id, 'SOR - createNewSORAccount', config('api_endpoints.SORCreateUser'), $sorRes['request']);
                        \App\Helper::logApiResponse($lastId->id, json_encode($sorRes['response']));
                        $sorResponse = $sorRes['response'];
                        if (isset($sorResponse->Account) && isset($sorResponse->Account->UserId)) {
                            $request = $sorRes['request'];
                            \App\SaveOn::insert(['api_log' => null, 'user_id' => $user->id, 'product_id' => $user->current_product_id, 'sor_user_id' => $sorResponse->Account->UserId, 'sor_password' => $request['Password'], 'status' => 1]);
                            echo "Success\n";
                        } else {
                            echo "Error when create new SOR<br/>Error: " . $sorResponse->Message . "\n";
                        }
                        echo "\n\n";
                    }
                }
            }
        } else {
            foreach ($responses as $reponse) {
                $distid = $reponse->ContractNumber;
                if (!empty($distid)) {
                    $user = \App\User::getByDistId($distid);
                    if (!empty($user)) {
                        echo $user->distid . " - " . $user->current_product_id . "\n";
                        $hasSorRec = \App\SaveOn::select('*')->where('user_id', $user->id)->first();
                        if (empty($hasSorRec)) {
                            if ($user->current_product_id == 4) {
                                $clubId = 12719;
                                $productId = 4;
                            } else {
                                $clubId = $reponse->VacationClubId;
                                $productId = \App\SaveOn::getProductIdByClubId($clubId);
                            }
                            if (!empty($clubId)) {
                                if (!empty($productId)) {
                                    print_r([
                                        'user_id' => $user->id,
                                        'status' => ($reponse->Status == 'Active' ? 1 : 0),
                                        'sor_user_id' => $reponse->UserID,
                                        'product_id' => $productId,
                                    ]);
                                    \App\SaveOn::insert([
                                        'user_id' => $user->id,
                                        'status' => ($reponse->Status == 'Active' ? 1 : 0),
                                        'sor_user_id' => $reponse->UserID,
                                        'product_id' => $productId,
                                    ]);
                                } else {
                                    echo "Product not found\n";
                                }
                            } else {
                                echo "Club not found\n";
                            }
                        } else {
                            echo "Sor account already exists\n";
                        }
                    } else {
                        echo "User not found \n";
                    }
                } else {
                    echo "Contract number not found \n";
                }

                echo "\n\n";
            }
        }
    }

}
