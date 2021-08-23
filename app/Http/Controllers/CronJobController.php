<?php

namespace App\Http\Controllers;

use DB;

class CronJobController extends Controller {

    public function __construct() {
        set_time_limit(0);
    }

    public function run($type) {
        if ($type == "import-customer") {
            //$this->importCusomter();
        } else if ($type == "re-run-subscription") {
//            $this->fixingAgreedIdecideUsers();
        }else if ($type == "fixing-agreed-idecide-users") {
//            $this->fixingAgreedIdecideUsers();
        } else if ($type == "fixing-boomerangs") {
//            $this->fixingBoomerangs();
        } else if ($type == "sites-deactivate-for-terminated-users") {
//            $this->sitesDeactivateForTerminatedUsers();
        } else if ($type == "import-alis-group-csv") {
//            $this->importAlisGroupCSV();
        } else if ($type == "update-csv-subscription-orders") {
//            $this->updateCsvSubscriptionOrders();
        } else if ($type == "test-site-deactivate") {
//            $response = \App\Helper::deActivateSaveOnUser(21250, 3, "TSA7721250", 'De activate user');
//            print_r($response);
//            $response = \App\Helper::reActivateSaveOnUser(21250, 3, "TSA7721250", 'Re activate user');
//            print_r($response);

//            $response = \App\Helper::deActivateIdecideUser(24159);
//            $response = \App\Helper::reActivateIdecideUser(24159);
//            print_r($response);

        } else if ($type == "sync-saveon-users") {
//            $this->syncWithSOR();
        } else if ($type == "fix-ewallet-duplicates") {
//            $this->ewalletDuplicateFix();
        } else if ($type == "fix-ewallet-closing-opening-balance") {
//            $this->ewalletClosingOpeningBalanceFix();
        } else if ($type == "fix-subscription-empty-payment-methods") {
//            $this->fixSubscriptionEmptyPaymentMethods();
        } else if ($type == "upload-tv-users-with-pay-method") {
//            $this->uploadTvUserWithPayMethod();
        } else if ($type == "upload-my-billing-csv") {
//            $this->uploadMyBillingCSV();
        } else if ($type == "upload-my-billing-csv-201905-28-31") {
//            $this->uploadMyBillingCSV052831();
        } else if ($type == "csv-profile-info-import") {
//            $this->csvProfileInfoImport();
        } else if ($type == "csv-profile-info-import") {
            $this->csvProfileInfoImport();
        } else if ($type == "MatchedRecsCsvEmptyAddress") {
//            $this->matchedRecsCsvEmptyAddress();
        } else if ($type == "site-enable-for-tv-users") {
//            $this->siteEnableForTvUsers();
        } else if ($type == "deactivate-idecide-sor-after-subscription-fail") {
//            $this->deactvivateIdecideSorUserAfterSubscriptionFail();
        } else if ($type == "idecide-sso-token-generate") {
//            $this->generateIDecideSSOToken();
        } else if ($type == "idecide-user-reset-password") {
//            $this->idecideUserResetPassword();
        }else if ($type == "site-enable-error-subscription20190606") {
//            $this->siteEnableForSubscription20190606();
        } else if ($type == "site-enable-error-subscription") {
//            $this->siteEnableForDisabledUsers();
        }else if ($type == "save-on-api-duplicate-checks") {
//            $this->saveOnApiDuplicateChecks();
        } else if ($type == "fix-already-email-used-idecide-users") {
//            $this->fixAlreadyEmailUsedIdecideUsers();
        } else if ($type == "create-saveon-for-standy-users") {
//            $this->createSORForStandByUsers();
        } else if ($type == "create-saveon-for-standy-users") {
//            $this->createSORForStandByUsers();
        } else if ($type == "process-coach-csv-data") {
//            $this->processCoachCSV();
//            $this->processCoachCSV_22_03();
        } else if ($type == "process-business-csv-data") {
//            $this->processBusinessCSV();
//            $this->processBusinessCSV_22_03();
        } else if ($type == "process-first-class-csv-data") {
//            $this->processFirstClassCSV_19_03();
//            $this->processFirstClassCSV_22_03();
        } else if ($type == "process-vip-billing-re-run") {
//            $this->processVipBillingReRun();
        } else if ($type == "process-manual-re-run") {
//            $this->processManualReRunAfterFixAcounts();
        } else if ($type == "process-business-billing-re-run") {
//            $this->processBusinessBillingReRun();
        } else if ($type == "process-coach-billing-re-run") {
//            $this->processCoachBillingReRun();
        } else if ($type == "import-transaction") {
//            $this->importTransactions();
        } else if ($type == "create-idecide-for-stand-by-users") {
//            $this->createIdecideForStandByUsers();
        } else if ($type == "import-tv-user-sor") {
//            $this->importTvUsers();
        } else if ($type == "process-pre-enrollments") {
//            $this->processPreEnrollments();
        } else if ($type == "add-boomerang-to-t-g-class") {
//            $this->addBoomerangsToTGClass();
        } else if ($type == "vip-first-class-sor-idecide-account-create") {
//            $this->vipFirstClassSORiDecideAccountCreate();
        } else if ($type == "add-boomerang-to-stand-by-class") {
//            $this->addBoomerangsToStandByClass();
        } else if ($type == "update-idecide-business-to-all-users") {
//            $this->updateIdecideBusinessToAllUsers();
        } else if ($type == "transfer-sor-for-vip-first-class-users") {
//            $this->transferSorForVipFirstClassUsers();
        } else if ($type == "import-tv-csv") {
//            $this->importTVcsv();
        } else if ($type == "assign-tv-sponsor") {
            //$this->assignTVSponsor();
        } else if ($type == "import-tv-first-class") {
//            $this->importTVFirstClass();
        } else if ($type == "make-username-uniqe") {
            //$this->makeUsernameUnique();
        } else if ($type == "fix-tv-username-isses") {
            //$this->fixTvUsernameIssues();
        } else if ($type == "sync-sor") {
//            $this->syncSOR();
        }else if ($type == "update-next-subscription-date") {
//            $this->update_next_subscription_date();
        } else if ($type == "update-iGo4-less-member-count-2019-05-02-csv") {
            // $this->updateiGo4LessMemberCount2019_05_02_csv();
        } else if ($type == "update-iGo4-less-member-count-2019-05-21-csv") {
//            $this->updateiGo4LessMemberCount2019_05_21_csv();
        } else {
            echo "invalid cron job type";
        }
    }


    public function fixingAgreedIdecideUsers()
    {
        $users = DB::table('product_terms_agreement')
            ->where('agree_idecide', 1)
            ->get();

        foreach ($users as $user) {
            $has = DB::table('idecide_users')
                ->select('*')
                ->where('user_id', $user->user_id)
                ->first();
            if (empty($has)) {
                echo $user->user_id . "\n";
                $idecideRes = \App\IDecide::iDecideCreateUser($user->user_id);
                $lastId = \App\Helper::logApiRequests($user->user_id, 'IDECIDE', config('api_endpoints.iDecideCreateNewUser'), $idecideRes['request']);
                \App\Helper::logApiResponse($lastId->id, json_encode($idecideRes['response']));
                $idecideResponse = $idecideRes['response'];
                print_r($idecideResponse);
                if (!empty($idecideResponse->errors)) {
                    $error = 1;
                    if (is_array($idecideResponse->errors)) {
                        $msg = implode('<br>', $idecideResponse->errors);
                    } else {
                        $msg = $idecideResponse->errors;
                    }
                } else {
                    $idecideRequest = $idecideRes['request'];
                    \App\IDecide::insert(['api_log' => $lastId->id, 'user_id' => $user->user_id, 'idecide_user_id' => $idecideResponse->userId, 'password' => $idecideRequest['password'], 'login_url' => $idecideResponse->loginUrl, 'status' => 1]);
                }
            }
        }


    }

