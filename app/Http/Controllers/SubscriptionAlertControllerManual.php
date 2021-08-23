<?php

namespace App\Http\Controllers;

use App\Address;
use App\BoomerangInv;
use App\EwalletTransaction;
use App\Helper;
use App\NMIGateway;
use App\Order;
use App\OrderItem;
use App\PaymentMethod;
use App\PaymentMethodType;
use App\SubscriptionHistory;
use App\User;
use DB;

class SubscriptionAlertControllerManual extends Controller
{

    const SUBSCRIPTION_SUCCESS = 1;
    const SUBSCRIPTION_FAIL = 0;
    const SUBSCRIPTION_PAYMENT_FAIL_MSG = "Monthly subscription payment fail";
    const SUBSCRIPTION_PAYMENT_SUCCESS_MSG = "Monthly subscription payment success";
    const NEXT_ATTEMPT_DURATION_DAYS = 2; // - 48 hours.

    public $payment_method_id = 0;

    public function __construct() {

    }

    public function is_user_exist($users) {
        $user_check = json_decode(json_encode($users));
        if (empty($user_check)) {
            return false;
        } else {
            return true;
        }
    }

    public function make_deactivate($user_id, $type = 1) {
        $user = User::where('id', $user_id);
        if ($type == 1) {
            $user->update(['is_sites_deactivate' => 1]);
        } elseif ($type == 2) {
            $user->update(['is_deactivate' => 1]);
        }
    }

    public function make_activate($user_id, $type = 1) {
        $user = User::where('id', $user_id);
        if ($type == 1) {
            $user->update(['is_sites_deactivate' => 0]);
        } elseif ($type == 2) {
            $user->update(['is_deactivate' => 0]);
        }
    }

