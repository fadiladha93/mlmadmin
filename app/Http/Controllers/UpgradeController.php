<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Validator;

class UpgradeController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
            'dlgUpgradePackage',
            'checkCouponCodeUpgrade',
            'upgradeProductCheckOut',
            'upgradeProductsCheckOutNewCard',
            'getUpgradeCountdown',
            'paymentMethodsForUpgrade'
        ]]);
        $this->middleware('auth');
    }

    private function upgraderPackagesCheckOutSessionDataValidate() {
        $discountCode = Session::get('checkOutUpgradePackageDiscountCode');
        $discount = 0;
        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                return ['error' => 1, 'msg' => "Invalid discount code"];
            }
        }
        if (!empty(Session::get('upgrade_package'))) {
            $checkOutUpgradePackage = Session::get('upgrade_package');
            $upgradePackageId = $checkOutUpgradePackage['upgrade_product_id'];
            $sessionId = $checkOutUpgradePackage['sessionId'];
            $newProductId = $checkOutUpgradePackage['new_product_id'];
            $currentProductId = $checkOutUpgradePackage['current_product_id'];
        }

        if (empty($upgradePackageId)) {
            return ['error' => 1, 'msg' => "Invalid upgrade package"];
        }
        if (empty($sessionId)) {
            return ['error' => 1, 'msg' => "Invalid upgrade package"];
        }
        if (empty($newProductId)) {
            return ['error' => 1, 'msg' => "Invalid upgrade package"];
        }
        if (empty($currentProductId)) {
            return ['error' => 1, 'msg' => "User exists in invalid package"];
        }
        return ['error' => 0, 'discountCode' => $discountCode, 'upgradeProductId' => $upgradePackageId, 'discount' => $discount, 'newProductId' => $newProductId, 'sessionId' => $sessionId, 'currentProductId' => $currentProductId];
    }

    public function upgradeProductCheckOut() {
        $req = request();

        $userCountry = \App\Address::where('userid', Auth::user()->id)
            ->where('addrtype', \App\Address::TYPE_BILLING)
            ->where('primary', 1)
            ->whereNotNull('countrycode')
            ->first();
        if (empty($userCountry)) {
            return response()->json(['error' => 1, 'msg' => 'We don\'t have your country in the Primary Address section of your profile. Please update your info to proceed.']);
        }
        // check discount code
        $sesData = $this->upgraderPackagesCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getById($sesData['upgradeProductId']);
        $amount = $product->price - $sesData['discount'];
        if ($amount <= 0) {
            return \App\Helper::paymentUsingCouponCode($sesData, $product, 'UPGRADE_PACKAGE');
        }
        $vali = \App\Helper::validateCheckOutPaymentType($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        $paymentType = $req->payment_method;
        if (!empty($paymentType) && $paymentType == "new_card") {
            $upgrade_package = Session::get('upgrade_package');
            $d['product_id'] = $upgrade_package['new_product_id'];
            $d['countries'] = \App\Country::getAll();
            $v = (string) view('affiliate.upgrades.dlg_upgrade_check_out_add_payment_method')->with($d);
            return response()->json(['error' => 0, 'v' => $v]);
        } else if (!empty($paymentType) && $paymentType == "e_wallet") {
            return $this->doPaymentForUpgradePackageByEwallet($req);
        } else if (!empty($paymentType) && $paymentType == 'bitpay') {
            return $this->bitpayInvoiceGenerate();
        } else if (!empty($paymentType)) {
            return $this->doPaymentForUpgradePackageByExistingCard($req);
        }
    }


    public function upgradeProductsCheckOutNewCard() {
        $req = request();
        $vali = \App\Helper::validatePaymentPage($req);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        return $this->doPaymentForNewCardUpgradeProducts($req);
    }

    private function doPaymentForUpgradePackageByExistingCard($req) {
        $sesData = $this->upgraderPackagesCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $paymentMethodId = $req->payment_method;
        $res = \App\Helper::checkExistingCardAndBillAddress(Auth::user()->id, $paymentMethodId);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $product = \App\Product::getById($sesData['upgradeProductId']);
        return \App\Helper::NMIPaymentProcessUsingExistingCard(Auth::user()->id, $res['billingAddress'], $product, $sesData, $res['paymentMethod'], Auth::user()->email, Auth::user()->phonenumber, Auth::user()->firstname, Auth::user()->lastname, 'UPGRADE_PACKAGE');
    }

    public function bitpayInvoiceGenerate() {
        $sesData = $this->upgraderPackagesCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $response = \App\Helper::bitPayPaymentRequest(Auth::user(), $sesData, 'UPGRADE_PACKAGE');
        return response()->json($response);
    }

    public function checkCouponCodeUpgrade() {
        $req = request();
        session(['checkOutUpgradePackageDiscountCode' => ""]);
        $upgrade_package = Session::get('upgrade_package');
        $upgradePackageId = $upgrade_package['upgrade_product_id'];
        $product = \App\Product::getProduct($upgradePackageId);
        $subTotal = $product->price;
        $total = $subTotal;
        $d['total'] = $total;
        $d['sub_total'] = $subTotal;
        $d['product'] = $product;
        $v = (string) view('affiliate.upgrades.dlg_upgrade_product_coupon')->with($d);
        $discountCode = $req->coupon;
        $discount = 0;
        if (!\utill::isNullOrEmpty($discountCode)) {
            $discount = \App\DiscountCoupon::getDiscountAmount($discountCode);
            if ($discount == 0) {
                return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v, 'total' => $total]);
            }
        } else {
            return response()->json(['error' => 1, 'msg' => "Invalid discount code", 'v' => $v, 'total' => $total]);
        }
        session(['checkOutUpgradePackageDiscountCode' => $discountCode]);
        $subTotal = $product->price;
        $total = $subTotal - $discount;
        if ($total <= 0) {
            $total = 0;
        }
        $d['total'] = $total;
        $d['sub_total'] = $subTotal;
        $d['product'] = $product;
        $v = (string) view('affiliate.upgrades.dlg_upgrade_product_coupon')->with($d);
        return response()->json(['error' => 0, 'msg' => 'Valid discount code', 'v' => $v, 'total' => $total]);
    }

    public function dlgUpgradePackage($package) {
        session_start();
        $d = array();
        $name = "";
        if ($package == \App\Product::ID_BASIC_PACK) {
            $name = "Coach Class";
        } else if ($package == \App\Product::ID_VISIONARY_PACK) {
            $name = "Business Class";
        } else if ($package == \App\Product::ID_FIRST_CLASS) {
            $name = "First Class";
        } else if ($package == \App\Product::ID_PREMIUM_FIRST_CLASS) {
            $name = "Premium First Class";
        }
        $upgradeTime = $this->getUpgradeCountdown();
        $upgradeTime = json_decode($upgradeTime->getContent());
        $currentProductId = \App\User::getCurrentProductId(Auth::user()->id);
        if ($upgradeTime->date <= date('Y-m-d')) {
            $upgProductId = $package;
        }else{
            $newProductId = $package;
            // get the upgrade package
            if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_BASIC_PACK) {
                $upgProductId = \App\Product::ID_UPG_STAND_COACH;
            } else if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_VISIONARY_PACK) {
                $upgProductId = \App\Product::ID_UPG_STAND_BUSINESS;
            } else if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_STAND_FIRST;
            } else if ($currentProductId == \App\Product::ID_NCREASE_ISBO && $newProductId == \App\Product::ID_PREMIUM_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_STAND_PREMIUM;
            } //
            else if ($currentProductId == \App\Product::ID_BASIC_PACK && $newProductId == \App\Product::ID_VISIONARY_PACK) {
                $upgProductId = \App\Product::ID_UPG_COACH_BUSINESS;
            } else if ($currentProductId == \App\Product::ID_BASIC_PACK && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_COACH_FIRST;
            } else if ($currentProductId == \App\Product::ID_BASIC_PACK && $newProductId == \App\Product::ID_PREMIUM_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_COACH_PREMIUM;
            } //
            else if ($currentProductId == \App\Product::ID_VISIONARY_PACK && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_BUSINESS_FIRST;
            } else if ($currentProductId == \App\Product::ID_Traverus_Grandfathering && $newProductId == \App\Product::ID_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_BUSINESS_FIRST;
            } else if ($currentProductId == \App\Product::ID_VISIONARY_PACK && $newProductId == \App\Product::ID_PREMIUM_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_BUSINESS_PREMIUM;
            } else if ($currentProductId == \App\Product::ID_Traverus_Grandfathering && $newProductId == \App\Product::ID_PREMIUM_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_BUSINESS_PREMIUM;
            } //
            else if ($currentProductId == \App\Product::ID_FIRST_CLASS && $newProductId == \App\Product::ID_PREMIUM_FIRST_CLASS) {
                $upgProductId = \App\Product::ID_UPG_FIRST_PREMIUM;
            }
            if (empty($upgProductId)) {
                return response()->json(['error' => 1, 'msg' => 'Upgrade product not found. Please contact supports.']);
            }
        }

        $upgProduct = \App\Product::getById($upgProductId);
        $d['cvv'] = \App\PaymentMethod::getUserPaymentRecords(Auth::user()->id);
        $d['name'] = $name;
        $d['product'] = $upgProduct;
        $d['sessionId'] = session_id();
        Session::put(['upgrade_package' => ['name' => $name, 'new_product_id' => $package, 'current_product_id' => $currentProductId, 'upgrade_product_id' => $upgProductId, 'sessionId' => $d['sessionId']]]);
        return view('affiliate.upgrades.dlg_upgrade_payment_method')->with($d);
    }

    private function doPaymentForUpgradePackageByEwallet() {
        $sesData = $this->upgraderPackagesCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getById($sesData['upgradeProductId']);
        $amount = $product->price - $sesData['discount'];
        $checkEwalletBalance = \App\User::select('*')->where('id', Auth::user()->id)->first();
        if ($checkEwalletBalance->estimated_balance < $amount) {
            return response()->json(['error' => 1, 'msg' => "Not enough e-wallet balance"]);
        }
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, null, null, \App\Helper::createEmptyPaymentRequest(Auth::user()->firstname, Auth::user()->lastname, null), \App\PaymentMethodType::TYPE_E_WALET);
        $orderSubtotal = $product->price;
        $orderTotal = $product->price - $sesData['discount'];
        $orderId = \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, null, $orderFor = "UPGRADE_PACKAGE");
        \App\EwalletTransaction::addPurchase(Auth::user()->id, \App\EwalletTransaction::TYPE_UPGRADE_PACKAGE, -$amount, $orderId);
        Session::put('upgrade_package');
        Session::put('checkOutUpgradePackageDiscountCode');
        $v = (string) view('affiliate.upgrades.dlg_check_out_package_upgrade_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function doPaymentForNewCardUpgradeProducts($req) {
        $sesData = $this->upgraderPackagesCheckOutSessionDataValidate();
        if ($sesData['error'] == 1) {
            return response()->json($sesData);
        }
        $product = \App\Product::getById($sesData['upgradeProductId']);
        $res = \App\Helper::checkExsitingCardAfterTokenize($req);
        if ($res['error'] == 1) {
            return response()->json($res);
        }
        $orderSubtotal = $product->price;
        $orderTotal = $product->price - $sesData['discount'];
        $paymentMethodType = \App\PaymentMethodType::TYPE_CREDIT_CARD;

        if (\App\Helper::checkTMTAllowPayment($req->countrycode,Auth::user()->id) > 0) {
            //  $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
            // ONLY ON US CUSTOMERS
            if($req->countrycode == "US"){
                $paymentMethodType = \App\PaymentMethodType::TYPE_PAYARC;
            }else{
                $paymentMethodType = \App\PaymentMethodType::TYPE_T1_PAYMENTS;
            }
        }
        
        $nmiResult = \App\Helper::NMIPaymentProcessUsingNewCard($req, $orderTotal, $product, $sesData['sessionId'], Auth::user()->email, Auth::user()->phonenumber, $paymentMethodType);

        if ($nmiResult['error'] == 1) {
            return response()->json($nmiResult);
        }
        $authorization = $nmiResult['authorization'];
        $addressId = \App\Helper::createSecondoryAddressIfNotAvlPrimaryAddress(Auth::user()->id, $req, $paymentMethodType);
        $paymentMethodId = \App\Helper::createSecondoryPaymentMethodIfNotAvlPrimaryPaymentMethod(Auth::user()->id, $res['token'], $addressId, $req, $paymentMethodType);
        \App\Helper::createNewOrderAfterPayment(Auth::user()->id, $orderSubtotal, $orderTotal, $paymentMethodId, $sesData, $product, $authorization, 'UPGRADE_PACKAGE');
        Session::put('upgrade_package');
        Session::put('checkOutUpgradePackageDiscountCode');
        $v = (string) view('affiliate.upgrades.dlg_check_out_package_upgrade_success');
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function getUpgradeCountdown() {
        $res = \App\User::canUpgrade();
        return response()->json(['error' => 0, 'date' => $res['end_date']]);
    }

    public function paymentMethodsForUpgrade() {
        $req = request();
        $d['cvv'] = \App\PaymentMethod::getUserPaymentRecords(Auth::user()->id);
        $v = (string) view('affiliate.upgrades.dlg_upgrade_payment_method')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

}