    public function sitesDeactivateForTerminatedUsers()
    {
        $users = DB::table('users')
            ->join('sor_tokens', 'users.id', '=', 'sor_tokens.user_id')
            ->select('users.*', 'sor_tokens.status as s_status')
            ->where('users.account_status', 'TERMINATED')
            ->where('sor_tokens.status', '!=', 0)
            ->get();
        foreach ($users as $user) {
            \App\Helper::deActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, \App\SaveOn::USER_DISABLE_FOR_SUBSCRIPTION_FAIL);
        }
    }

    public function fixingBoomerangs()
    {
        $boomerangs = DB::table('orderItem')->where('quantity', '>', 1)->where('productid', 15)->limit(50)->get();
        foreach ($boomerangs as $key => $boomerang) {
            echo "order id :- " . $boomerang->orderid ."\n\n";
            $quantity = $boomerang->quantity;
            echo "\nbefore qty :- " . $quantity;
            $quantity = $quantity - 1;
            echo "\nafter qty :- " . $quantity."\n\n";
            for ($x = 1; $x <= $quantity; $x++) {
                DB::table('orderItem')->insert([
                    'orderid' => $boomerang->orderid,
                    'productid' => 15,
                    'quantity' => 1,
                    'bv' => 15,
                    'qv' => 25,
                    'cv' => 15,
                    'created_date' => $boomerang->created_date,
                    'created_time' => $boomerang->created_time,
                    'created_dt' => $boomerang->created_dt,
                ]);
            }
            DB::table('orderItem')->where('orderid', $boomerang->orderid)->update([
                'itemprice' => '25.00',
                'bv' => 15,
                'qv' => 25,
                'cv' => 15,
                'quantity' => 1,
            ]);
        }
    }
    public function importAlisGroupCSV()
    {
        $file = file_get_contents(public_path() . '/json/ali_group.json');
        $ali_group = json_decode($file);
        foreach ($ali_group as $member) {
            $distid = $member->distid;
            $user = DB::table('users')->select('*')
                ->where('distid', $distid)
                ->first();
            if (!empty($user)) {
                //create order
                //all the users are in coach class
                $upgradeProductId = \App\Product::ID_UPG_COACH_FIRST;
                $sesData = [
                    'newProductId' => \App\Product::ID_FIRST_CLASS,
                    'currentProductId' => \App\Product::ID_BASIC_PACK,
                    'discountCode' => ''
                ];
                $product = \App\Product::getById($upgradeProductId);
                $orderSubtotal = $product->price;
                $orderTotal = $product->price - 0;
                $authorization = 'Admin#23082019#CSVALI';
                $paymentMethodType = \App\PaymentMethodType::TYPE_ADMIN;
                $addressId = \App\Helper::createSecondoryAddressIfNotAvlPrimaryAddress($user->id, [], $paymentMethodType);
                $req = new \stdClass();
                $req->token = null;
                $req->cvv = null;
                $req->expiry_date = null;
                $req->first_name = null;
                $req->last_name = null;
                $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod($user->id, '', $addressId, $req, $paymentMethodType);
                \App\Helper::createNewOrderAfterPayment($user->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, $authorization, 'UPGRADE_PACKAGE');
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['founder' => 1, 'remarks' => 'Upgraded per HB to First Class, 20% Binary for 12 months and 2 Months Membership Free']);
                echo $distid . " - Order created \n";
            } else {
                echo $distid . " - Not Found \n";
            }
        }

    }

    public function updateCsvSubscriptionOrders()
    {
        $orders = \App\Order::select('*')->where('trasnactionid', '12062019#JUNE#CSVMYBILLING')->get();
        foreach ($orders as $order) {
            $subscriptionHistory = DB::table('subscription_history')->select('*')->where('user_id', $order->userid)->where('status', 1)->whereMonth('attempted_date', 06)->get();
            echo "User id := " . $order->userid . "\n";
            if (count($subscriptionHistory) == 0) {
                //create
                $oCreatedDate = date('d', strtotime($order->created_date));
                if ($oCreatedDate >= 25) {
                    $sDate = strtotime(date("Y-m-25", strtotime($order->created_date)) . " +1 month");
                    $sDate = date("Y-m-d", $sDate);
                } else {
                    $sDate = strtotime(date("Y-m-d", strtotime($order->created_date)) . " +1 month");
                    $sDate = date("Y-m-d", $sDate);
                }
                DB::table('users')->where('id', $order->userid)->update([
                    'gflag' => 0,
                    'payment_fail_count' => 0,
                    'original_subscription_date' => $sDate,
                    'next_subscription_date' => $sDate,
                    'subscription_remarks' => 'subscription imported using csv',
                ]);
                $subscription = new \App\SubscriptionHistory();
                $subscription->user_id = $order->userid;
                $subscription->subscription_product_id = \App\Product::ID_Traverus_Grandfathering;
                $subscription->attempted_date = date("Y-m-d", strtotime($order->created_date));
                $subscription->attempt_count = 0;
                $subscription->payment_method_id = $order->payment_methods_id;
                $subscription->response = "Subscription payment success. Import from csv";
                $subscription->next_attempt_date = $sDate;
                $subscription->status = 1;
                $subscription->remarks = "subscription imported using csv";
                $subscription->save();
                echo "Subscription added\n";
                $userInfo = \App\User::find($order->userid);
                \App\Helper::reActivateSaveOnUser($userInfo->id, $userInfo->current_product_id, $userInfo->distid, \App\SaveOn::USER_ACTIVATED_SUCCESSFULLY);
                \App\Helper::reActivateIdecideUser($userInfo->id);
                $response = \App\Helper::reActivateSaveOnUser($userInfo->id, $userInfo->current_product_id, $userInfo->distid, \App\SaveOn::USER_ACTIVATED_SUCCESSFULLY);
                echo "SOR enable\n";
                print_r($response);
                echo "IDecide enable\n";
                \App\Helper::reActivateIdecideUser($userInfo->id);
                print_r($response);
            } else if (count($subscriptionHistory) == 1) {
                echo "Subscription already exists\n";
                //already exsits
//                    DB::table('users')->where('id', $order->userid)->update(['subscription_remarks' => 'More than one subscription charged']);
            } else if (count($subscriptionHistory) > 1) {
                echo "Subscription charged twice\n";
                //more than one subscription fee charged
                DB::table('users')->where('id', $order->userid)->update(['subscription_remarks' => 'More than one subscription charged']);
            }
            echo "\n\n";
            die;
        }

    }
    public function siteEnableForDisabledUsers()
    {
        $users = \App\User::where('account_status', \App\User::ACC_STATUS_APPROVED)
            ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
            ->where('current_product_id', '!=', \App\Product::ID_PREMIUM_FIRST_CLASS)
            ->whereDate('next_subscription_date', '>=', date('Y-07-d'))
            ->orderBy('id', 'asc')
            ->get();
        foreach ($users as $user) {
            \App\Helper::sor_member_info($user->distid);
            echo $user->distid . " | " . $user->id . "\n";
            die;
            //check save status
            \App\Helper::reActivateIdecideUser($user->id);
            \App\Helper::reActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, 'Monthly subscription payment success');


        }

    }


    public function siteEnableForSubscription20190606()
    {
        $users = \App\User::select('*')->whereIn('distid', [
            'TSA3917797'
        ])->get();
        foreach ($users as $user) {
            echo $user->distid . "\n";
            \App\Helper::reActivateIdecideUser($user->id);
            \App\Helper::reActivateSaveOnUser($user->id, $user->current_product_id, $user->distid, 'Monthly subscription payment success');
        }

    }

    public function siteEnableForTvUsers()
    {
        echo  "<pre>";
        $users = \App\ApiLogs::select('*')->where('endpoint', 'clubmembership/deactivatemember')->where('created_at', '>=', '2019-06-01')->where('response', 'true')->get();
        foreach ($users as $user) {
            $u = \App\User::select('*')->where('id', $user->user_id)->first();
            if ($u->is_tv_user == 1) {
                echo $user->user_id . "\n";
                \App\Helper::reActivateSaveOnUser($u->id, $u->current_product_id, $u->distid, 'The user has paid subscription fees');
            }
        }
    }
    public function csvProfileInfoImport()
    {
        $filename = public_path() . '/csv/profile_info_spreadsheet.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $checkUser = \App\User::select('*')->where('distid', trim($line[0]))->first();
                        echo $checkUser->distid . " ! " . $checkUser->id . "\n";
                        if (!empty($checkUser)) {
                            $sub_date = trim($line[15]);
                            $sub_date = date("Y-06-d", strtotime($sub_date));
                            \App\User::where('id', $checkUser->id)->update(['original_subscription_date' => $sub_date, 'next_subscription_date' => $sub_date]);
                            //                            $billAddress = \App\Address::getRec($checkUser->id, \App\Address::TYPE_BILLING, $isPrimary = 1);
//                            if (empty($billAddress)) {
//                                $areq = new \stdClass();
//                                $areq->userid = $checkUser->id;
//                                $areq->addrtype = \App\Address::TYPE_BILLING;
//                                $areq->primary = 1;
//                                $areq->address1 = trim($line[5]);
//                                $areq->address2 = trim($line[6]);
//                                $areq->city = trim($line[7]);
//                                $areq->stateprov = trim($line[8]);
//                                $areq->postalcode = trim($line[9]);
//                                $areq->countrycode = "US";
//                                $areq->apt = "";
//                                $addressId = \App\Address::addNewRecSecondaryAddressTvUser($checkUser->id, \App\Address::TYPE_BILLING, $isPrimary, $areq);
//                                echo "Address added ! " . $addressId . "\n";
//                            } else if (!empty($billAddress) && empty($billAddress->address1) && empty($billAddress->city) && empty($billAddress->postalcode) && empty($billAddress->countrycode)) {
//                                \App\Address::where('id', $billAddress->id)->update(['address1' => trim($line[5]), 'address2' => trim($line[6]),
//                                    'city' => trim($line[7]), 'stateprov' => trim($line[8]), 'postalcode' => trim($line[9]), 'countrycode' => 'US']);
//                                echo "Address updated ! " . $billAddress->id . "\n";
//                            } else {
//                                echo "Address already exists ! " . $billAddress->id . "\n";
//                            }
                            echo "\n\n";
                        }
                    }
                }
            }
        }
        fclose($fp);
        die;
    }

    public function uploadMyBillingCSV052831()
    {
        $filename = public_path() . '/csv/June $79.95 billings.csv';
        $filename = public_path() . '/csv/May 28-31 Monthly Billing TraVerus.csv';
        $filename = public_path() . '/csv/TG_Not_imported_May_Billings.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $checkUser = \App\User::select('*')->where('distid', trim($line[0]))->first();
                        if (!empty($checkUser)) {
                            $orderTotal = trim($line[1]);
                            $product = \App\Product::getById(\App\Product::ID_Traverus_Grandfathering);
                            $quantity = 0;
                            if ($orderTotal == "239.85") {
                                $quantity = 3;
                            } else if ($orderTotal == "159.9") {
                                $quantity = 2;
                            } else if ($orderTotal == "79.95") {
                                $quantity = 1;
                            }
                            if ($quantity) {
                                $orderBV = $product->bv * $quantity;
                                $orderQV = $product->qv * $quantity;
                                $orderCV = $product->cv * $quantity;
                                $createdDate = date('Y-m-d', strtotime(trim($line[2])));
                                $userId = $checkUser->id;
                                $paymentMethodId = \App\PaymentMethod::select('*')->where('userID', $userId)->where('primary', 1)->where('pay_method_type', \App\PaymentMethodType::TYPE_CREDIT_CARD)->first();
                                $hasOrder = \App\Order::select('*')->where('userid', $userId)->where('trasnactionid', "15062019#MAY#CSVMYBILLING")->where('created_date', $createdDate)->first();
                                $orderId = (!empty($hasOrder) ? $hasOrder->id : '');
                                if (empty($paymentMethodId)) {
                                    $address = \App\Address::getRec($userId, \App\Address::TYPE_BILLING, $isPrimary = 1);
                                    if (empty($address)) {
                                        $areq = new \stdClass();
                                        $areq->userid = $userId;
                                        $areq->addrtype = "";
                                        $areq->primary = 1;
                                        $areq->address1 = "";
                                        $areq->address2 = "";
                                        $areq->city = "";
                                        $areq->stateprov = "";
                                        $areq->postalcode = "";
                                        $areq->countrycode = "";
                                        $areq->apt = "";
                                        $addressId = \App\Address::addNewRecSecondaryAddressTvUser($userId, \App\Address::TYPE_BILLING, $isPrimary, $areq);
                                    } else {
                                        $addressId = $address->id;
                                    }
                                    $rec = new \stdClass();
                                    $rec->token = null;
                                    $rec->cvv = null;
                                    $rec->firstname = null;
                                    $rec->lastname = null;
                                    $paymentMethodId = \App\PaymentMethod::create(['userID' => $userId, 'primary' => '1', 'pay_method_type' => \App\PaymentMethodType::TYPE_CREDIT_CARD]);
                                }
                                if (!empty($paymentMethodId) && empty($hasOrder)) {
                                    $orderId = \App\Order::addNew($userId, trim($line[1]), trim($line[1]), $orderBV, $orderQV, $orderCV, "15062019#MAY#CSVMYBILLING", $paymentMethodId->id, null, null, $createdDate, null);
                                    \App\OrderItem::addNew($orderId, $product->id, $quantity, $product->price, $orderBV, $orderQV, $orderCV);
                                } else {
                                    echo $userId . " | payment method empty\n";
                                }
                                echo "Order id | " . $orderId . "\n\n";
                            }
                        }
                    }
                }
            }
        }
        fclose($fp);
        die;
    }

    public function uploadMyBillingCSV()
    {
        $filename = public_path() . '/csv/May Billings.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $checkUser = \App\User::select('*')->where('distid', trim($line[0]))->first();
                        if (!empty($checkUser)) {
                            $createdDate = date('Y-m-d', strtotime(trim($line[2])));
                            $userId = $checkUser->id;
                            $product = \App\Product::getById(14);
                            $orderBV = $product->bv;
                            $orderQV = $product->qv;
                            $orderCV = $product->cv;
                            $paymentMethodId = \App\PaymentMethod::select('*')->where('userID', $userId)->where('primary', 1)->where('pay_method_type', \App\PaymentMethodType::TYPE_CREDIT_CARD)->first();
                            $hasOrder = \App\Order::select('*')->where('userid', $userId)->where('trasnactionid', "31052019#CSVMYBILLING")->first();
                            $orderId = (!empty($hasOrder) ? $hasOrder->id : '');
                            if (empty($paymentMethodId)) {
                                $address = \App\Address::getRec($userId, \App\Address::TYPE_BILLING, $isPrimary = 1);
                                if (empty($address)) {
                                    $areq = new \stdClass();
                                    $areq->userid = $userId;
                                    $areq->addrtype = "";
                                    $areq->primary = 1;
                                    $areq->address1 = "";
                                    $areq->address2 = "";
                                    $areq->city = "";
                                    $areq->stateprov = "";
                                    $areq->postalcode = "";
                                    $areq->countrycode = "";
                                    $areq->apt = "";

                                    $addressId = \App\Address::addNewRecSecondaryAddressTvUser($userId, \App\Address::TYPE_BILLING, $isPrimary, $areq);
                                } else {
                                    $addressId = $address->id;
                                }
                                $rec = new \stdClass();
                                $rec->token = null;
                                $rec->cvv = null;
                                $rec->firstname = null;
                                $rec->lastname = null;
                                $paymentMethodId = \App\PaymentMethod::create(['userID' => $userId, 'primary' => '1', 'pay_method_type' => \App\PaymentMethodType::TYPE_CREDIT_CARD]);
                            }
                            if (!empty($paymentMethodId) && empty($hasOrder)) {
                                $orderId = \App\Order::addNew($userId, trim($line[1]), trim($line[1]), $orderBV, $orderQV, $orderCV, "31052019#CSVMIBILLING", $paymentMethodId->id, null, null, $createdDate, null);
                                \App\OrderItem::addNew($orderId, $product->id, 1, trim($line[1]), $orderBV, $orderQV, $orderCV);
                            } else {
                                echo $userId . " | payment method empty\n";
                            }
                            echo "Order id | " . $orderId . "\n\n";
                        }
                    }
                }
            }
        }
        fclose($fp);
        die;
    }

    public function fixSubscriptionEmptyPaymentMethods()
    {
        $users = \App\User::select('*')->whereIn('distid', ['TSA7018664', 'TSA0617516', 'TSA5116278', 'TSA7815705', 'TSA7814669', 'TSA2402708', 'TSA4306695', 'TSA6426692', 'TSA3486082', 'TSA9975991', 'TSA4545068', 'TSA9054988', 'TSA1742683'])->get();
        foreach ($users as $user) {
            echo $user->id . "\n";
            $subsPaymentId = $user->subscription_payment_method_id;
            $paymentMethodExists = \App\PaymentMethod::find($subsPaymentId);
            if (empty($paymentMethodExists)) {
                \App\User::where('id', $user->id)->update(['subscription_payment_method_id' => null]);
            }
        }
    }

    public function matchedRecsCsvEmptyAddress()
    {
        $distids = [];
        $filename = public_path() . '/csv/Matched recs.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $checkUser = \App\User::select('*')->where('distid', trim($line[0]))->first();
                        if (!empty($checkUser)) {
                            $distids[] = trim($line[0]);
                        }
                        echo "\n\n";
                    }
                }
            }
        }
        fclose($fp);

        $x = "";
        foreach ($distids as $distid) {
            $x = $x . "'.$distid.',";
        }
        echo $x;
        die;
        file_put_contents("billing-address-not-found.json", json_encode($distids));

    }
    public function uploadTvUserWithPayMethod()
    {
        $distids = [];
        $filename = public_path() . '/csv/Matched recs.csv';
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $checkUser = \App\User::select('*')->where('distid', trim($line[0]))->first();
                        if (!empty($checkUser)) {
                            //add pay method
                            $hasPaymentMethod = \App\PaymentMethod::select('*')->where('pay_method_type', \App\PaymentMethodType::TYPE_CREDIT_CARD)->where('userID', $checkUser->id)->count();
                            if ($hasPaymentMethod == 0) {
                                $hasBillingAddress = \App\Address::select('*')->where('userid', $checkUser->id)->first();
                                if (empty($hasBillingAddress)) {
                                    $rec = new \stdClass();
                                    $rec->userid = $checkUser->id;
                                    $rec->addrtype = \App\Address::TYPE_BILLING;
                                    $rec->primary = 1;
                                    $rec->address1 = null;
                                    $rec->address2 = null;
                                    $rec->city = null;
                                    $rec->stateprov = null;
                                    $rec->postalcode = null;
                                    $rec->countrycode = null;
                                    $rec->apt = null;
                                    $addressId = \App\Address::addNewRecSecondaryAddressTvUser($checkUser->id, \App\Address::TYPE_BILLING, 1, $rec);
                                    echo trim($line[0]) . " billing address added\n";
                                } else {
                                    echo trim($line[0]) . " billing address already exists\n";
                                    $addressId = $hasBillingAddress->id;
                                }
                                \App\PaymentMethod::insert(
                                    [
                                        'userID' => $checkUser->id,
                                        'primary' => 1,
                                        'token' => trim($line[9]),
                                        'cvv' => trim($line[8]),
                                        'expMonth' => trim($line[6]),
                                        'expYear' => trim($line[7]),
                                        'firstname' => trim($line[4]),
                                        'lastname' => trim($line[5]),
                                        'bill_addr_id' => $addressId,
                                        'pay_method_type' => \App\PaymentMethodType::TYPE_CREDIT_CARD,
                                    ]
                                );
                                echo trim($line[0]) . " payment method added\n";
                            } else {
                                echo trim($line[0]) . " pay method already exists\n";
                            }
                        } else {
                            $distids[] = trim($line[0]);
                            echo trim($line[0]) . " Not found\n";
                        }
                        echo "\n\n";
                    }
                }
            }
        }
        fclose($fp);
        file_put_contents("billing-address-not-found.json", json_encode($distids));
    }

    public function ewalletClosingOpeningBalanceFix()
    {
        DB::statement("update ewallet_transactions set add_deduct = 1 where type in ('DEPOSIT','COUP_CODE_REFUND','D_VOUCHER_REFUND','SUBSCRIPTION_REFUND','ADJUSTMENT_ADD')");
        DB::statement("update ewallet_transactions set add_deduct = -1 where type in ('WITHDRAW','COUP_CODE_PURCHASE','TRANSACTION FEE','UPGRADE_PACKAGE','CHECKOUT_BUUMERANGS','ADJUSTMENT_DEDUCT','MONTHLY_SUBSCRIPTION','MONTHLY_SUBSCRIPTION','REACT_SUBSCRIPTION')");
        $transactions = \App\EwalletTransaction::select('*')->orderBy('id', 'asc')->get()->toArray();
        $nts = [];
        foreach ($transactions as $transaction) {
            $nts[$transaction['user_id']][] = $transaction;
        }
        foreach ($nts as $userId => $nt) {
            $s = 0;
            foreach ($nt as $key => $n) {
                if ($key > 0) {
                    \App\EwalletTransaction::where('id', $n['id'])
                        ->update([
                            'opening_balance' => $s,
                        ]);
                }
                if ($n['add_deduct'] > 0) {
                    $new_closing_amount = $s /*$n['opening_balance']*/ + $n['amount'];
                    \App\EwalletTransaction::where('id', $n['id'])
                        ->update([
                            'closing_balance' => (float)$new_closing_amount,
                        ]);
                } else if ($n['add_deduct'] < 0) {
                    $new_closing_amount = $s /*$n['opening_balance']*/ - $n['amount'];
                    \App\EwalletTransaction::where('id', $n['id'])
                        ->update([
                            'closing_balance' => (float)$new_closing_amount,
                        ]);
                }
                $s = $new_closing_amount;
            }
        }
        DB::statement("
        UPDATE users m
SET estimated_balance = f.valsum
FROM
(
  SELECT user_id, SUM(amount * add_deduct) valsum
  FROM ewallet_transactions
  GROUP BY user_id
) f
WHERE m.id = f.user_id
");
    }

    public function updateiGo4LessMemberCount2019_05_02_csv()
    {
        $filename = public_path() . '/csv/iGo4Less Member Count 2019-05-02.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $contractNumber = $data[0];
            $csvSorMemberId = trim($data[1]);
            if (!empty($contractNumber)) {
                $user = \App\User::select('*')->where('distid', (string)$contractNumber)->first();
                if (!empty($user)) {
                    $sorMember = \App\SaveOn::getSORUserId($user->id);
                    if (!empty($sorMember) && $sorMember != $csvSorMemberId) {
                        echo (string)$contractNumber . " - not found\n";
                        echo (string)$csvSorMemberId . " - new sor id\n";
                        echo (string)$sorMember . " - old sor id\n\n\n";
                        \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $csvSorMemberId, 'old_sor_user_id' => $sorMember]);
                    }
                }
            }
        }
    }

    public function updateiGo4LessMemberCount2019_05_21_csv()
    {
        $filename = public_path() . '/csv/iGo4Less Member Count 2019-05-21.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $contractNumber = $data[0];
            $csvSorMemberId = trim($data[1]);
            if (!empty($contractNumber)) {
                $user = \App\User::select('*')->where('distid', (string)$contractNumber)->first();
                if (!empty($user)) {
                    $sorMember = \App\SaveOn::getSORUserId($user->id);
                    if (!empty($sorMember) && $sorMember != $csvSorMemberId) {
                        echo (string)$contractNumber . " - not found\n";
                        echo (string)$csvSorMemberId . " - new sor id\n";
                        echo (string)$sorMember . " - old sor id\n\n\n";
                        \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $csvSorMemberId, 'old_sor_user_id' => $sorMember]);
                        die;
                    }
                }
            }
        }

    }

    public function ewalletDuplicateFix()
    {
        $results = DB::select(
            DB::raw('SELECT orders.*
FROM orders
LEFT JOIN "orderItem" ON orders.id="orderItem".orderid
WHERE "orderItem".productid IN (32,31,30,29,28,27,25,24,23,22,21)
AND orders.created_date >= \'2019-04-26\'
AND orders.ordertotal > 0
AND  orders.trasnactionid IS NULL
ORDER BY userid DESC'));
        foreach ($results as $result) {
            echo $result->userid . "\n";
            $getEwallet = \App\PaymentMethod::where('userID', $result->userid)
                ->where('pay_method_type', 3)
                ->get()->pluck('id')->toArray();
            if (count($getEwallet) > 1) {
                \App\Order::whereIn('payment_methods_id', $getEwallet)->where('userid', $result->userid)->update(['payment_methods_id' => $getEwallet[0]]);
                unset($getEwallet[0]);
                \App\PaymentMethod::whereIn('id', $getEwallet)->where('pay_method_type', 3)->where('userID', $result->userid)->delete();
            }
        }
    }




    public function update_next_subscription_date()
    {
        $users = \App\User::select('*')->where('created_date', '<=', '2019-03-11')->pluck('id');
        \App\User::whereIn('id', $users)->update(['next_subscription_date' => '2019-04-11']);
        $users = \App\User::select('*')->where('created_date', '>=', '2019-03-25')->where('created_date', '<=', '2019-03-31')->pluck('id');
        \App\User::whereIn('id', $users)->update(['next_subscription_date' => '2019-04-25']);
        $users = \App\User::select('*')->whereNull('next_subscription_date')->get();
        foreach ($users as $user) {
            $next_subscription_date = strtotime(date("Y-m-d", strtotime($user->created_date)) . " +1 month");
            $next_subscription_date = date('Y-m-d', $next_subscription_date);
            \App\User::where('id', $user->id)->update(['next_subscription_date' => $next_subscription_date]);
        }

    }

    public function syncSOR()
    {
        $filename = public_path() . '/Sheet1-Table 1.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($line) {
                $csv_data[] = $line;
            }
        }
        fclose($fp);
        $x = 0;
        foreach ($csv_data as $data) {
            if ($x > 0) {
                $sor_id = trim($data[0]);
                $contract_number = trim($data[1]);
                $user = \App\User::select('*')->where('distid', $contract_number)->first();
                if (!empty($user)) {
                    \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $sor_id]);
                } else {
                    echo $contract_number . "\n";
                    echo "Not found\n\n";
                }
            }
            $x++;
        }
    }

    public function generateIDecideSSOToken() {
        $userId = '';
        $responseBody = \App\IDecide::createIdecideSSOToken($userId);
        $response = $responseBody['response'];
        if (!empty($response->token)) {
            //token generate
            echo "Token :- " . $response->token . "\n";
            echo "SSO Url :- " . $response->ssoUrl . "\n";
        } else {
            //token generate failure
        }
    }

    public function idecideUserResetPassword() {
        $userId = '2';
        $newPassword = 'password';
        $responseBody = \App\IDecide::updateUserPassword($userId, $newPassword);
        $response = $responseBody['response'];
        $request = $responseBody['request'];
        if (!empty($response->success) && $response->success == 1) {
            //password reset success
            \App\IDecide::where('user_id', $userId)->update(['password' => $newPassword]);
        } else {
            //password reset failure
            echo $errors = implode('<br>', $response->errors);
        }
    }

    public function fixAlreadyEmailUsedIdecideUsers() {
        $apiLogs = \App\ApiLogs::select('*')
            ->where('api', 'IDECIDE')
            ->where('response', 'like', '%EMAIL_USED"%')
            ->get();
        echo "Total records :- " . count($apiLogs) . "\n\n";
        $apiLogs = \App\ApiLogs::select('*')
            ->where('api', 'IDECIDE')
            ->where('response', 'like', '%EMAIL_USED"%')
//            ->limit(100)
            ->get();
        foreach ($apiLogs as $apiLog) {
            echo "Api Log Id:-" . $apiLog->id . "\n";
            $checkIdecideUser = \App\IDecide::select('*')->where('user_id', $apiLog->user_id)->first();
            if (empty($checkIdecideUser)) {
                $user = \App\User::select('*')->where('id', $apiLog->user_id)->first();
                if (!empty($user->email)) {
                    echo "User Id:- " . $user->id . "\n";
                    echo "Email addresss:-" . $user->email . "\n";
                    $response = \App\IDecide::checkUserByEmail($user->email);
                    $response = $response['response'];
                    if (empty($response->errors)) {
                        if (!empty($response) && $response->success == 1) {
                            \App\IDecide::insert(['api_log' => $apiLog->id, 'user_id' => $user->id, 'idecide_user_id' => $response->userId, 'password' => '', 'login_url' => $response->loginUrl]);
                            \App\ApiLogs::where('id', $apiLog->id)->update(['response' => json_encode($response)]);
                        }
                    } else {
                        if ($response->errors[0] == 'Non-existent user email address specified') {
                            \App\ApiLogs::where('id', $apiLog->id)->update(['response' => json_encode($response)]);
                        }
                    }
                    echo "\n\n";
                }
            } else {
                echo "User Id:- " . $checkIdecideUser->user_id . "\n";
                echo "Log deleted\n\n\n";
                \App\ApiLogs::where('id', $apiLog->id)->delete();
            }
        }
    }

    public function saveOnApiDuplicateChecks() {
        $sorLevels = array(
            "12744" => 1,
            "12716" => 2,
            "12718" => 3,
            "12719" => 4
        );
        //1002
        $users = \App\User::select('*')->where('sor_sync_status', 0)->orderBy('id', 'asc')->limit(100)->get();
//        $users = \App\User::select('*')->where('id', 1002)->get();
        foreach ($users as $user) {
            $hasSaveOn = DB::table('sor_token_new')->where('user_id', $user->id)->first();
            if (!empty($user->email) && empty($hasSaveOn)) {
                $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                $accounts = json_decode($jsonResponse);
                if (count($accounts) > 1) {
                    //duplicates checking
                    $totalTsaMatch = [];
                    foreach ($accounts as $key => $account) {
                        if ($account->ContractNumber == $user->distid && array_key_exists($account->VacationClubId, $sorLevels)) {
                            $totalTsaMatch[] = $key;
                        }
                    }
                    if (count($totalTsaMatch) == 1) {
                        //no duplicates for tsa
                        $key = $totalTsaMatch[0];
                        DB::table('sor_token_new')->insert(
                            ['user_id' => $user->id, 'sor_user_id' => (string) $accounts[$key]->UserID, 'status' => 'from our club', 'club_id' => (string) $accounts[$key]->VacationClubId]
                        );
                    } else if (count($totalTsaMatch) > 1) {
                        //duplicate for tsa
                        DB::table('sor_token_new')->insert(
                            ['user_id' => $user->id, 'sor_user_id' => null, 'status' => 'duplicate tsa', 'club_id' => null]
                        );
                    } else {
                        DB::table('sor_token_new')->insert(
                            ['user_id' => $user->id, 'sor_user_id' => null, 'status' => 'no save on account found', 'club_id' => null]
                        );
                    }
                } elseif (count($accounts) == 1) {
                    //no duplicates accounts
                    $sorUserID = (string) $accounts[0]->UserID;
                    $clubId = (string) $accounts[0]->VacationClubId;
                    if ($user->distid == (string) $accounts[0]->ContractNumber) {
                        DB::table('sor_token_new')->insert(
                            ['user_id' => $user->id, 'sor_user_id' => $sorUserID, 'status' => 'from our club', 'club_id' => $clubId]
                        );
                    } else {
                        DB::table('sor_token_new')->insert(
                            ['user_id' => $user->id, 'sor_user_id' => null, 'status' => 'from another club', 'club_id' => null]
                        );
                    }
                } else {
                    //no save on account
                    DB::table('sor_token_new')->insert(
                        ['user_id' => $user->id, 'sor_user_id' => null, 'status' => 'no save on account found']
                    );
                }
            }
            echo "\n\n";
            \App\User::where('id', $user->id)->update(['sor_sync_status' => 1]);
        }
    }

    public function processManualReRunAfterFixAcounts() {
        $distId = 'A1177409';
        echo $distId . "\n";
        die;
        $user = \App\User::select('*')->where('distid', $distId);
        if (empty($user)) {
            echo $distId . "\n";
            echo "Create user\n\n";
            die;
        } else {
            $user = \App\User::select('*')->where('distid', $distId)->first();
            if (!empty($user)) {
                $hasOrders = \App\Order::select('*')->where('userid', $user->id)->count();
                echo "Total orders:-" . $hasOrders . "\n";
                if ($hasOrders == 0) {
                    echo "create orders\n";
                    $product = \App\Product::getProduct(2);
                    //check save account from api
                    $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                    $response = json_decode($jsonResponse);
                    echo "Total Records:- " . count($response) . "\n";
                    //checking duplicates
                    if (count($response) > 0) {
                        if ($distId == $user->distid) {
                            foreach ($response as $saveOnDetails) {
                                if ($saveOnDetails->ContractNumber == $distId) {
                                    //create order
                                    $orderId = \App\Order::addNew($user->id, $product->price, $product->price, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                    // create new order item
                                    echo "Order Id :- " . $orderId . "\n\n";
                                    \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                    //create order item
                                    \App\User::where('id', $user->id)->update(['coach_re_run_csv' => 1]);
                                    echo "\n\n";
                                    break;
                                }
                            }
                        }
                    } else {
                        echo "\nNo Save on account\n";
                        \App\User::where('id', $user->id)->update(['coach_re_run_csv' => 1]);
                        echo "\n\n";
                    }
                } else {
                    \App\User::where('id', $user->id)->update(['coach_re_run_csv' => 1]);
                    echo "\n\n";
                }
            }
        }
    }

    public function processCoachBillingReRun() {
        $filename = public_path() . '/TV15263 billing rerun coach.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($line) {
                $csv_data[] = $line;
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[6]);
            echo $distId . "\n";
            $user = \App\User::select('*')->where('distid', $distId);
            if (empty($user)) {
                echo $distId . "\n";
                echo "Create user\n\n";
                die;
            } else {
                $user = \App\User::select('*')->where('distid', $distId)->where('coach_re_run_csv', 0)->first();
                if (!empty($user)) {
                    $hasOrders = \App\Order::select('*')->where('userid', $user->id)->count();
                    echo "Total orders:-" . $hasOrders . "\n";
                    if ($hasOrders == 0) {
                        echo "create orders\n";
                        $product = \App\Product::getProduct(2);
                        //check save account from api
                        $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                        $response = json_decode($jsonResponse);
                        echo "Total save on records :- " . count($response) . "\n\n";
                        //checking duplicates
                        if (count($response) > 0) {
                            if ($distId == $user->distid) {
                                foreach ($response as $saveOnDetails) {
                                    if ($saveOnDetails->ContractNumber == $distId) {
                                        //create order
                                        $orderId = \App\Order::addNew($user->id, $product->price, $product->price, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                        // create new order item
                                        echo "Order Id :- " . $orderId . "\n\n";
                                        \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                        //create order item
                                        \App\User::where('id', $user->id)->update(['coach_re_run_csv' => 1]);
                                        echo "\n\n";
                                        break;
                                    }
                                }
                            }
                        } else {
                            echo "\nNo Save on account\n";
                            \App\User::where('id', $user->id)->update(['coach_re_run_csv' => 1]);
                            echo "\n\n";
                        }
                    } else {
                        \App\User::where('id', $user->id)->update(['coach_re_run_csv' => 1]);
                        echo "\n\n";
                    }
                }
            }
        }
    }

    public function processBusinessBillingReRun() {
        $filename = public_path() . '/TV15263 billing rerun business.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($line) {
                $csv_data[] = $line;
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[6]);
            echo $distId . "\n";
            $user = \App\User::select('*')->where('distid', $distId);
            if (empty($user)) {
                echo $distId . "\n";
                echo "Create user\n\n";
                die;
            } else {
                $user = \App\User::select('*')->where('distid', $distId)->where('business_re_run_csv', 0)->first();
                if (!empty($user)) {
                    $hasOrders = \App\Order::select('*')->where('userid', $user->id)->count();
                    echo "Total orders:-" . $hasOrders . "\n";
                    if ($hasOrders == 0) {
                        echo "create orders\n";
                        $product = \App\Product::getProduct(3);
                        //check save account from api
                        $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                        $response = json_decode($jsonResponse);
                        echo "Total Records:- " . count($response) . "\n";
                        //checking duplicates
                        if (count($response) > 0) {
                            if ($distId == $user->distid) {
                                foreach ($response as $saveOnDetails) {
                                    if ($saveOnDetails->ContractNumber == $distId) {
                                        //create order
                                        $orderId = \App\Order::addNew($user->id, 500, 500, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                        // create new order item
                                        echo "Order Id :- " . $orderId . "\n\n";
                                        \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                        //create order item
                                        \App\User::where('id', $user->id)->update(['business_re_run_csv' => 1]);
                                        echo "\n\n";
                                        break;
                                    }
                                }
                            }
                        } else {
                            echo "\nNo Save on account\n";
                            \App\User::where('id', $user->id)->update(['business_re_run_csv' => 1]);
                            echo "\n\n";
                        }
                    } else {
                        \App\User::where('id', $user->id)->update(['business_re_run_csv' => 1]);
                        echo "\n\n";
                    }
                }
            }
        }
    }

    public function processVipBillingReRun() {
        $filename = public_path() . '/TV15263 billing rerun vip.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($line) {
                $csv_data[] = $line;
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[6]);
            echo $distId . "\n";
            $user = \App\User::select('*')->where('distid', $distId);
            if (empty($user)) {
                echo $distId . "\n";
                echo "Create user\n\n";
                die;
            } else {
                $user = \App\User::select('*')->where('distid', $distId)->where('vip_re_run_csv', 0)->first();
                if (!empty($user)) {
                    $hasOrders = \App\Order::select('*')->where('userid', $user->id)->count();
                    echo "Total orders:-" . $hasOrders . "\n";
                    if ($hasOrders == 0) {
                        echo "create orders\n";
                        $product = \App\Product::getProduct(4);
                        //check save account from api
                        $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                        $response = json_decode($jsonResponse);
                        echo "Total Records:- " . count($response) . "\n";
                        //checking duplicates
                        if (count($response) > 0) {
                            die;
                            if ($distId == $user->distid) {
                                foreach ($response as $saveOnDetails) {
                                    if ($saveOnDetails->ContractNumber == $distId) {
                                        //create order
                                        $orderId = \App\Order::addNew($user->id, 1000, 1000, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                        // create new order item
                                        echo "Order Id :- " . $orderId . "\n\n";
                                        \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                        //create order item
                                        \App\User::where('id', $user->id)->update(['vip_re_run_csv' => 1]);
                                        echo "\n\n";
                                        break;
                                    }
                                }
                            }
                        } else {
                            echo "\nNo Save on account\n";
                            \App\User::where('id', $user->id)->update(['vip_re_run_csv' => 1]);
                            echo "\n\n";
                        }
                    } else {
                        \App\User::where('id', $user->id)->update(['vip_re_run_csv' => 1]);
                        echo "\n\n";
                    }
                }
            }
        }
    }

    public function processFirstClassCSV_22_03() {
        $filename = public_path() . '/TG_Orders_vip.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[4]);
            echo "TSA :- " . $distId . "\n";
            $user = \App\User::select('*')->where('distid', $distId)->where('first_class_csv_create_orders_process_22_03', 0)->first();
            if (!empty($user)) {
                $product = \App\Product::getProduct(4);
                $rec = new \App\Order();
                $rec->userid = $user->id;
                $rec->statuscode = 1;
                $rec->ordersubtotal = $product->price;
                $rec->ordertotal = $product->price;
                $rec->orderbv = $product->bv;
                $rec->orderqv = $product->qv;
                $rec->ordercv = $product->cv;
                $rec->trasnactionid = null;
                $rec->payment_methods_id = null;
                $rec->shipping_address_id = null;
                $rec->inv_id = null;
                $rec->first_class_csv_create_orders_22_03 = 1;
                $rec->created_date = '2019-03-11';
                $rec->created_time = '11:09:00';
                $rec->save();
                $orderId = $rec->id;
                // create new order item
                echo "Order Id :- " . $orderId . "\n\n";
                \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                //create order item
                \App\User::where('id', $user->id)->update(['first_class_csv_create_orders_process_22_03' => 1]);
                echo "\n\n";
            } else {
//                echo "user not found\n";
            }
        }
    }

    public function processFirstClassCSV_19_03() {
        $filename = public_path() . '/First Class.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[4]);
//            $user = \App\User::select('*')->where('distid', $distId)->where('id', 9034)->first();
            $user = \App\User::select('*')->where('distid', $distId)->where('first_class_csv_create_orders_process_22_03', 0)->first();
            if (!empty($user)) {
                $product = \App\Product::getProduct(4);
                //check save account from api
                $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                $response = json_decode($jsonResponse);
                echo "Total Records:- " . count($response) . "\n";
                //checking duplicates
                if (count($response) > 0) {
                    if ($distId == $user->distid) {
                        foreach ($response as $saveOnDetails) {
                            if ($saveOnDetails->ContractNumber == $distId) {
                                //create order
                                $orderId = \App\Order::addNew($user->id, $product->price, $product->price, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                // create new order item
                                echo "Order Id :- " . $orderId . "\n\n";
                                \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                //create order item
                                \App\User::where('id', $user->id)->update(['first_class_csv_create_orders_process_22_03' => 1]);
                                echo "\n\n";
                                break;
                            }
                        }
                    }
                } else {
                    \App\User::where('id', $user->id)->update(['first_class_csv_create_orders_process_22_03' => 1]);
                    echo "\n\n";
                }
            } else {
                echo "user not found\n";
            }
            die;
        }
    }

    public function processBusinessCSV_22_03() {
        $filename = public_path() . '/TG_Orders_business.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[4]);
            echo "TSA :- " . $distId . "\n";
            $user = \App\User::select('*')->where('distid', $distId)->where('business_csv_create_orders_process_22_03', 0)->first();
            if (!empty($user)) {
                $product = \App\Product::getProduct(3);
                $rec = new \App\Order();
                $rec->userid = $user->id;
                $rec->statuscode = 1;
                $rec->ordersubtotal = $product->price;
                $rec->ordertotal = $product->price;
                $rec->orderbv = $product->bv;
                $rec->orderqv = $product->qv;
                $rec->ordercv = $product->cv;
                $rec->trasnactionid = null;
                $rec->payment_methods_id = null;
                $rec->shipping_address_id = null;
                $rec->inv_id = null;
                $rec->business_class_csv_create_orders_22_03 = 1;
                $rec->created_date = '2019-03-11';
                $rec->created_time = '11:09:00';
                $rec->save();
                $orderId = $rec->id;
                // create new order item
                echo "Order Id :- " . $orderId . "\n\n";
                \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                //create order item
                \App\User::where('id', $user->id)->update(['business_csv_create_orders_process_22_03' => 1]);
                echo "\n\n";
            }
        }
    }

    public function processBusinessCSV() {
        $filename = public_path() . '/Business class.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                $x = 0;
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[6]);
//            $user = \App\User::select('*')->where('distid', $distId)->where('id', 9034)->first();
            $user = \App\User::select('*')->where('distid', $distId)->where('business_csv_create_orders_process', 0)->first();
            if (!empty($user)) {
                $product = \App\Product::getProduct(3);
                //check save account from api
                $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                $response = json_decode($jsonResponse);
                echo "Total Records:- " . count($response) . "\n";
                //checking duplicates
                if (count($response) > 0) {
                    if ($distId == $user->distid) {
                        foreach ($response as $saveOnDetails) {
                            if ($saveOnDetails->ContractNumber == $distId) {
                                //create order
                                $orderId = \App\Order::addNew($user->id, 500, 500, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                // create new order item
                                echo "Order Id :- " . $orderId . "\n\n";
                                \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                //create order item
                                \App\User::where('id', $user->id)->update(['business_csv_create_orders_process' => 1]);
                                echo "\n\n";
                                break;
                            }
                        }
                    }
                } else {
                    \App\User::where('id', $user->id)->update(['business_csv_create_orders_process' => 1]);
                    echo "\n\n";
                }
            }
        }
    }

    public function processCoachCSV_22_03() {
        $filename = public_path() . '/TG_Orders_coach.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[4]);
            echo "TSA :- " . $distId . "\n";
            $user = \App\User::select('*')->where('distid', $distId)->where('coach_csv_create_orders_process_22_03', 0)->first();
            if (!empty($user)) {
                $product = \App\Product::getProduct(2);
                $rec = new \App\Order();
                $rec->userid = $user->id;
                $rec->statuscode = 1;
                $rec->ordersubtotal = $product->price;
                $rec->ordertotal = $product->price;
                $rec->orderbv = $product->bv;
                $rec->orderqv = $product->qv;
                $rec->ordercv = $product->cv;
                $rec->trasnactionid = null;
                $rec->payment_methods_id = null;
                $rec->shipping_address_id = null;
                $rec->inv_id = null;
                $rec->coach_class_csv_create_orders_22_03 = 1;
                $rec->created_date = '2019-03-11';
                $rec->created_time = '11:09:00';
                $rec->save();
                $orderId = $rec->id;
                // create new order item
                echo "Order Id :- " . $orderId . "\n\n";
                \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                //create order item
                \App\User::where('id', $user->id)->update(['coach_csv_create_orders_process_22_03' => 1]);
                echo "\n\n";
            }
        }
    }

    public function processCoachCSV() {
        $filename = public_path() . '/Coach.csv';
        $csv_data = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                $x = 0;
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $csv_data[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        foreach ($csv_data as $data) {
            $distId = trim($data[6]);
//            $user = \App\User::select('*')->where('distid', $distId)->where('id', 9034)->first();
            $user = \App\User::select('*')->where('distid', $distId)->where('coach_csv_create_orders_process', 0)->orderBy('id', 'asc')->first();
            if (!empty($user)) {
                $product = \App\Product::getProduct(2);
                //check save account from api
                $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
                $response = json_decode($jsonResponse);
                echo "Total Records:- " . count($response) . "\n";
                //checking duplicates
                if (count($response) > 0) {
                    if ($distId == $user->distid) {
                        foreach ($response as $saveOnDetails) {
                            if ($saveOnDetails->ContractNumber == $distId) {
                                //create order
                                $orderId = \App\Order::addNew($user->id, 250, 250, $product->bv, $product->qv, $product->cv, null, null, null, null, 1);
                                // create new order item
                                echo "Order Id :- " . $orderId . "\n\n";
                                \App\OrderItem::addNew($orderId, $product->id, 1, $product->price, $product->bv, $product->qv, $product->cv);
                                //create order item
                                \App\User::where('id', $user->id)->update(['coach_csv_create_orders_process' => 1]);
                                echo "\n\n";
                                break;
                            }
                        }
                    }
                } else {
                    \App\User::where('id', $user->id)->update(['coach_csv_create_orders_process' => 1]);
                    echo "\n\n";
                }
            }
        }
    }

    public function syncWithSOR() {
        $usersLists = \App\User::select('*')->where('sor_sync_status', 0)->orderBy('id', 'asc')->limit(20)->get();
//        $usersLists = \App\User::select('*')->where('email', 'fmays357@yahoo.com')->orderBy('id', 'asc')->get();
        $sorLevels = array(
            "12744" => 1,
            "12716" => 2,
            "12718" => 3,
            "12719" => 4
        );
        foreach ($usersLists as $user) {
            $jsonResponse = $this->customCurlToGetSorMemberInfo($user);
            $response = json_decode($jsonResponse);
            $totalRecords = count($response);
            echo "Total Records:- " . $totalRecords . "\n";
            if ($totalRecords > 1) {
                echo "Duplicates in API\n";
                \App\User::where('id', $user->id)->update(['sor_account_available' => $totalRecords, 'sor_sync_status' => 1]);
            } else if ($totalRecords == 1) {
                if ($user->distid == $response[0]->ContractNumber) {
                    $SorUserID = (string) $response[0]->UserID;
                    echo "SOR User ID :- " . $SorUserID . "\n";
                    $clubId = (string) $response[0]->VacationClubId;
                    echo "CLUB ID :-" . $clubId . "\n";
                    $SorLevelProductId = $sorLevels[$clubId];
                    echo "SAVE ON Product ID :- " . $SorLevelProductId . "\n";
                    $hasSOR = \App\SaveOn::select('*')->where('sor_user_id', $SorUserID)->get();
                    echo "Total SOR Token Table Records :- " . count($hasSOR) . "\n";
                    if (!empty($hasSOR) && count($hasSOR) > 1) {
                        //duplicates in sor token table
                        echo "Duplicates in SOR table\n";
                        \App\User::where('user_id', $user->id)->update(['sor_token_table_records' => count($hasSOR), 'sor_account_available' => count($hasSOR), 'sor_sync_status' => 1]);
                    } else if (!empty($hasSOR) && count($hasSOR) == 1) {
                        //club id updates
                        echo "Updated\n";
                        \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $SorUserID, 'product_id' => $SorLevelProductId]);
                        \App\User::where('id', $user->id)->update(['sor_token_table_records' => count($hasSOR), 'sor_account_available' => count($hasSOR), 'sor_sync_status' => 1]);
                    } else {
                        //insert to save on table
                        echo "Inserted\n";
                        die;
                        \App\SaveOn::insert(['sor_user_id' => $SorUserID, 'product_id' => $SorLevelProductId, 'user_id' => $user->id]);
                        \App\User::where('id', $user->id)->update(['sor_token_table_records' => 0, 'sor_account_available' => 0, 'sor_sync_status' => 1]);
                    }
                } else {
                    echo "In another club\n";
                    \App\User::where('id', $user->id)->update(['sor_sync_status' => 1]);
                }
            } else {
                \App\User::where('id', $user->id)->update(['sor_sync_status' => 1]);
            }
            echo "\n\n";
        }
    }

    public function customCurlToGetSorMemberInfo($user) {
        $userEmail = $user->email;
        echo "UserID:- " . $user->id . "\n";
        echo "UserEmail:- " . $userEmail . "\n";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.saveonresorts.com/clubmembership/getmembers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<MemberInfo><APIUsername>iGo4Less0</APIUsername><APIPassword>bezp96o5y04oqj73</APIPassword> <MemberSearchList><MemberReferenceItem><Email>$userEmail</Email></MemberReferenceItem></MemberSearchList></MemberInfo>",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/xml",
            ),
        ));
        $jsonResponse = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            die;
        }
        return $jsonResponse;
    }

    public function customCurlToGetSorMemberInfoBackup($user) {
        $sorLevels = array(
            "12744" => 1,
            "12716" => 2,
            "12718" => 3,
            "12719" => 4
        );
        $userEmail = $user->email;
        echo "UserID:- " . $user->id . "\n";
        echo "UserEmail:- " . $userEmail . "\n";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.saveonresorts.com/clubmembership/getmembers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<MemberInfo>\r\n    <APIUsername>iGo4Less0</APIUsername>\r\n    <APIPassword>bezp96o5y04oqj73</APIPassword>\r\n    <MemberSearchList>\r\n        <MemberReferenceItem>\r\n            <Email>$userEmail</Email>\r\n        </MemberReferenceItem>\r\n    </MemberSearchList>\r\n</MemberInfo>",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/xml",
            ),
        ));
        $jsonResponse = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            die;
        }
        $response = json_decode($jsonResponse);
        $totalRecords = count($response);
        echo "Total Records:- " . $totalRecords . "\n";
        if ($totalRecords == 1) {
            if ($response[0]->ContractNumber == $user->distid) {
                $SorUserID = (string) $response[0]->UserID;
                echo "SOR User ID :- " . $SorUserID . "\n";
                $clubId = (string) $response[0]->VacationClubId;
                echo "CLUB ID :-" . $clubId . "\n";
                $SorLevelProductId = $sorLevels[$clubId];
                echo "SAVE ON Product ID :- " . $SorLevelProductId . "\n";
                $hasSOR = \App\SaveOn::select('*')->where('sor_user_id', $SorUserID)->get();
                if (!empty($hasSOR) && count($hasSOR) > 1) {
                    //duplicates in sor token table
                    \App\User::where('user_id', $user->id)->update(['sor_token_table_records' => count($hasSOR), 'sor_account_available' => count($hasSOR), 'sor_sync_status' => 1]);
                } else if (!empty($hasSOR) && count($hasSOR) == 1) {
                    //club id updates
                    echo "Updated\n";
                    \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $SorUserID, 'product_id' => $SorLevelProductId]);
                    \App\User::where('id', $user->id)->update(['sor_token_table_records' => count($hasSOR), 'sor_account_available' => count($hasSOR), 'sor_sync_status' => 1]);
                } else {
                    //insert to save on table
                    echo "Inserted\n";
                    \App\SaveOn::insert(['sor_user_id' => $SorUserID, 'product_id' => $SorLevelProductId, 'user_id' => $user->id]);
                    \App\User::where('id', $user->id)->update(['sor_token_table_records' => 0, 'sor_account_available' => 0, 'sor_sync_status' => 1]);
                }
            } else {
                \App\User::where('id', $user->id)->update(['sor_account_available' => 1, 'sor_sync_status' => 1, 'has_another_club' => 1]);
            }
        } else if ($totalRecords > 1) {
            $duplciatesFoundInresponse = 0;
            $duplicateKeys = array();
            foreach ($response as $key => $saveOnDetails) {
                if ($saveOnDetails->ContractNumber == $user->distid) {
                    $duplciatesFoundInresponse = $duplciatesFoundInresponse + 1;
                    $duplicateKeys[] = $key;
                }
            }

            if (count($response) !== count($duplicateKeys)) {
                \App\User::where('id', $user->id)->update(['has_another_club' => 1]);
            }


            //no duplicates for tsa
            if (count($duplicateKeys) == 1) {
                $SorUserID = (string) $response[$duplicateKeys[0]]->UserID;
                echo "SOR User ID :- " . $SorUserID . "\n";
                $clubId = (string) $response[$duplicateKeys[0]]->VacationClubId;
                echo "CLUB ID :- " . $clubId . "\n";
                $SorLevelProductId = $sorLevels[$clubId];
                echo "SAVE ON Product ID :- " . $SorLevelProductId . "\n";
                $hasSOR = \App\SaveOn::select('*')->where('sor_user_id', $SorUserID)->get();
                if (!empty($hasSOR) && count($hasSOR) > 1) {
                    //duplicates in sor token table
                    \App\User::where('user_id', $user->id)->update(['sor_token_table_records' => count($hasSOR), 'sor_account_available' => 1, 'sor_sync_status' => 1]);
                } else if (!empty($hasSOR) && count($hasSOR) == 1) {
                    echo "Updated\n";
                    \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $SorUserID, 'product_id' => $SorLevelProductId]);
                    \App\User::where('id', $user->id)->update(['sor_token_table_records' => count($hasSOR), 'sor_account_available' => 1, 'sor_sync_status' => 1]);
                } else {
                    //insert
                    echo "Inserted\n";
                    \App\SaveOn::insert(['sor_user_id' => $SorUserID, 'product_id' => $SorLevelProductId, 'user_id' => $user->id]);
                    \App\User::where('id', $user->id)->update(['sor_token_table_records' => 0, 'sor_account_available' => 0, 'sor_sync_status' => 1]);
                }
            } else if (count($duplicateKeys) > 1) {
                //duplicates for tsa
                \App\User::where('id', $user->id)->update(['sor_account_available' => count($duplicateKeys), 'sor_sync_status' => 1]);
            } else {
                //no records
                \App\User::where('id', $user->id)->update(['sor_account_available' => 0, 'sor_sync_status' => 1]);
            }
        } else {
            //no data
            \App\User::where('id', $user->id)->update(['sor_account_available' => 0, 'sor_sync_status' => 1]);
        }
        echo "\n\n";
    }

    public function createSORForStandByUsers() {
        $users = \App\User::select('*')->where('current_product_id', 1)->where('stand_by_user_saveon_user_processed', 0)->get();
        echo "Total records:- " . count($users) . "\n";
        $users = \App\User::select('*')->where('current_product_id', 1)->where('stand_by_user_saveon_user_processed', 0)->limit(200)->get();
        foreach ($users as $user) {
            $checkSorToken = \App\SaveOn::select('*')->where('user_id', $user->id)->first();
            if (empty($checkSorToken)) {
                $userAddress = \App\Address::getRec($user->id, \App\Address::TYPE_BILLING);
                echo "user id :- " . $user->id . "\n\n";
                if (!empty($userAddress)) {
                    $msg = '';
                    if (empty($userAddress->address1)) {
                        $msg .= "address1 empty<br>";
                    } elseif (empty($userAddress->city)) {
                        $msg .= "city empty<br>";
                    } else if (empty($userAddress->countrycode)) {
                        $msg .= "countrycode empty<br>";
                    } else if (!empty($user->distid)) {
                        $msg .= "user contract number empty<br>";
                    }
                    if (!empty($msg)) {
                        //sor user create
                        $newProductId = 1;
                        $sorRes = \App\SaveOn::SORCreateUser($user->id, $newProductId, $userAddress);
                        $lastId = \App\Helper::logApiRequests($user->id, 'SOR - cron', config('api_endpoints.SORCreateUser'), $sorRes['request']);
                        \App\Helper::logApiResponse($lastId->id, json_encode($sorRes['response']));
                        $sorResponse = $sorRes['response'];
                        if ($sorResponse->ResultType == 'success') {
                            $request = $sorRes['request'];
                            \App\SaveOn::insert(['api_log' => $lastId->id, 'user_id' => $user->id, 'product_id' => $newProductId, 'sor_user_id' => $sorResponse->Account->UserId, 'sor_password' => $request['Password'], 'status' => 1]);
                            \App\User::where('id', $user->id)->update(['stand_by_user_saveon_user' => 1, 'stand_by_user_saveon_user_processed' => 1]);
                        } else {
                            \App\User::where('id', $user->id)->update(['stand_by_user_saveon_user_processed' => 1, 'stand_by_user_saveon_error' => $sorResponse->Message]);
                        }
                    } else {
                        \App\User::where('id', $user->id)->update(['stand_by_user_saveon_error' => $msg, 'stand_by_user_saveon_user_processed' => 1]);
                    }
                } else {
                    \App\User::where('id', $user->id)->update(['stand_by_user_saveon_error' => "user address can not be empty", 'stand_by_user_saveon_user_processed' => 1]);
                }
            }
        }
    }

    public function createIdecideForStandByUsers() {
        $users = \App\User::select('*')->where('current_product_id', 1)->where('createIdecideForStandByUsers', 0)->get();
        echo "Total products:- " . count($users) . "\n\n";
        $users = \App\User::select('*')->where('current_product_id', 1)->where('createIdecideForStandByUsers', 0)->limit(100)->get();
        foreach ($users as $user) {
            echo $user->id . "\n";
            $checkExistingUser = \App\IDecide::select('*')->where('user_id', $user->id)->first();
            if (empty($checkExistingUser)) {
                //insert
                $idecideRes = \App\IDecide::iDecideCreateUser($user->id);
                if ($idecideRes['response']) {
                    if (!empty($idecideRes['response']->errors) && in_array('EMAIL_USED', $idecideRes['response']->errors)) {
                        $response = \App\IDecide::updateIDecideUser($user->id, $user->username, $user->firstname, $user->lastname);
                        if (!empty($response['response'])) {
                            $request = $response['request'];
                            $lastId = \App\Helper::logApiRequests($user->id, 'IDECIDE - cron', 'updateUser', $request);
                            \App\Helper::logApiResponse($lastId->id, json_encode($response['response']));
                        }
                    }
                }
                $lastId = \App\Helper::logApiRequests($user->id, 'IDECIDE - cron', config('api_endpoints.iDecideCreateNewUser'), $idecideRes['request']);
                \App\Helper::logApiResponse($lastId->id, json_encode($idecideRes['response']));
            }
            \App\User::where('id', $user->id)->update(['createIdecideForStandByUsers' => 1]);
        }
    }

    public function addBoomerangsToTGClass() {
        $users = \App\User::select('*')
            ->where('current_product_id', 14)
            ->orderBy('id', 'asc')
            ->get();
        foreach ($users as $user) {
            echo $user->id . "\n";
            $this->addToInventory($user->id, 25);
        }
    }

    public function addToInventory($userId, $newCount) {
        $rec = \App\BoomerangInv::where('userid', $userId)->first();
        if (!empty($rec)) {
            $rec->available_tot = $newCount;
            $rec->save();
        } else {
            $n = new \App\BoomerangInv();
            $n->userid = $userId;
            $n->pending_tot = 0;
            $n->available_tot = $newCount == null ? 0 : $newCount;
            $n->save();
        }
    }

    public function updateIdecideBusinessToAllUsers() {
        $iDecideUsersLists = \App\IDecide::select('*')->where('is_updated_business_number', 0)->get();
        foreach ($iDecideUsersLists as $iDecideUser) {
            $user = \App\User::find($iDecideUser->user_id);
            if (!empty($user)) {
                echo $iDecideUser->id . "\n";
                echo $user->id . "\n\n";
                $userName = $user->username;
                $integrationId = $user->id;
                $firstName = $user->firstname;
                $lastName = $user->lastname;
                $response = \App\IDecide::updateIDecideUser($integrationId, $userName, $firstName, $lastName);
                if (!empty($response['response'])) {
                    $request = $response['request'];
                    $lastId = \App\Helper::logApiRequests($user->id, 'IDECIDE - cron', 'updateUser', $request);
                    \App\Helper::logApiResponse($lastId->id, json_encode($response['response']));
                }

                \App\IDecide::where('id', $iDecideUser->id)->update(['is_updated_business_number' => 1]);
            }
        }
    }

    public function importTvUsers() {
        $filename = public_path() . '/TG-Table 1.csv';
        $result_igo = array();
        $fp = fopen($filename, 'r');
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                $x = 0;
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $result_igo[] = $line;
                    }
                }
            }
        }
        fclose($fp);
        $x = 0;
        foreach ($result_igo as $result) {
            if ($x == 0) {
                $tsa = trim($result[6]);
                echo $tsa . "\n";
                $sor_user_id = trim($result[3]);
                echo $sor_user_id . "\n";
                if (!empty($tsa)) {
                    $user = \App\User::select('*')->where('distid', $tsa)->first();
                    if (!empty($user)) {
                        if ($user->is_tv_user == 1) {
                            echo $user->id . "\n\n";
                            $alreadyExists = \App\SaveOn::select('*')->where('user_id', $user->id)->first();
                            if (!empty($alreadyExists)) {
                                \App\SaveOn::where('user_id', $user->id)->update(['sor_user_id' => $sor_user_id, 'product_id' => 4]);
                            } else {
                                \App\SaveOn::insert(['sor_user_id' => $sor_user_id, 'user_id' => $user->id]);
                            }
                        }
                    } else {
                        echo "User not found\n\n";
                    }
                }
            }
            $x++;
        }