    public function RunCronProcess($user = false, $param2 = false) {

//        $users = User::where('account_status', User::ACC_STATUS_APPROVED)
//            ->where('usertype', UserType::TYPE_DISTRIBUTOR)
//            ->where('current_product_id', '!=', Product::ID_PREMIUM_FIRST_CLASS)
//            ->whereDate('next_subscription_date', date('Y-m-d'))
//            ->orderBy('id', 'desc')
//            ->get();
        $users = DB::select("SELECT * FROM users WHERE distid not in  ('TSA6517220','TSA4820016','TSA1919454','TSA7724165') and  id IN (" . DB::raw("SELECT user_id FROM subscription_history WHERE attempted_date >= '2019-06-05' AND attempted_date <= '2019-06-06' and response like '%De-Tokenize%' and attempt_count = 2") . ")");



//        echo date('Y-m-01');
        // - Processing each user.
        foreach ($users as $user) {
            try {
                echo $this->make_response_output("_________________________________", $user->distid . "-" . $user->id);
                $product = SubscriptionHistory::getSubscriptionProduct($user->id);
                echo $this->make_response_output('User:', $user->firstname . " " . $user->lastname . " - " . $user->email);
                echo $this->make_response_output('Product:', $product->sku . " " . $product->productname . " - " . $product->price);

                // Check user has product for payment.
                if ($product) {

                    $orderBV = $product->bv;
                    $orderQV = $product->qv;
                    $orderCV = $product->cv;
                    $numberOfBoomerangs = $product->num_boomerangs;
                    $orderSubtotal = $product->price;
                    $orderTotal = $product->price;
                    $user_attempt_count = $user->subscription_attempts + 1;

                    echo $this->make_response_output('Num boomerang before :', $numberOfBoomerangs . "|" . @BoomerangInv::getInventory($user->id)->pending_tot . "|" . @BoomerangInv::getInventory($user->id)->available_tot);

                    // - Check and CREATE payment method if not exist any.
                    if ($user->subscription_payment_method_id == null || trim($user->subscription_payment_method_id) == '') {

                        // - CREATE payment method with TYPE eWallet.
                        $user->cvv = null;
                        $payment_method_id = PaymentMethod::addNewRec($user->id, null, null, null, PaymentMethodType::TYPE_E_WALET, $user);
                        User::where('id', $user->id)->update(['subscription_payment_method_id' => $payment_method_id]);
                    } else {
                        $payment_method_id = $user->subscription_payment_method_id;
                    }

                    $this->payment_method_id = $payment_method_id;
                    // - Getting payment method for current user.
                    $payment_method = PaymentMethod::where('userID', $user->id)
                        ->where('id', $payment_method_id)
                        ->where(function ($query) {
                            $query->where('is_deleted', '=', 0)
                                ->orWhereNull('is_deleted');
                        })
                        ->first();
                    // -  Payment process continuing.
                    if ($payment_method) {
                        // - Checking payment method type/ if empty set to eWallet.
                        if ($payment_method->pay_method_type == PaymentMethodType::TYPE_E_WALET || $payment_method->pay_method_type == null) {
                            echo $this->make_response_output('Payment method: ', 'eWallet');

                            // -- ||eWallet|| --
                            // -  Checking balance is enough for payment.
                            if ($user->estimated_balance >= $orderTotal) {

                                // - ADD order and purchase SENT Email to user.
                                $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, $payment_method_id, null, null, null, null);
                                OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                                EwalletTransaction::addPurchase($user->id, EwalletTransaction::MONTHLY_SUBSCRIPTION, (-$product->price), $orderId);
                                BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);

                                \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);

                                // - Check gflag and Reached maximum payment attempt.
                                if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                    //- SET SRO and iDECIED accounts ACTIVATE.
                                    Helper::reActivateIdecideUser($user->id);
                                    Helper::reActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_SUCCESS_MSG);
                                    $this->make_activate($user->id, 1);
                                }
                                $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via eWallet');

                                echo $this->make_response_output('Num boomerang after :', BoomerangInv::getInventory($user->id)->pending_tot . "|" . BoomerangInv::getInventory($user->id)->available_tot);
                                echo $this->make_response_output('Payment Success: ', 'Subscription payment success via eWallet');

                            } else {

                                //							User::where( 'id', $user->id )
                                //							    ->update( [ 'is_cron_fail' => 1 ] );
                                //							continue;
                                //							( "fail-----------" );

                                echo $this->make_response_output('Payment Fail: (a) ', 'Estimated balance is not enough!');

                                // - Check other payment method exist.
                                $availablePaymentMethod = $this->CheckAvailablePaymentMethod($user, $payment_method->pay_method_type);

                                if ($availablePaymentMethod !== false) {
                                    if ($payment_method->primary == 1 && $availablePaymentMethod->primary == 1) {

                                    } else {
                                        $this->UpdateSubscriptionHistoryOnly($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - Estimated balance is not enough! Trying with another payment method');

                                        //-- ||Credit card|| --
                                        // -- Getting payment details.
                                        $paymentMethod = $availablePaymentMethod;
                                        $this->payment_method_id = $paymentMethod->id;

                                        echo $this->make_response_output('Status: ', 'Trying with another payment method,  ID: ' . $this->payment_method_id);

                                        // - De-Tokenize Credit card details.
                                        $tokenEx = new \tokenexAPI();
                                        $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
                                        $tokenRes = $tokenRes['response'];

                                        // - Check De-Tokenize is success.
                                        if (!$tokenRes->Success) {
                                            // - Check gflag and Reached maximum payment attempt.
                                            echo $this->make_response_output('Payment fail: (b) ', 'Subscription payment fail - (De-Tokenize) via card - ' . $tokenRes->Error);

                                            if ($user_attempt_count >= 2 || $user->gflag == 1) {
                                                //-  SRO and iDECIED account DEACTIVATION.
                                                Helper::deActivateIdecideUser($user->id);
                                                Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                                $this->make_deactivate($user->id, 1);
                                                $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error, true, true);
                                            } else {
                                                $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error);
                                            }


                                            \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $tokenRes->Error);
                                            continue;
                                        }

                                        $billingAddress = Address::find($paymentMethod->bill_addr_id);

                                        // - Process payment with credit card.
                                        $nmiResult = NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode);


                                        if ($nmiResult['error'] == 1) {

                                            echo $this->make_response_output('Payment fail: (c) ', 'Subscription payment fail via card - ' . $nmiResult['msg']);

                                            // - Check gflag and Reached maximum payment attempt.
                                            if ($user_attempt_count >= 2 || $user->gflag == 1) {

                                                //-  SRO and iDECIED account DEACTIVATION.

                                                Helper::deActivateIdecideUser($user->id);
                                                Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                                $this->make_deactivate($user->id, 1);
                                                $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail via card - ' . $nmiResult['msg'], true, true);
                                            } else {
                                                $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg']);
                                            }


                                            \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $nmiResult['msg']);

                                            continue;
                                        }

                                        $authorization = $nmiResult['authorization'];

