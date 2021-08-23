<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SorDisableForNotAgreedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SorDisableForNotAgreedUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SorDisableForNotAgreedUsers';

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
        $agreed_users = \App\ProductTermsAgreement::select('*')->where('agree_sor', 1)->get()->pluck('user_id')->toArray();
        $agreed_users[] = 0;
        $sor_accounts = \App\SaveOn::select('*')
            ->whereNull('disable_process')
            ->whereNotIn('user_id', $agreed_users)
//            ->where('user_id', '17954')
            ->limit(300)
            ->get();
        foreach ($sor_accounts as $sor_account) {
            $userDetail = \App\User::getById($sor_account->user_id);
            if (empty($userDetail))
                continue;
            echo $userDetail->id . " - " . $userDetail->distid . "\n";
            try {
                $disabledUserRsponse = \App\SaveOn::disableUser($userDetail->current_product_id, $userDetail->email, $userDetail->distid, \App\SaveOn::USER_ACCOUNT_REST);
                if ($disabledUserRsponse['status'] == 'success' && $disabledUserRsponse['disabled'] == 'true') {
                    \App\SaveOn::where('sor_user_id', $sor_account->sor_user_id)
                        ->update([
                            'status' => \App\SaveOn::DEACTIVE,
                            'note' => \App\SaveOn::USER_ACCOUNT_REST,
                            'disable_process' => 'disabled',
                        ]);
                    $msg = \App\SaveOn::USER_DEACTIVATED_SUCCESSFULLY;
                    echo $msg . "\n";
                } else {
                    if (!empty($disabledUserRsponse) && !empty($disabledUserRsponse['msg']) && $disabledUserRsponse['msg'] == 'Unable to find user matching email or contract number or otherid') {
                        \App\SaveOn::where('sor_user_id', $sor_account->sor_user_id)
                            ->update([
                                'disable_process' => $disabledUserRsponse['msg'],
                            ]);
                        echo $disabledUserRsponse['msg'] . "\n";
                    } else if (!empty($disabledUserRsponse) && !empty($disabledUserRsponse['disabled']) == 'The email address is invalid') {
                        \App\SaveOn::where('sor_user_id', $sor_account->sor_user_id)
                            ->update([
                                'disable_process' => $disabledUserRsponse['disabled'],
                            ]);
                        echo $disabledUserRsponse['disabled'] . "\n";
                    } else {
                        print_r($disabledUserRsponse);
                        die;
                    }
                }
            } catch (\Exception $ex) {
                echo $ex->getMessage() . "\n";
                exit;
            }
            echo "\n";
        }
    }
}