//    $fp = fopen('file.csv', 'w');
//    foreach ($result_igo as $fields) {
//        fputcsv($fp, $fields);
//    }
//    fclose($fp);
    }

    public function transferSorForVipFirstClassUsers() {
        $recs = \App\User::select('*')->where('current_product_id', '13')->where('is_vip_first_class_sor_idecide_processed', 1)->where('is_tv_user', 0)->get();
        foreach ($recs as $rec) {
            $sorUserDetail = \App\SaveOn::select('*')->where('user_id', $rec->id)->where('product_id', 3)->first();
            if (!empty($sorUserDetail)) {
                echo "User Id:- " . $rec->id . "\n";
                echo "Contract Number:- " . $rec->distid . "\n";
                $transferToProductId = 4;
                $sorRes = \App\SaveOn::transfer($rec->id, $sorUserDetail->sor_user_id, $transferToProductId);
                $sorResponse = $sorRes['response'];
                if (!empty($sorResponse->status_code) && $sorResponse->status_code == 200) {
                    $request = $sorRes['request'];
                    \App\SaveOn::where('user_id', $sorUserDetail->user_id)->update(['product_id' => $transferToProductId]);
                    echo $sorResponse->status_code . "\n\n";
                } else {
                    echo $sorResponse . "\n\n";
                }
            }
        }
    }

    public function addBoomerangsToStandByClass() {
        $users = \App\User::select('*')
            ->where('current_product_id', 1)
            ->where('stand_by_user_idecide_user_processed', 0)
            ->orderBy('id', 'asc')
            ->get();
        foreach ($users as $user) {
            $this->addToInventory($user->id, 5);
        }
    }

    public function vipFirstClassSORiDecideAccountCreate() {
        echo 'check again all the process';
        die;
        $recs = \App\User::select('*')->where('current_product_id', 13)->where('is_vip_first_class_sor_idecide_processed', 0)->limit(8)->get();
        foreach ($recs as $rec) {
            $product = \App\Product::find($rec->current_product_id);
            $userAddress = \App\Address::getRec($rec->id, \App\Address::TYPE_BILLING);
            echo "user id :- " . $rec->id;
            if (!empty($userAddress)) {
                $msg = '';
                if (empty($userAddress->address1)) {
                    $msg .= "address1 empty<br>";
                } elseif (empty($userAddress->city)) {
                    $msg .= "city empty<br>";
                } else if (empty($userAddress->countrycode)) {
                    $msg .= "countrycode empty<br>";
                } else if (!empty($rec->distid)) {
                    $msg .= "user contract number empty<br>";
                }
                if (!empty($msg)) {
                    //sor user create
                    $savOnRes = $this->saveOnUserCreate($rec, 4, $userAddress);
                    if ($savOnRes['error'] == 1) {
                        $error = 1;
                        \App\User::where('id', $rec->id)->update(['vip_first_class_sor_error' => $savOnRes['msg']]);
                    } else {
                        \App\BoomerangInv::addToInventory($rec->id, $product->num_boomerangs);

                        \App\User::where('id', $rec->id)->update(['vip_first_class_sor_user' => 1]);
                    }
                } else {
                    \App\User::where('id', $rec->id)->update(['vip_first_class_sor_error' => $msg]);
                }
            } else {
                \App\User::where('id', $rec->id)->update(['vip_first_class_sor_error' => "user address can not be empty"]);
            }
            //idecide user create
            $idecideRes = $this->iDecideUserCreate($rec);
            if ($idecideRes['error'] == 1) {
                \App\User::where('id', $rec->id)->update(['vip_first_class_idecide_error' => $idecideRes['msg']]);
            } else {
                \App\User::where('id', $rec->id)->update(['vip_first_class_idecide_user' => 1]);
            }
            \App\User::where('id', $rec->id)->update(['is_vip_first_class_sor_idecide_processed' => 1]);
        }
    }

    private function saveOnUserCreate($userRec, $newProductId, $userAddress) {
        $checkAlreadyExistSaveOnAccount = \App\SaveOn::select('*')->where('user_id', $userRec->id)->first();
        if (empty($checkAlreadyExistSaveOnAccount)) {
            if ($newProductId == 2 || $newProductId == 3 || $newProductId == 4) {
                $sorRes = \App\SaveOn::SORCreateUser($userRec->id, $newProductId, $userAddress);
                $lastId = \App\Helper::logApiRequests($userRec->id, 'SOR', config('api_endpoints.SORCreateUser'), $sorRes['request']);
                \App\Helper::logApiResponse($lastId->id, json_encode($sorRes['response']));
                $sorResponse = $sorRes['response'];
                if ($sorResponse->ResultType == 'success') {
                    $request = $sorRes['request'];
                    \App\SaveOn::insert(['api_log' => $lastId->id, 'user_id' => $userRec->id, 'product_id' => $newProductId, 'sor_user_id' => $sorResponse->Account->UserId, 'sor_password' => $request['Password']]);
                    return array('error' => 0);
                } else {
                    return array('error' => 1, 'msg' => $sorResponse->Message);
                }
            }
        } else {
            $saveOnProductId = $checkAlreadyExistSaveOnAccount->product_id;
            if ($saveOnProductId != $saveOnProductId) {
                //transfer
                $transferToProductId = $newProductId;
                $sorRes = \App\SaveOn::transfer($userRec->id, $saveOnProductId->sor_user_id, $transferToProductId);
                $sorResponse = $sorRes['response'];
                if (!empty($sorResponse->status_code) && $sorResponse->status_code == 200) {
                    $request = $sorRes['request'];
                    \App\SaveOn::where('user_id', $userRec->id)->update(['product_id' => $transferToProductId]);
                    echo $sorResponse->status_code . "\n\n";
                } else {
                    echo $sorResponse . "\n\n";
                }
            }
        }
    }

    private function iDecideUserCreate($userRec) {
        $checkAlreadyExistIdecideAccount = \App\SaveOn::select('*')->where('user_id', $userRec->id)->first();
        if (empty($checkAlreadyExistIdecideAccount)) {
            $idecideRes = \App\IDecide::iDecideCreateUser($userRec->id);
            $lastId = \App\Helper::logApiRequests($userRec->id, 'IDECIDE', config('api_endpoints.iDecideCreateNewUser'), $idecideRes['request']);
            \App\Helper::logApiResponse($lastId->id, json_encode($idecideRes['response']));
            $idecideResponse = $idecideRes['response'];
            if (!empty($idecideResponse->errors)) {
                $error = 1;
                if (is_array($idecideResponse->errors)) {
                    $msg = implode('<br>', $idecideResponse->errors);
                } else {
                    $msg = $idecideResponse->errors;
                }
                return array('error' => $error, 'msg' => $msg);
            } else {
                $userAlredyExists = \App\IDecide::select('*')->where('user_id', $userRec->id)->first();
                $idecideRequest = $idecideRes['request'];
                if (empty($userAlredyExists)) {
                    \App\IDecide::insert(['api_log' => $lastId->id, 'user_id' => $userRec->id, 'idecide_user_id' => $idecideResponse->userId, 'password' => $idecideRequest['password'], 'login_url' => $idecideResponse->loginUrl]);
                } else {
                    \App\IDecide::where('id', $userAlredyExists->id)->update(['api_log' => $lastId->id, 'idecide_user_id' => $idecideResponse->userId, 'password' => $idecideRequest['password'], 'login_url' => $idecideResponse->loginUrl]);
                }
                return array('error' => 0);
            }
        } else {
            $idecideRes = \App\IDecide::updateIDecideUser($userRec->id, $userRec->username);
            $lastId = \App\Helper::logApiRequests($userRec->id, 'IDECIDE', config('api_endpoints.iDecideCreateNewUser'), $idecideRes['request']);
            \App\Helper::logApiResponse($lastId->id, json_encode($idecideRes['response']));
            $idecideResponse = $idecideRes['response'];
            if (!empty($idecideResponse->errors)) {
                $error = 1;
                if (is_array($idecideResponse->errors)) {
                    $msg = implode('<br>', $idecideResponse->errors);
                } else {
                    $msg = $idecideResponse->errors;
                }
                return array('error' => $error, 'msg' => $msg);
            } else {
                $userAlredyExists = \App\IDecide::select('*')->where('user_id', $userRec->id)->first();
                $idecideRequest = $idecideRes['request'];
                if (empty($userAlredyExists)) {
                    \App\IDecide::insert(['api_log' => $lastId->id, 'user_id' => $userRec->id, 'idecide_user_id' => $idecideResponse->userId, 'password' => $idecideRequest['password'], 'login_url' => $idecideResponse->loginUrl]);
                } else {
                    \App\IDecide::where('id', $userAlredyExists->id)->update(['api_log' => $lastId->id, 'idecide_user_id' => $idecideResponse->userId, 'password' => $idecideRequest['password'], 'login_url' => $idecideResponse->loginUrl]);
                }
                return array('error' => 0);
            }
            //update add business name and welcome url
        }
    }

    public function processPreEnrollments() {
        echo "processPreEnrollments";
        die;
        //2765
//        session_start();
        $tokenEx = new \tokenexAPI();
        $recs = DB::table('pre_enrollment_selection')
            ->join('users', 'users.id', '=', 'pre_enrollment_selection.userId')
            ->select('users.current_product_id', 'pre_enrollment_selection.*')
            ->whereIn('pre_enrollment_selection.productId', [2, 3, 4])
            ->where('users.current_product_id', 1)
            ->where('pre_enrollment_selection.is_processed', 0)
            ->where('users.id', 271)
            ->limit(1)
            ->get();
        foreach ($recs as $rec) {
            $currentProductId = $rec->current_product_id;
            $newProductId = $rec->productId;
            $upgProductId = '';
            if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_BASIC_PACK) {
                $upgProductId = \App\Product::ID_UPG_STAND_COACH;
            } else if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_VISIONARY_PACK) {
                $upgProductId = \App\Product::ID_UPG_STAND_BUSINESS;
            } else if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_STAND_FIRST;
            } else if ($currentProductId == \App\Product::ID_BASIC_PACK && $newProductId == \App\Product::ID_VISIONARY_PACK) {
                $upgProductId = \App\Product::ID_UPG_COACH_BUSINESS;
            } else if ($currentProductId == \App\Product::ID_BASIC_PACK && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_COACH_FIRST;
            } else if ($currentProductId == \App\Product::ID_VISIONARY_PACK && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_BUSINESS_FIRST;
            } else if (empty($upgProductId)) {
                //invalid product upgrades
                DB::table('pre_enrollment_selection')
                    ->where('id', $rec->id)
                    ->update([
                        'is_processed' => 1,
                        'is_process_success' => 0,
                        'process_msg' => 'invalid product upgrades',
                        'updated_at' => DB::raw('CURRENT_TIMESTAMP')
                    ]);
            }
            if (!empty($upgProductId)) {
                // pre enrollment record should not exist at order / order item table
                $result = $this->placeOrder($rec->userId, $upgProductId, $newProductId, $tokenEx);
                print_r($result);
                DB::table('pre_enrollment_selection')
                    ->where('id', $rec->id)
                    ->update([
                        'is_processed' => 1,
                        'is_process_success' => $result['error'] == 0 ? 1 : 0,
                        'process_msg' => $result['msg'],
                        'updated_at' => DB::raw('CURRENT_TIMESTAMP')
                    ]);
            }
        }
        echo "\nDone\n";
    }

    private function placeOrder($userId, $upgradeProductId, $newProductId, $tokenEx) {
        $error = 0;
        $msg = "";
        $product = \App\Product::getProduct($upgradeProductId);
        $amount = $product->price;
        //$deductedAmount = $amount;
        // deduct the IQ credit from product amount
        $legacyId = \App\User::getLegacyId($userId);
        //
        $iqCredit = 0;
        if ($legacyId != null) {
            $iqCredit = \App\IQCredits::getCreditAmount($legacyId);
        }
        $deductedAmount = $amount - $iqCredit;
        $paymentMethod = \App\PaymentMethod::getRec($userId, 1, \App\PaymentMethodType::TYPE_CREDIT_CARD);
        if ($paymentMethod == null) {
            $error = 1;
            $msg = "Payment method is not set";
            return array('error' => $error, 'msg' => $msg);
        }
        $userAddress = \App\Address::getRec($userId, \App\Address::TYPE_BILLING);
        if ($userAddress == null) {
            $error = 1;
            $msg = "Address not found";
            return array('error' => $error, 'msg' => $msg);
        }
        if (!empty($userAddress)) {
            $errorAddress = '';
            if (empty($userAddress->address1)) {
                $errorAddress .= "address1 empty<br>";
            } elseif (empty($userAddress->city)) {
                $errorAddress .= "city empty<br>";
            } else if (empty($userAddress->countrycode)) {
                $errorAddress .= "countrycode empty<br>";
            }
        }
        if (!empty($errorAddress)) {
            $error = 1;
            $msg = $errorAddress;
            return array('error' => $error, 'msg' => $msg);
        }

        if ($product == null) {
            $error = 1;
            $msg = "Product not found";
            return array('error' => $error, 'msg' => $msg);
        }
        $userRec = \App\User::find($userId);
        echo "User Id :- " . $userId . "\n";
        echo "Upgrade product id:- " . $upgradeProductId . "\n";
        echo "Current Product :- " . $userRec->current_product_id . "\n";
        echo "New Product :- " . $newProductId . "\n";
        echo "Product amount:- " . $deductedAmount . "\n";
        echo "Add boomerangs:- " . $product->num_boomerangs . "\n";
        if ($deductedAmount > 0) {
            //dummy token
            $paymentMethod->token = '4403938175327473';
            //payment process
            $response = $this->paymentProcess($tokenEx, $userRec, $paymentMethod, $deductedAmount, $product, $userAddress, $newProductId);
            print_r($response);
            $response['user_id'] = $userRec->id;
            $lg = json_encode($response);
            //testing purpose logging api response to json file
            file_put_contents('upgrade_user_payment_process.log.json', $lg . PHP_EOL, FILE_APPEND | LOCK_EX);
            if (isset($response['error']) == 1) {
                //payment error
                $error = 1;
                $msg = $response['msg'];
            } else if (key_exists('nmi', $response)) {
                //payment success
                $idecide = 0;
                $saveon = 0;
                //saveon error
                if (key_exists('SaveOne', $response)) {
                    if ($response['SaveOne']['error'] == 0) {
                        $saveon = 1;
                        //add boomerang
                        \App\BoomerangInv::addToInventory($userRec->id, $product->num_boomerangs);
                    }
                }
                //saveon error
                if (key_exists('iDecide', $response)) {
                    if ($response['iDecide']['error'] == 0) {
                        $idecide = 1;
                    }
                }
                //current product id update
                \App\User::setCurrentProductId($userRec->id, $upgradeProductId);
                //success
                \App\PreEnrollmentSelection::where('userId', $userId)->update(['idecide_user' => $idecide, 'saveon_user' => $saveon]);
                $authorization = $response['nmi']['authorization'];
                // create new order
                $orderId = \App\Order::addNew($userRec->id, $deductedAmount, $deductedAmount, $product->bv, $product->qv, $product->cv, $authorization, $paymentMethod->id, null, null);
                // create new order item
                \App\OrderItem::addNew($orderId, $product->id, 1, $amount, $product->bv, $product->qv, $product->cv);
                $error = 0;
                $msg = $response['nmi']['msg'];
            }
        } else {
            \App\PreEnrollmentSelection::addNewRec($userRec->id, \App\Product::ID_EB_FIRST_CLASS);
            $authorization = "";
            // create new order
            $orderId = \App\Order::addNew($userRec->id, 0, 0, $product->bv, $product->qv, $product->cv, $authorization, $paymentMethod->id, null, null);
            // create new order item
            \App\OrderItem::addNew($orderId, $product->id, 1, 0, $product->bv, $product->qv, $product->cv);
            $error = 0;
            $msg = "";
        }
        $result = array();
        $result['error'] = $error;
        $result['msg'] = $msg;
        return $result;
    }

    private function paymentProcess($tokenEx, $userRec, $paymentMethod, $deductedAmount, $product, $userAddress, $newProductId) {
        $allApiResponse = array();
        //fraud service
//        $tokenKountHashRes = $tokenEx->getKountHashValueAndTokenizeLog($paymentMethod->token);
//        $lastId = \App\Helper::logApiRequests($userRec->id, 'TOKENEX', $tokenKountHashRes['api_endpoint'], $tokenKountHashRes['request']);
//        \App\Helper::logApiResponse($lastId->id, json_encode($tokenKountHashRes['response']));
//        $tokenRes = $tokenKountHashRes['response'];
//        if (!$tokenRes->Success) {
//            $error = 1;
//            $msg = "TokenEx Error : " . $tokenRes->Error;
//            return array('error' => $error, 'msg' => $msg);
//        }
//        $response = \App\Kount::getKountScore($tokenRes->Hash, $userRec->id, $deductedAmount, $product, $userAddress);
//        if ($response['error'] == 1) {
//            $error = 1;
//            return array('error' => $error, 'msg' => (!empty($response['msg']) && is_array($response['msg']) ? implode('<br>', $response['msg']) : $response['msg']));
//        }
        // get credit card information ( payment method )
        $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
        $lastId = \App\Helper::logApiRequests($userRec->id, 'TOKENEX', config('api_endpoints.TOKENEXDetokenize'), $tokenRes['request']);
        \App\Helper::logApiResponse($lastId->id, json_encode($tokenRes['response']));
        $tokenRes = $tokenRes['response'];
        if (!$tokenRes->Success) {
            $error = 1;
            $msg = "TokenEx Error : " . $tokenRes->Error;
            return array('error' => $error, 'msg' => $msg);
        }
        echo "TokenEx Detokenize:-" . $tokenRes->Value . "\n";
        //nmi
        $nmiResult = \App\NMIGateway::processPayment($tokenRes->Value, $userRec->firstname, $userRec->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $deductedAmount, $userAddress->address1, $userAddress->city, $userAddress->stateprov, $userAddress->postalcode, $userAddress->countrycode);
        $lastId = \App\Helper::logApiRequests($userRec->id, 'TOKENEX', config('api_endpoints.TOKENEXProcessTransactionAndTokenize'), $nmiResult['request']);
        \App\Helper::logApiResponse($lastId->id, json_encode($nmiResult['response']));
        if ($nmiResult['error'] == 1) {
            $error = 1;
            $msg = "Payment Failed:<br/>" . $nmiResult['msg'];
            return array('error' => $error, 'msg' => $msg);
        } else {
            $allApiResponse["nmi"] = array('error' => 0, 'msg' => 'Payment processed', 'authorization' => $nmiResult['authorization']);
        }
        //saveOn
        $savOnRes = $this->saveOnUserCreate($userRec, $newProductId, $userAddress);
        if ($savOnRes['error'] == 1) {
            $error = 1;
            $msg = $savOnRes['msg'];
            $allApiResponse["SaveOne"] = array('error' => $error, 'msg' => $msg);
        } else {
            $allApiResponse["SaveOne"] = array('error' => 0);
        }
        //idecide
        $idecideRes = $this->iDecideUserCreate($userRec);
        if ($idecideRes['error'] == 1) {
            $error = 1;
            $msg = $idecideRes['msg'];
            $allApiResponse["iDecide"] = array('error' => $error, 'msg' => $msg);
        } else {
            $allApiResponse["iDecide"] = array('error' => 0);
        }
        return $allApiResponse;
    }

    private function importCusomter() {
        foreach (glob(storage_path('/from_store/customers/*.json')) as $filename) {
            $s = file_get_contents($filename);
            $recs = json_decode($s);
            foreach ($recs->customer as $customer) {
                \App\Customer::addNew($customer);
            }
            unlink($filename);
        }
        // dd('done');
    }

    private function importTransactions() {
        foreach (glob(storage_path('/from_store/transactions/*.json')) as $filename) {
            $s = file_get_contents($filename);
            $trans = json_decode($s);
            foreach ($trans as $tran) {
                \App\Transaction::addNew($tran);
            }
            unlink($filename);
        }
        // dd('done');
    }

    private function importTVcsv() {
        foreach (glob(storage_path('/tv_csv/*.csv')) as $filename) {
            $file = fopen($filename, 'r');
            $row = 0;
            while (($line = fgetcsv($file)) !== FALSE) {
                // skip header
                if ($row > 0) {
                    \App\User::importFromTV($line);
                }
                $row++;
            }
            fclose($file);
            //unlink($filename);
        }
        // dd('imported');
    }

    private function assignTVSponsor() {
        foreach (glob(storage_path('/tv_csv/*.csv')) as $filename) {
            $file = fopen($filename, 'r');
            $row = 0;
            while (($line = fgetcsv($file)) !== FALSE) {
                // skip header
                if ($row > 0) {
                    \App\User::assignTVsponsor($line);
                }
                $row++;
            }
            fclose($file);
            //unlink($filename);
        }
        // dd('assigned');
    }

    private function importTVFirstClass() {
        foreach (glob(storage_path('/tv_first_class/*.csv')) as $filename) {
            $file = fopen($filename, 'r');
            $row = 0;
            while (($line = fgetcsv($file)) !== FALSE) {
                // skip header
                if ($row > 0) {
                    \App\User::makeTVuserFirstClass($line[1]);
                }
                $row++;
            }
            fclose($file);
            //unlink($filename);
        }
        // dd('done');
    }

    private function makeUsernameUnique() {
        $recs = DB::select('select username, count(1) from users group by username HAVING count(1) > 1');
        echo count($recs);
        foreach ($recs as $rec) {
            if (!\utill::isNullOrEmpty($rec->username)) {
                echo $rec->username . "<br/>";
                $userRecs = DB::table('users')
                    ->where('username', $rec->username)
                    ->get();
                $count = 0;
                foreach ($userRecs as $userRec) {
                    if ($count > 0) {
                        $newusername = $rec->username . $count;
                        //echo $newusername;
                        DB::table('users')
                            ->where('id', $userRec->id)
                            ->update([
                                'username' => $newusername
                            ]);
                    }
                    $count++;
                }
            }
        }
        // dd('done');
    }

    private function fixTvUsernameIssues() {
        $recs = DB::select('select username, count(1) from users group by username HAVING count(1) > 1');
        foreach ($recs as $rec) {
            if (!\utill::isNullOrEmpty($rec->username)) {
                echo $rec->username . "<br/>";
                $userRecs = DB::table('users')
                    ->select('id')
                    ->where('username', $rec->username)
                    ->where('is_tv_user', 0)
                    ->first();
                if (!empty($userRecs)) {
                    $newUsername = $this->setNewUsername($userRecs->id, $rec->username);
                    echo $userRecs->id . " New: " . $newUsername . "<br/>";
                    DB::table('users')
                        ->where('id', $userRecs->id)
                        ->update([
                            'username' => $newUsername
                        ]);
                }
            }
        }
    }

    private function setNewUsername($userId, $username, $c = 1) {
        $newUsername = strtolower($username) . $c;
        $count = DB::table('users')
            ->where('id', '!=', $userId)
            ->where('username', $newUsername)
            ->where('is_tv_user', 0)
            ->count();
        if ($count == 0)
            return $newUsername;
        else {
            $c++;
            return $this->setNewUsername($userId, $username, $c);
        }
    }

}