                                        // - Success payment -  Add payment to our system.
                                        $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethod->id, null, null, null, null);
                                        OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                                        BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);


                                        if ($availablePaymentMethod->is_subscription == 1) {
                                            echo $this->make_response_output('Payment success: ', 'Subscription payment success via subscription card');
                                            $card_type = "Subscription Card";
                                        } elseif ($availablePaymentMethod->primary == 1) {
                                            echo $this->make_response_output('Payment success: ', 'Subscription payment success via primary card');
                                            $card_type = "Primary Card";
                                        } else {
                                            echo $this->make_response_output('Payment success: ', 'Subscription payment success via secondary card');
                                            $card_type = 'Secondary Card';
                                        }

                                        $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via ' . $card_type);

                                        // - Check gflag and Reached maximum payment attempt.
                                        if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                            //-  SRO and iDECIED account ACTIVATION.
                                            // -|| IDecide::enableUser( $user->id );
                                            // -|| SaveOn::enableUser( $product->id, $user->email, $user->phonenumber, $user->id );
                                        }

                                        echo $this->make_response_output('Num boomerang before :', $numberOfBoomerangs . "|" . @BoomerangInv::getInventory($user->id)->pending_tot . "|" . @BoomerangInv::getInventory($user->id)->available_tot);

                                        \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);
                                        continue;
                                    }
                                }
                                // - Check gflag and Reached maximum payment attempt.
                                if ($user_attempt_count >= 2 || $user->gflag == 1) {
                                    //-  SRO and iDECIED account DEACTIVATION.

                                    Helper::deActivateIdecideUser($user->id);
                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                    $this->make_deactivate($user->id, 1);
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - Estimated balance is not enough! ', true, true);
                                } else {
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - Estimated balance is not enough! ');
                                }

                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - Estimated balance is not enough!');

                                continue;
                            }
                            continue;
                        } elseif ($payment_method->pay_method_type == PaymentMethodType::TYPE_CREDIT_CARD || $payment_method->pay_method_type = PaymentMethodType::TYPE_SECONDARY_CC) {

                            echo $this->make_response_output('Payment method: ', 'Credit Card');

                            //-- ||Credit card|| Main --
                            // -- Getting payment details.
                            $paymentMethod = $payment_method;

                            // - De-Tokenize Credit card details.
                            $tokenEx = new \tokenexAPI();
                            $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
                            $tokenRes = $tokenRes['response'];

                            // - Check De-Tokenize is success.
                            if (!$tokenRes->Success) {

                                echo $this->make_response_output('Payment fail: (d) ', 'Subscription payment fail via card - (De-Tokenize) (Selected) ' . $tokenRes->Error);

                                $this->UpdateSubscriptionHistoryOnly($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize) (Selected)' . $tokenRes->Error);

                                echo $this->make_response_output('Payment status: ', 'Trying eWallet ');
                                if ($user->estimated_balance >= $orderTotal) {
                                    // - ADD order and purchase SENT Email to user.
                                    $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, $payment_method_id, null, null, null, null);
                                    OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                                    EwalletTransaction::addPurchase($user->id, EwalletTransaction::MONTHLY_SUBSCRIPTION, (-$product->price), $orderId);
                                    BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);

                                    \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);

                                    // - Check gflag and Reached maximum payment attempt.
                                    if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                        //- SET SRO and iDECIED accounts ACTIVATE.
                                        // -|| IDecide::enableUser( $user->id );
                                        // -|| SaveOn::enableUser( $product->id, $user->email, $user->phonenumber, $user->id );
                                    }
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via eWallet');
                                    echo $this->make_response_output('Num boomerang after :', BoomerangInv::getInventory($user->id)->pending_tot . "|" . BoomerangInv::getInventory($user->id)->available_tot);
                                    echo $this->make_response_output('Payment Success: ', 'Subscription payment success via eWallet');
                                    continue;
                                } else {
                                    $this->UpdateSubscriptionHistoryOnly($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail via eWallet');
                                    echo $this->make_response_output('Payment fail: ', "eWallet balance not enough for subscription");
                                }

                                if ($paymentMethod->is_subscription != 1) {

                                    // - Check other payment method exist.
                                    $availablePaymentMethod = $this->CheckAvailablePaymentMethod($user, $payment_method->pay_method_type);
                                    if ($availablePaymentMethod !== false) {
                                        if ($payment_method->primary == 1 && $availablePaymentMethod->primary == 1) {

                                        } else {
                                            //-- ||Credit card|| --
                                            // -- Getting payment details.
                                            $paymentMethod = $availablePaymentMethod;
                                            $this->payment_method_id = $paymentMethod->id;
                                            echo $this->make_response_output('Payment status: ', 'Trying another payment method');

                                            // - De-Tokenize Credit card details.
                                            $tokenEx = new \tokenexAPI();
                                            $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
                                            $tokenRes = $tokenRes['response'];

                                            // - Check De-Tokenize is success.
                                            if (!$tokenRes->Success) {

                                                echo $this->make_response_output('Payment fail: (e) ', 'Subscription payment fail via card - (De-Tokenize)' . $tokenRes->Error);

                                                // - Check gflag and Reached maximum payment attempt.
                                                if ($user_attempt_count >= 2 || $user->gflag == 1) {
                                                    //-  SRO and iDECIED account DEACTIVATION.

                                                    Helper::deActivateIdecideUser($user->id);
                                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                                    $this->make_deactivate($user->id, 1);
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error, true, true);
                                                } else {
                                                    $this->UpdatessSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error);
                                                }


                                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $tokenRes->Error);
                                                continue;
                                            }

                                            $billingAddress = Address::find($paymentMethod->bill_addr_id);

                                            // - Process payment with credit card.
                                            $nmiResult = NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode);

                                            if ($nmiResult['error'] == 1) {

                                                echo $this->make_response_output('Payment fail: (f) ', 'Subscription payment fail via card - ' . $nmiResult['msg']);

                                                // - Check gflag and Reached maximum payment attempt.
                                                if ($user_attempt_count >= 2 || $user->gflag == 1) {

                                                    //-  SRO and iDECIED account DEACTIVATION.

                                                    Helper::deActivateIdecideUser($user->id);
                                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                                    $this->make_deactivate($user->id, 1);
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg'], true, true);
                                                } else {
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg']);
                                                }


                                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $nmiResult['msg']);

                                                continue;
                                            }

                                            $authorization = $nmiResult['authorization'];

                                            // - Success payment -  Add payment to our system.
                                            $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethod->id, null, null, null, null);
                                            OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                                            BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);

                                            $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via card');

                                            if ($availablePaymentMethod->is_subscription == 1) {
                                                echo $this->make_response_output('Payment success: ', 'Subscription payment success via subscription card');
                                            } elseif ($availablePaymentMethod->primary == 1) {
                                                echo $this->make_response_output('Payment success: ', 'Subscription payment success via primary card');
                                            } else {
                                                echo $this->make_response_output('Payment success: ', 'Subscription payment success via secondary card');
                                            }

                                            // - Check gflag and Reached maximum payment attempt.
                                            if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                                //-  SRO and iDECIED account ACTIVATION.
                                                // -|| IDecide::enableUser( $user->id );
                                                // -|| SaveOn::enableUser( $product->id, $user->email, $user->phonenumber, $user->id );
                                            }
                                            \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);
                                            continue;
                                        }
                                    }
                                }

                                // - Check gflag and Reached maximum payment attempt.
                                if ($user_attempt_count >= 2 || $user->gflag == 1) {
                                    //-  SRO and iDECIED account DEACTIVATION.

                                    Helper::deActivateIdecideUser($user->id);
                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                    $this->make_deactivate($user->id, 1);
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error, true, true);
                                } else {
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error);
                                }

                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $tokenRes->Error);
                                continue;
                            }

                            $billingAddress = Address::find($paymentMethod->bill_addr_id);

                            // - Process payment with credit card.
                            $nmiResult = NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode);

                            if ($nmiResult['error'] == 1) {

                                echo $this->make_response_output('Payment fail: (g) ', 'Subscription payment fail via card - (selected) - ' . $nmiResult['msg']);
                                $this->UpdateSubscriptionHistoryOnly($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (Selected) ' . $nmiResult['msg']);

                                echo $this->make_response_output('Payment status: ', 'Trying eWallet ');
                                if ($user->estimated_balance >= $orderTotal) {
                                    // - ADD order and purchase SENT Email to user.
                                    $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, null, $payment_method_id, null, null, null, null);
                                    OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                                    EwalletTransaction::addPurchase($user->id, EwalletTransaction::MONTHLY_SUBSCRIPTION, (-$product->price), $orderId);
                                    BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);

                                    \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);

                                    // - Check gflag and Reached maximum payment attempt.
                                    if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                        //- SET SRO and iDECIED accounts ACTIVATE.
                                        // -|| IDecide::enableUser( $user->id );
                                        // -|| SaveOn::enableUser( $product->id, $user->email, $user->phonenumber, $user->id );
                                    }
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via eWallet');
                                    echo $this->make_response_output('Num boomerang after :', BoomerangInv::getInventory($user->id)->pending_tot . "|" . BoomerangInv::getInventory($user->id)->available_tot);
                                    echo $this->make_response_output('Payment Success: ', 'Subscription payment success via eWallet');
                                    continue;
                                } else {
                                    $this->UpdateSubscriptionHistoryOnly($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment success via eWallet Fail');
                                    echo $this->make_response_output('Payment fail: ', "eWallet balance not enough for subscription");
                                }

                                if ($paymentMethod->is_subscription != 1) {
                                    // - Check other payment method exist.
                                    $availablePaymentMethod = $this->CheckAvailablePaymentMethod($user, $payment_method->pay_method_type);
                                    if ($availablePaymentMethod !== false) {
                                        if ($payment_method->primary == 1 && $availablePaymentMethod->primary == 1) {

                                        } else {
                                            echo $this->make_response_output('Payment status: ', 'Trying with another payment method');

                                            //-- ||Credit card|| --
                                            // -- Getting payment details.
                                            $paymentMethod = $availablePaymentMethod;
                                            $this->payment_method_id = $paymentMethod->id;
                                            // - De-Tokenize Credit card details.
                                            $tokenEx = new \tokenexAPI();
                                            $tokenRes = $tokenEx->detokenizeLog(config('api_endpoints.TOKENEXDetokenize'), $paymentMethod->token);
                                            $tokenRes = $tokenRes['response'];

                                            // - Check De-Tokenize is success.
                                            if (!$tokenRes->Success) {

                                                echo $this->make_response_output('Payment fail: (h) ', 'Subscription payment fail via card - (De-Tokenize)' . $tokenRes->Error);

                                                // - Check gflag and Reached maximum payment attempt.
                                                if ($user_attempt_count >= 2 || $user->gflag == 1) {
                                                    //-  SRO and iDECIED account DEACTIVATION.

                                                    Helper::deActivateIdecideUser($user->id);
                                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                                    $this->make_deactivate($user->id, 1);
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error, true, true);
                                                } else {
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - (De-Tokenize)' . $tokenRes->Error);
                                                }


                                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $tokenRes->Error);
                                                continue;
                                            }

                                            $billingAddress = Address::find($paymentMethod->bill_addr_id);

                                            // - Process payment with credit card.
                                            $nmiResult = NMIGateway::processPayment($tokenRes->Value, $paymentMethod->firstname, $paymentMethod->lastname, $paymentMethod->expMonth, $paymentMethod->expYear, $paymentMethod->cvv, $orderTotal, $billingAddress->address1, $billingAddress->city, $billingAddress->stateprov, $billingAddress->postalcode, $billingAddress->countrycode);

                                            if ($nmiResult['error'] == 1) {

                                                echo $this->make_response_output('Payment fail: (i) ', 'Subscription payment fail via card - ' . $nmiResult['msg']);

                                                // - Check gflag and Reached maximum payment attempt.
                                                if ($user_attempt_count >= 2 || $user->gflag == 1) {

                                                    //-  SRO and iDECIED account DEACTIVATION.

                                                    Helper::deActivateIdecideUser($user->id);
                                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                                    $this->make_deactivate($user->id, 1);
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg'], true, true);
                                                } else {
                                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg']);
                                                }


                                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $nmiResult['msg']);

                                                continue;
                                            }

                                            $authorization = $nmiResult['authorization'];

                                            // - Success payment -  Add payment to our system.
                                            $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethod->id, null, null, null, null);
                                            OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                                            BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);

                                            $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via card');

                                            if ($availablePaymentMethod->is_subscription == 1) {
                                                echo $this->make_response_output('Payment success: ', 'Subscription payment success via subscription card');
                                            } elseif ($availablePaymentMethod->primary == 1) {
                                                echo $this->make_response_output('Payment success: ', 'Subscription payment success via primary card');
                                            } else {
                                                echo $this->make_response_output('Payment success: ', 'Subscription payment success via secondary card');
                                            }

                                            // - Check gflag and Reached maximum payment attempt.
                                            if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                                //-  SRO and iDECIED account ACTIVATION.
                                                // -|| IDecide::enableUser( $user->id );
                                                // -|| SaveOn::enableUser( $product->id, $user->email, $user->phonenumber, $user->id );
                                            }
                                            \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);
                                            continue;
                                        }
                                    }
                                }
                                // echo $this->make_response_output( 'Payment fail: (j) ', 'Subscription payment fail via card - ' . $nmiResult['msg'] );
                                // - Check gflag and Reached maximum payment attempt.
                                if ($user_attempt_count >= 2 || $user->gflag == 1) {

                                    //-  SRO and iDECIED account DEACTIVATION.

                                    Helper::deActivateIdecideUser($user->id);
                                    Helper::deActivateSaveOnUser($user->id, $product->id, $user->distid, self::SUBSCRIPTION_PAYMENT_FAIL_MSG);
                                    $this->make_deactivate($user->id, 1);
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (2), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg'], true, true);
                                } else {
                                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - ' . $nmiResult['msg']);
                                }

                                \MyMail::sendSubscriptionRecurringFailed($user->firstname, $user->lastname, $user->id, $user->email, 'Subscription payment fail - ' . $nmiResult['msg']);

                                continue;
                            }

                            $authorization = $nmiResult['authorization'];

                            // - Success payment -  Add payment to our system.
                            $orderId = Order::addNew($user->id, $orderSubtotal, $orderTotal, $orderBV, $orderQV, $orderCV, $authorization, $paymentMethod->id, null, null, null, null);
                            OrderItem::addNew($orderId, $product->id, 1, $orderTotal, $orderBV, $orderQV, $orderCV);
                            BoomerangInv::addToInventory($user->id, $numberOfBoomerangs);

                            $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), 0, self::SUBSCRIPTION_SUCCESS, $product->id, 'Subscription payment success via card');
                            echo $this->make_response_output('Payment success: ', 'Subscription payment success via card');
                            // - Check gflag and Reached maximum payment attempt.
                            if ($user->gflag == 1 || $user_attempt_count >= 2) {
                                //-  SRO and iDECIED account ACTIVATION.
                                // -|| IDecide::enableUser( $user->id );
                                // -|| SaveOn::enableUser( $product->id, $user->email, $user->phonenumber, $user->id );
                            }

                            echo $this->make_response_output('Num boomerang before :', BoomerangInv::getInventory($user->id)->pending_tot . "|" . BoomerangInv::getInventory($user->id)->available_tot);


                            \MyMail::sendSubscriptionRecurringSuccess($user->firstname, $user->lastname, $user->id, $user->email);
                        } else {

                            // -  Does't match any payment method
                            $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), ($user_attempt_count), self::SUBSCRIPTION_FAIL, $product->id, 'Subscription payment fail - Wrong Payment Method');
                        }
                    } else {
                        $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (0), self::SUBSCRIPTION_FAIL, $product->id, "Subscription payment fail - Doesn't match any payment method for this user");
                    }
                } else {

                    // - Product is empty for current user
                    $this->UpdateSubscriptionAttempt($user->id, date("Y-m-d"), (0), self::SUBSCRIPTION_FAIL, 0, "Subscription payment fail - Doesn't match any product for the user");
                }
            } catch (\Exception $ex) {
                $this->UpdateSubscriptionHistoryOnly($user->id, date("Y-m-d"), (0), self::SUBSCRIPTION_FAIL, 0, 'Subscription exceptions - ' . $ex->getMessage());
            }
        }
        //
        $this->deactvivateIdecideSorUserAfterSubscriptionFail();
        $this->terminateUserAfterSubscriptionFail();
    }

    private function terminateUserAfterSubscriptionFail() {
        $users = \App\User::terminateUserDetailsAfterSubscriptionFail();
        foreach ($users as $user) {
            \App\User::where('id', $user->id)->update(['account_status' => \App\User::ACC_STATUS_TERMINATED]);
        }
    }

    private function deactvivateIdecideSorUserAfterSubscriptionFail() {
        $users = \App\User::gracePeriodUsers();
        foreach ($users as $user) {
            //            $idecide = \App\IDecide::getIDecideUserId($user->id);
            //            if (!empty($idecide)) {
            //                \App\Helper::deActivateIdecideUser($user->id);
            //            }
            $sor = \App\SaveOn::getSORUserInfo($user->id);
            if (!empty($sor)) {
                \App\Helper::deActivateSaveOnUser($user->id, $sor->product_id, $user->distid, \App\SaveOn::USER_DISABLE_FOR_SUBSCRIPTION_FAIL);
            }
            \App\User::where('id', $user->id)->update(['gflag' => 0]);
        }
    }

    public function CheckAvailablePaymentMethod($userObj, $current_payment_method) {
        $payment_methods = PaymentMethod::where('userID', $userObj->id)
            ->where(function ($query) {
                $query->where('is_deleted', '=', 0)
                    ->orWhereNull('is_deleted');
            })
            ->get();
        $newPaymentMethod = false;
        if ($payment_methods) {
            foreach ($payment_methods as $payment_method) {
                if ($payment_method->is_subscription == 1) {
                    $newPaymentMethod = $payment_method;
                    break;
                } elseif ($payment_method->primary == 1 && $current_payment_method == PaymentMethodType::TYPE_E_WALET) {
                    $newPaymentMethod = $payment_method;
                }
            }
        }

        return $newPaymentMethod;
    }

    public function UpdateSubscriptionHistoryOnly($user_id, $attempt_date, $attempt_count, $status, $products_id, $response = null, $subscription_date = false) {
        $user = User::find($user_id);
        if ($user) {

            // - Add subscription history.
            $subscription = new SubscriptionHistory();
            $subscription->user_id = $user_id;
            $subscription->subscription_product_id = $products_id;
            $subscription->attempted_date = $attempt_date;
            $subscription->attempt_count = $attempt_count;
            $subscription->payment_method_id = $this->payment_method_id;
            $subscription->response = $response;
            $subscription->next_attempt_date = $user->next_subscription_date;
            $subscription->status = $status;
            $subscription->save();
        } else {

        }
    }

    public function UpdateSubscriptionAttempt($user_id, $attempt_date, $attempt_count, $status, $products_id, $response = null, $userAttemptFail = false, $more_chances_count = false) {

        $user = User::find($user_id);
        if ($user) {

            // - Get attempt count MAX = 2
            $attempt_count = ( $attempt_count > 1 ) ? 2 : $attempt_count;

            // - User has passed attempt limit OR gflag user(final chance for user).
            if (!$userAttemptFail) {
                $userAttemptFail = ( $attempt_count > 1 ) ? true : false;
            }

            // -  Getting next subscription date.
            $subscription_date = $this->GetNextSubscriptionDate($user->created_date, $status, $user->original_subscription_date, $userAttemptFail);

            if ($attempt_count !== false) {

                if ($subscription_date) {

                    // - Update next and original subscription date.
                    $update_query = [
                        'next_subscription_date' => $subscription_date['next_subscription_date'],
                        'subscription_attempts' => $attempt_count,
                        'original_subscription_date' => $subscription_date['original_subscription_date'],
                    ];

                    // - Previous month payment failed user.
                    if ($more_chances_count != false) {
                        // -  Again 2nd chance payment failed user.
                        if ($userAttemptFail) {
                            $update_query['subscription_attempts'] = 0;
                        }
                    }

                    // - gFlagged users payment success.
                    if ($status == self::SUBSCRIPTION_SUCCESS) {
                        $update_query['gflag'] = 0;
                        $update_query['payment_fail_count'] = 0;
                    }


                    User::where('id', $user_id)
                        ->update($update_query);

                    $user_fail_month_count = $user->payment_fail_count;
                    // - Payment failed on current month.
                    if ($more_chances_count != false) {
                        if ($userAttemptFail) {
                            $user_fail_month_count = $user_fail_month_count + 1;
                            User::where('id', $user_id)
                                ->update(['payment_fail_count' => $user_fail_month_count]);
                        }
                    }

                    echo $this->make_response_output("Update date: ", 'Next and original subscription date');
                    print_r($update_query);
                    echo $this->make_response_output("Total monthly payment fail count: ", '--' . $user_fail_month_count . '--');

                    // - Add subscription history.
                    $subscription = new SubscriptionHistory();
                    $subscription->user_id = $user_id;
                    $subscription->subscription_product_id = $products_id;
                    $subscription->attempted_date = $attempt_date;
                    $subscription->attempt_count = $attempt_count;
                    $subscription->payment_method_id = $this->payment_method_id;
                    $subscription->response = $response;
                    $subscription->next_attempt_date = $subscription_date['next_subscription_date'];
                    $subscription->status = $status;
                    $subscription->save();
                }
            } else {

            }
        } else {

        }
    }

    public function GetNextSubscriptionDate($created_date, $status = null, $last_subscription_date = false, $userAttemptFail = false) {

        $next_subscription_date = null;
        $original_subscription_date = null;

        // $create_date_array = explode('-', $created_date);

        // -  Check last subscription date exist
        if ($last_subscription_date && trim($last_subscription_date) != '') {

            // -  Set month and year for calculate next subscription date.
            $last_subscription_date_array = explode('-', $last_subscription_date);

            $current_month = ltrim($last_subscription_date_array[1], 0);
            $current_year = ltrim($last_subscription_date_array[0], 0);
            $created_day = ltrim($last_subscription_date_array[2], 0);
        } else {
            $current_month = ltrim(date('m'), 0);
            $current_year = ltrim(date('Y'), 0);
            $created_day = ltrim(date('d'), 0);
        }

        // - Check day is exist.
//        if (isset($create_date_array[2])) {
        //			$created_day = ltrim( $create_date_array[2], 0 ); // - for created date filter.
        //$created_day = ltrim(date('d'), 0);

        // Set day limit to below 26.
        if ($created_day <= 25) {
            $new_sub_day = $created_day;
        } else {
            $new_sub_day = 25;
        }

        if ($status === self::SUBSCRIPTION_SUCCESS) {
            // - Success payment..

            // - Checking month and SET next month for next subscription
            if ($current_month < 12) {
                $original_subscription_date = $current_year . "-" . $this->AddZeroPrefix($current_month + 1) . "-" . $this->AddZeroPrefix($new_sub_day);
                $next_subscription_date = $current_year . "-" . $this->AddZeroPrefix($current_month + 1) . "-" . $this->AddZeroPrefix($new_sub_day);
            } else {
                $original_subscription_date = ($current_year + 1) . "-" . $this->AddZeroPrefix(1) . "-" . $this->AddZeroPrefix($new_sub_day);
                $next_subscription_date = ($current_year + 1) . "-" . $this->AddZeroPrefix(1) . "-" . $this->AddZeroPrefix($new_sub_day);
            }
        } elseif ($status === self::SUBSCRIPTION_FAIL) {

            // - Fail payment..
            // - Checking month and SET next day for next subscription payment attempt.
            if ($current_month < 12) {
                $original_subscription_date = $current_year . "-" . $this->AddZeroPrefix($current_month) . "-" . $this->AddZeroPrefix($new_sub_day);
                $next_subscription_date = $current_year . "-" . $this->AddZeroPrefix($current_month) . "-" . $this->AddZeroPrefix($new_sub_day + self::NEXT_ATTEMPT_DURATION_DAYS);

                // - User reached maximum attempt count or gflaged users SET to next month.
                if ($userAttemptFail) {
                    $original_subscription_date = $current_year . "-" . $this->AddZeroPrefix($current_month + 1) . "-" . $this->AddZeroPrefix($new_sub_day);
                    $next_subscription_date = $current_year . "-" . $this->AddZeroPrefix($current_month + 1) . "-" . $this->AddZeroPrefix($new_sub_day);
                }
            } else {
                $original_subscription_date = ($current_year) . "-" . $this->AddZeroPrefix(12) . "-" . $this->AddZeroPrefix($new_sub_day);
                $next_subscription_date = ($current_year) . "-" . $this->AddZeroPrefix(12) . "-" . $this->AddZeroPrefix($new_sub_day + self::NEXT_ATTEMPT_DURATION_DAYS);

                // - User reached maximum attempt count or gflaged users SET to next month.
                if ($userAttemptFail) {
                    $original_subscription_date = ($current_year + 1) . "-" . $this->AddZeroPrefix(1) . "-" . $this->AddZeroPrefix($new_sub_day);
                    $next_subscription_date = ($current_year + 1) . "-" . $this->AddZeroPrefix(1) . "-" . $this->AddZeroPrefix($new_sub_day);
                }
            }
        }

        return [
            'next_subscription_date' => $next_subscription_date,
            'original_subscription_date' => $original_subscription_date
        ];
//        }

        //return false;
    }

    public function AddZeroPrefix($str) {
        return str_pad($str, 2, '0', STR_PAD_LEFT);
    }

    public function make_response_output($title, $data, $newline = true) {

        return '<pre><strong>' . $title . ":</strong> " . $data . ( ( $newline ) ? "" . PHP_EOL : '' ) . "</pre>";
    }

}
