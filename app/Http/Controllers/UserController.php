<?php

namespace App\Http\Controllers;

use utill;
use Config;
use MyMail;
use Storage;
use App\User;
use Exception;
use App\Address;
use Carbon\Carbon;
use App\NMIGateway;
use App\PaymentMethod;
use App\AdminPermission;
use App\MailGunMailList;
use App\UserRankHistory;
use App\UserAuthSsoToken;
use App\PaymentMethodType;
use Faker\Provider\Payment;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GeoIp;
use App\Models\ReplicatedPreferences;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
            'registrationForm',
            'register',
            'verifyEmail',
            'registraionSuccess',
            'getEnrolledInternDataTable',
            'frmChangePassword',
            'changePassword',
            'showMyProfile',
            'saveProfile',
            'savePayapMobile',
            'savePayapSSN',
            'savePrimaryAddress',
            'saveBillingAddress',
            'billingAddNewCard',
            'showQuestionList',
            'getQuestionListContent',
            'questionListCompleted',
            'getStates',
            'deletePaymentMethod',
            'savePlacements',
            'placementPreference',
            'getUserInfo',
            'updateUserTaxInfo',
            'updateUserTaxInfoInternational',
            'checksUserData',
            'savesUserData'
        ]]);

        $this->middleware('auth',['except' => [
            'registrationForm',
            'register',
            'verifyEmail',
            'registraionSuccess',
            'callback',
            'getUserInfo',
            'checksUserData',
            'savesUserData'
        ]]);
    }

    public function registrationForm() {
        return view('affiliate.user.sign_up');
    }

    public function registraionSuccess() {
        return view('affiliate.user.registration_success');
    }

    public function showMyProfile($type = null) {
        if ($type == null)
            return $this->showMyProfile_basic();
        else if ($type == "primary-credit-card")
            return $this->showMyProfile_primary_cc();
        else if ($type == "primary-address")
            return $this->showMyProfile_primary_address();
        else if ($type == "billing-address")
            return $this->showMyProfile_billing_address();
        else if ($type == "replicated")
            return $this->showMyProfile_replicated();
        //else if ($type == "payap")
        //    return $this->showMyProfile_payap();
        else if ($type == "idecide")
            return $this->showMyProfile_idecide();
        else if ($type == "billing")
            return $this->showMyProfile_billing();
        else
            return redirect('/');
    }

    public function showMyProfile_replicated() {
        $user = Auth::user();
        $preferences = $user->replicatedPreferences;

        $d['tab'] = "replicated";

        $d['preferences'] = [
            'buiness_name' => $preferences && $preferences->business_name ? $preferences->business_name : '',
            'displayed_name' => $preferences && $preferences->displayed_name ? $preferences->displayed_name : $user->firstname . ' ' . $user->lastname,
            'name' => $user->firstname . ' ' . $user->lastname,
            'co_name' => $user->co_applicant_name,
            'co_display_name' => $preferences && $preferences->co_name ? $preferences->co_name : $user->co_applicant_name,
            'phone' => $preferences && $preferences->phone ? $preferences->phone : $user->phonenumber,
            'email' => $preferences && $preferences->email ? $preferences->email : $user->email,
            'show_email' => $preferences ? $preferences->show_email : 1,
            'show_phone' => $preferences ? $preferences->show_phone : 1,
            'show_name' => $preferences ? $preferences->show_name : 1,
            'disable_co_app' => !$user->co_applicant_name,
        ];

        return view('affiliate.dashboard.replicated_preferences')->with($d);
    }

    public function showMyProfile_basic() {
        $d['rec'] = Auth::user();
        $d['tab'] = "basic";
        return view('affiliate.user.my_profile_basic')->with($d);
    }

    public function showMyProfile_primary_cc() {
        $d['rec'] = Auth::user();
        $d['tab'] = "primary_card";
        $paymentMethod = \App\PaymentMethod::getRec(Auth::user()->id, 1, \App\PaymentMethodType::TYPE_CREDIT_CARD);
        $d['payment_method'] = $paymentMethod;
        //
        $expiryDate = "";
        if (!empty($paymentMethod)) {
            $expiryDate = $paymentMethod->expMonth . "/" . $paymentMethod->expYear;
        }
        $d['expiry_date'] = $expiryDate;
        return view('affiliate.user.my_profile_primary_card')->with($d);
    }

    public function showMyProfile_billing_address()
    {
        $d['rec'] = Auth::user();
        $d['tab'] = "billing-address";
        $d['countries'] = \App\Country::getAll();
        //new enrollment users
        $d['billing_address'] = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_BILLING, 1);
        return view('affiliate.user.my_profile_billing_address')->with($d);
    }

    public function showMyProfile_primary_address() {
        $d['rec'] = Auth::user();
        $d['tab'] = "address";
        $d['countries'] = \App\Country::getAll();
        //new enrollment users
        $d['primary_address'] = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_REGISTRATION, 1);
        return view('affiliate.user.my_profile_primary_address')->with($d);
    }

//    public function showMyProfile_payap() {
//        $d = array();
//        $d['rec'] = Auth::user();
//        $d['tab'] = "payap";
//        return view('affiliate.user.my_profile_payap')->with($d);
//    }

    public function showMyProfile_idecide() {
        $d = array();
        $d['tab'] = "idecide";
        return view('affiliate.user.my_profile_idecide')->with($d);
    }

    public function showMyProfile_billing() {
        $d = array();
        $d['tab'] = "billing";
        $d['cards'] = PaymentMethod::getUserPaymentMethods(Auth::user()->id, 1);
        $d['addresses'] = \App\Address::getFilteredBillingAddresses(Auth::user()->id);
        $d['countries'] = \App\Country::getAll();

        $d['vibeUserWithoutBillingInfo'] = User::isVibeImportUser() && Auth::user()->paymentMethods()->count() == 0;

        return view('affiliate.user.my_profile_billing')->with($d);
    }

    public function placementPreference() {
        $loginUser = Auth::user();
        $d = array();
        $d['tab'] = "placement-preference";
        $placement = DB::select("select binary_placement from users where id = '$loginUser->id'");
        $d['binary_placement'] = $placement[0]->binary_placement;
        return view('affiliate.user.my_profile_placement_preference')->with($d);
    }

    public function saveProfile() {
        $req = request();
        $loginUser = Auth::user();
        $vali = $this->validateSaveProfile($loginUser->id);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            \App\User::updateRec($loginUser->id, $req, false);
            return response()->json(['error' => '0', 'msg' => 'Saved']);
        }
    }

    public function savePayapMobile() {
        $req = request();
        $payap_mobile = trim($req->payap_mobile);
        $payap_mobile = str_replace(" ", "", $payap_mobile);
        if (\utill::isNullOrEmpty($payap_mobile)) {
            return response()->json(['error' => '1', 'msg' => 'Please enter your Payap mobile']);
        }
        // verify payap mobile
        $response = \App\PayAP::verifyAccountNumber(Auth::user()->id, $payap_mobile);
        $response = $response['response'];
        if (!($response->status == "success" && $response->user > 0))
            return response()->json(['error' => '1', 'msg' => 'Invalid Payap mobile number']);
        //
        $user = \App\User::find(Auth::user()->id);
        $user->payap_mobile = $payap_mobile;
        $user->save();
        return response()->json(['error' => '0', 'msg' => 'You number has been saved and verified please proceed with your transaction']);
    }

    public function savePayapSSN() {
        $req = request();
        $vali = $this->validatePayAppTaxInformation();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update([
                'ssn' => $req->ssn
            ]);
        return response()->json(['error' => '0', 'msg' => 'Saved']);
    }

    public function savePrimaryAddress() {
        $req = request();
        $vali = $this->validatePrimaryAddress();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        \App\Address::updateRec(Auth::user()->id, \App\Address::TYPE_REGISTRATION, 1, $req);
        return response()->json(['error' => '0', 'msg' => 'Saved']);
    }

    public function saveBillingAddress()
    {
        $req = request();
        $vali = $this->validatePrimaryAddress();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        \App\Address::updateRec(Auth::user()->id, \App\Address::TYPE_BILLING, 1, $req);
        return response()->json(['error' => '0', 'msg' => 'Saved']);
    }

    private function getAddNewBillingCardRulesAndMessages()
    {
        // ccd, ccn, and cvc are from composer package
        $rules = [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'expiry_date' => 'required|ccd|size:7',
            'number' => 'required|ccn',
            'cvv' => 'required|cvc'
        ];

        $messages = [
            'first_name.required' => 'First name on card is required',
            'first_name.max' => 'First name cannot exceed 50 characters',
            'last_name.required' => 'Last name on card is required',
            'last_name.max' => 'Last name cannot exceed 50 characters',
            'number.required' => 'Card number is required',
            'number.ccn' => 'Card number is invalid',
            'cvv.required' => 'CVV is required',
            'cvv.cvc' => 'CVV is invalid',
            'cvv.max' => 'CVV cannot exceed 4 characters',
            'expiry_date.required' => 'Expiration date is required',
            'expiry_date.size' => 'Invalid expiration date format',
            'expiry_date.ccd' => 'Invalid expiration date'
        ];

        return array($rules, $messages);
    }

    private function getNewAddressValidator()
    {
        $rules = [
            'address1' => 'required|max:255',
            'city' => 'required|max:255',
            'stateprov' => 'required|max:50',
            'postalcode' => 'required|max:10',
            'countrycode' => 'required|regex:/[a-z][a-z]/i'
        ];

        $messages = [
            'address1.required' => 'Address is required',
            'address1.max' => 'Address is above the maximum size',
            'countrycode.required' => 'Country is required',
            'city.required' => 'City / Town is required',
            'city.max' => 'City / Town is above the maximum size',
            'stateprov.required' => 'State / Province is required',
            'stateprov.max' => 'State / Province is above the maximum size',
            'postalcode.required' => 'Postal code is required',
            'postalcode.max' => 'Postal code is above the maximum size'
        ];

        return array($rules, $messages);
    }

    private function createBillingNewCardValidator(Request $request)
    {
        list($rules, $messages) = $this->getAddNewBillingCardRulesAndMessages();

        if ($request->post('address_id') == -1) {
            list($addressRules, $addressMessages) = $this->getNewAddressValidator();

            $rules = array_merge($rules, $addressRules);
            $messages = array_merge($messages, $addressMessages);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        return $validator;
    }

    public function billingAddNewCard(Request $request)
    {
       
        $validator = $this->createBillingNewCardValidator($request);

        $errorMessage = $this->generateErrorMessageFromValidator($validator);

        if (!empty($errorMessage)) {
            return response()->json(['error' => 1, 'msg' => $errorMessage]);
        }

        $makePrimaryCard = $request->post('make_primary_card') == 'on' ? 1 : 0;;

        $user = Auth::user();

        $addressId = $request->post('address_id') == -1 ?
            Address::addNewRecSecondaryAddress(
                $user->id,
                Address::TYPE_BILLING,
                $makePrimaryAddress = 0,
                $request
            ) : $request->post('address_id');

        $address = Address::getById($addressId);

        $expiration = $request->expiry_date;
        $expirationParts = explode("/", $expiration);
        $expiryMonth = $expirationParts[0];
        $expiryYear = $expirationParts[1];

        $useTmt = \App\Helper::checkTMTAllowPayment($user->country_code, $user->id) > 0;
        $paymentMethodType = $useTmt ? PaymentMethodType::TYPE_T1_PAYMENTS : PaymentMethodType::TYPE_CREDIT_CARD;

        $tokenExResult = \App\PaymentMethod::generateTokenEx($request->number);
        if ($tokenExResult['error'] == 1) {
            return ['error' => 1, 'msg' => "Invalid card number<br/>" . $tokenExResult['msg']];
        }

        $tokenEx = $tokenExResult['token'];

        $cardAlreadyExists = PaymentMethod::checkCardAlreadyExists(Auth::user()->id, $tokenEx);

        if ($cardAlreadyExists) {
            return response()->json(['error' => 1, 'msg' => "Card already exists"]);
        }

        \App\PaymentMethod::addNewRec($user->id,
            $makePrimaryCard,
            $tokenEx,
            $addressId,
            $paymentMethodType,
            $request
        );

        return response()->json(['error' => '0', 'msg' => 'Saved', 'url' => 'reload']);
    }

    public function frmChangePassword() {
        return view('affiliate.user.change_password');
    }

    public function changePassword() {
        $req = request();
        $vali = $this->validateChangePassword();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            $loginUser = Auth::user();
            $loginUser->password = password_hash($req->pass_1, PASSWORD_BCRYPT);
            $loginUser->default_password = "";
            $loginUser->save();
            return response()->json(['error' => '0', 'msg' => 'Password changed successfully']);
        }
    }

    public function userList($userType) {
        $d = array();
        if ($userType == "admins") {
            if (\App\AdminPermission::sidebar_admin_users()) {
                $d['title'] = "Administrators";
                return view('admin.user.list_admin')->with($d);
            } else {
                return redirect('/');
            }
        } else if ($userType == "cs-users") {
            if (\App\AdminPermission::sidebar_cs_users()) {
                $d['title'] = "CS users";
                return view('admin.user.list_admin')->with($d);
            } else {
                return redirect('/');
            }
        } else if ($userType == "ambassadors") {
            $d = array();
            $enrollmentpacks = \App\Product::getEnrollmentPacks();
            // $enrollmentpacks = array(
            //     "None" => 0,
            //     "Standby Class" => \App\Product::ID_NCREASE_ISBO,
            //     "Coach Class" => \App\Product::ID_BASIC_PACK,
            //     "Business Class" => \App\Product::ID_VISIONARY_PACK,
            //     "First Class" => \App\Product::ID_FIRST_CLASS,
            //     "Graduate" => \App\Product::ID_EB_FIRST_CLASS,
            //     "Traverus Grandfathering" => \App\Product::ID_Traverus_Grandfathering,
            //     "Premium First Class" => \App\Product::ID_PREMIUM_FIRST_CLASS,
            // );
            $d['enrollment_packs'] = $enrollmentpacks;
            return view('admin.user.list_interns')->with($d);
        } else if ($userType == "terminated-users") {
            return view('admin.user.terminated_users');
        } else {
            abort(404);
        }
    }

    public function getInternDataTable() {
        $req = request();
        $query = DB::table('vusersandlifetimerank')
            ->select('id', 'distid', 'firstname', 'lastname', 'email', 'mobilenumber', 'username', 'country_code', 'sponsorid', 'account_status', 'current_product_id', 'basic_info_updated', 'created_dt', 'entered_by', 'monthly_rank_desc')
            ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
            ->where('account_status', "<>", \App\User::ACC_STATUS_TERMINATED);

        if ($req->filterByEnrollmentpack != "")
            $query->where('current_product_id', $req->filterByEnrollmentpack);

        return DataTables::of($query)->toJson();
    }

    public function getTerminatedUsersDataTable() {
        $query = DB::table('vusersandlifetimerank')
            ->select('id', 'distid', 'firstname', 'lastname', 'email', 'mobilenumber', 'username', 'country_code', 'sponsorid', 'account_status', 'current_product_id', 'basic_info_updated', 'created_dt', 'entered_by', 'monthly_rank_desc')
            ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
            ->where('account_status', \App\User::ACC_STATUS_TERMINATED);

        return DataTables::of($query)->toJson();
    }

    public function getEnrollementDataTable($distid) {
        $query = DB::table('users')
            ->select('id', 'firstname', 'lastname', 'email', 'account_status', 'email_verified', 'created_dt', 'entered_by', 'username', 'basic_info_updated', 'current_product_id', 'distid', 'sponsorid')
            ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
            ->where('sponsorid', $distid);
        return DataTables::of($query)->toJson();
    }

    public function exportInternData($sort_col, $asc_desc, $fr_by_en = null, $q = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Ambassador.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $query = DB::table('vusersandlifetimerank')
            ->select('distid', 'firstname', 'lastname', 'mobilenumber', 'email', 'username', 'country_code', 'sponsorid', 'account_status', 'current_product_id', 'created_dt', 'monthly_rank_desc')
            ->where('usertype', \App\UserType::TYPE_DISTRIBUTOR)
            ->where('account_status', "<>", \App\User::ACC_STATUS_TERMINATED);

        if ($q == null) {
            if ($fr_by_en != -1)
                $query->where('current_product_id', $fr_by_en);

            $query->orderBy($sort_col, $asc_desc);
            $recs = $query->get();
        } else {
            if ($fr_by_en != -1)
                $query->where('current_product_id', $fr_by_en);

            $query->where(function ($sq) use ($q) {
                $sq->where('firstname', 'ilike', "%" . $q . "%")
                    ->orWhere('lastname', 'ilike', "%" . $q . "%")
                    ->orWhere('email', 'ilike', "%" . $q . "%")
                    ->orWhere('account_status', 'ilike', "%" . $q . "%")
                    ->orWhere('distid', 'ilike', "%" . $q . "%")
                    ->orWhere('sponsorid', 'ilike', "%" . $q . "%")
                    ->orWhere('phonenumber', 'ilike', "%" . $q . "%")
                    ->orWhere('countrycode', 'ilike', "%" . $q . "%")
                    ->orWhere('account_status', 'ilike', "%" . $q . "%")
                    ->orWhere('current_product_id', 'ilike', "%" . $q . "%")
                    ->orWhere('created_dt', 'ilike', "%" . $q . "%")
                    ->orWhere('username', 'ilike', "%" . $q . "%")
                    ->orWhere('monthly_rank_desc', 'ilike', "%" . $q . "%");
            });
            $query->orderBy($sort_col, $asc_desc);
            $recs = $query->get();
        }

        $columns = array('Dist ID', 'First Name', 'Last Name', 'Phone', 'Email', 'Username', 'Country', 'Sponsor ID', 'Account Status', 'Enrollment Pack', 'Enrollment Date', 'Lifetime Rank');

        $callback = function () use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->distid, $rec->firstname, $rec->lastname, $rec->mobilenumber, $rec->email, $rec->username, $rec->country_code, $rec->sponsorid, $rec->account_status, $rec->current_product_id, $rec->created_dt, $rec->monthly_rank_desc));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function getLeadDataTable() {
        $query = DB::table('lead_intern');
        return DataTables::of($query)->toJson();
    }

    public function exportLeadData($sort_col, $asc_desc, $q = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Leads.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            $recs = DB::table('lead_intern')
                ->orderBy($sort_col, $asc_desc)
                ->get();
        } else {
            $recs = DB::table('lead_intern')
                ->where(function ($sq) use ($q) {
                    $sq->where('name', 'like', "%" . $q . "%")
                        ->orWhere('email', 'like', "%" . $q . "%")
                        ->orWhere('phone', 'like', "%" . $q . "%")
                        ->orWhere('contact_date', 'like', "%" . $q . "%")
                        ->orWhere('status', 'like', "%" . $q . "%")
                        ->orWhere('intern_detail', 'like', "%" . $q . "%");
                })
                ->orderBy($sort_col, $asc_desc)
                ->get();
        }


        $columns = array('Name', 'Email', 'Phone number', 'Contact Date', 'Status', 'Intern detail');

        $callback = function () use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->name, $rec->email, $rec->phone, $rec->contact_date, $rec->status, $rec->intern_detail));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function getEnrolledInternDataTable() {
        //->select('id', 'firstname', 'lastname', 'email', 'account_status', 'username')
        $query = DB::table('users')
            ->select('id', 'firstname', 'lastname', 'email', 'account_status', 'username', 'basic_info_updated', 'distid', 'current_product_id')
            ->where('sponsorid', Auth::user()->distid);
        return DataTables::of($query)->toJson();
    }

    public function getAdminDataTable() {
        if (\App\User::admin_cs_manager()) {
            $query = DB::table('users')
                ->select('id', 'firstname', 'lastname', 'email', 'admin_role', 'mobilenumber', 'phone_country_code', 'secondary_auth_enabled')
                ->where('usertype', \App\UserType::TYPE_ADMIN)
                ->where('admin_role', \App\UserType::ADMIN_CS);
        } else {
            $query = DB::table('users')
                ->select('id', 'firstname', 'lastname', 'email', 'admin_role', 'mobilenumber', 'phone_country_code', 'secondary_auth_enabled')
                ->where('usertype', \App\UserType::TYPE_ADMIN);

            // Hide super exec and super admin users if super exec.
            if (User::admin_super_exec()) {
                $query->whereNotIn('admin_role', [\App\UserType::ADMIN_SUPER_ADMIN, \App\UserType::ADMIN_SUPER_EXEC]);
            }
        }

        return DataTables::of($query)->toJson();
    }

    public function frmNewIntern() {
        if (\App\AdminPermission::fn_add_new_ambassador()) {
            $d = array();
            $d['added_by'] = \App\User::getLoginUserName();
            $d['new_tsa'] = \App\User::getRandomTSA();
            $d['countries'] = \App\Country::getAll();
            $d['enrollment_packs'] = \App\Product::getByTypeId(\App\ProductType::TYPE_ENROLLMENT, "id", "asc");
            return view('admin.user.frmNewIntern')->with($d);
        } else {
            return redirect('/');
        }
    }

    public function addNewIntern() {
        if (\App\AdminPermission::fn_add_new_ambassador()) {
            $req = request();
            $vali = $this->validateRec();
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            } else {
                $userId = \App\User::addNew($req, true);
                // create orders
                $product = \App\Product::getProduct($req->current_product_id);
                $paymentMethodId = \App\PaymentMethod::addNewRec($userId, null, null, null, \App\PaymentMethodType::TYPE_ADMIN, null);
                $orderId = \App\Order::addNew($userId, $product->price, 0, 0, 0, 0, 'Admin', $paymentMethodId, null, null);
                // create new order item
                \App\OrderItem::addNew($orderId, $req->current_product_id, 1, $product->price, 0, 0, 0);
                //
                \App\Address::updateRec($userId, \App\Address::TYPE_BILLING, 1, $req);
                \App\UpdateHistory::userAdd($userId, $req);
                return response()->json(['error' => '0', 'url' => url('/users/ambassadors')]);
            }
        } else {
            return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
        }
    }

    public function updateIntern() {
        $req = request();
        $userId = $req->rec_id;
        $user_rec = \App\User::getById($userId);
        $vali = $this->validateRec($userId, $user_rec->distid, $user_rec->sponsorid);
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            $permissionCheck = $this->checkAdminPermission($userId, $req);
            if (!$permissionCheck['permit']) {
                return response()->json(['error' => 1, 'msg' => $permissionCheck['msg']]);
            } else {
                \App\User::updateRec($userId, $req);
                \App\Address::updateRec($userId, \App\Address::TYPE_REGISTRATION, 1, $req);
                \App\UpdateHistory::userUpdate($userId, $user_rec, $req);
                //
                if ($user_rec->account_status != $req->account_status) {
                    \App\User::resetSyncWithMailgun($userId);
                    if ($req->account_status == \App\User::ACC_STATUS_APPROVED) {
                        $current_date = date("Y-m-d");
                        if ($current_date >= date("Y-m-25")) {
                            $next_billing_date = strtotime(date("Y-m-25", strtotime($current_date)) . " +1 month");
                        } else {
                            $next_billing_date = strtotime(date("Y-m-d", strtotime($current_date)) . " +1 month");
                        }
                        $next_billing_date = date("Y-m-d", $next_billing_date);
                        DB::table('users')
                            ->where('id', $userId)
                            ->update([
                                'original_subscription_date' => $next_billing_date,
                                'next_subscription_date' => $next_billing_date
                            ]);
                    }
                }
                if (in_array($req->account_status, [\App\User::ACC_STATUS_TERMINATED, \App\User::ACC_STATUS_SUSPENDED])) {
                    $currentProductId = \App\User::getCurrentProductId($userId);
                    \App\Helper::deActivateIdecideUser($userId);
                    \App\Helper::deActivateSaveOnUser($userId, $currentProductId, $user_rec->distid, \App\SaveOn::USER_SUSPENDED_NOTE);
                }
                //
                return response()->json(['error' => 0, 'msg' => 'Saved', 'url' => 'reload']);
            }
        }
    }

    private function checkAdminPermission($userId, $req) {
        $permit = true;
        $msg = "";
        //
        $rec = \App\User::find($userId);
        //
        if ($rec->sponsorid != $req->sponsorid) {
            if (!\App\AdminPermission::fn_update_sponsor_id()) {
                $permit = false;
                $msg = 'Permission denied to change the sponsor';
            }
        } else if ($rec->username != strtolower($req->username)) {
            if (!\App\AdminPermission::fn_update_username()) {
                $permit = false;
                $msg = 'Permission denied to change the username';
            }
        }
        //
        $res = array();
        $res['permit'] = $permit;
        $res['msg'] = $msg;
        return $res;
    }

    public function verifyEmail($email, $verificationCode) {
        $valid = true;
        $rec = \App\User::where('email', $email)->first();
        if (empty($rec))
            $valid = false;
        else {
            $code = \App\User::getEmailVerificationCode($email, $rec->id);
            if ($code != trim($verificationCode)) {
                $valid = false;
            }
        }
        if ($valid) {
            $rec->email_verified = 1;
            $rec->save();
            //
            $msg = array('error' => 0, 'msg' => 'Congratulations! Your email has been verified');
            return redirect('/login')->withErrors($msg);
        } else {
            $msg = array('error' => 1, 'msg' => 'Invalid E-mail verification link');
            return redirect('/login')->withErrors($msg);
        }
    }

    public function loginAsUser($distid) {
        session(['login_from_admin' => Auth::user()->id]);
        $userRec = \App\User::getByDistId($distid);
        Auth::loginUsingId($userRec->id);
        return redirect('/');
    }

    public function saveSubscriptionProduct()
    {
        $request = request();
        if (empty($request->distid)) {
            return response()->json(['error' => 1, 'msg' => 'Distributor not found']);
        }
        
        $product = \App\Product::getById($request->subscription_product);                    
        $sDate = date("Y-m-d", strtotime($request->subscription_product_date));
        
        $user = \App\User::where('distid', $request->distid)->first();
        $old_data = $user->toJson();

        $user->subscription_product = (empty($product) ? null : $product->id);
        $user->next_subscription_date = $sDate;
        $user->save();
        
        $new_data = $user->toJson();
        
        $log = new UserActivityLog();
        $response = GeoIP::getInformationFromIP(request()->ip());
        $log->ip_address = request()->ip();
        $log->user_id = $user->id;
        $log->ip_details = $response;
        $log->old_data = $old_data;
        $log->new_data = $new_data;
        $log->action = 'UPDATE User '.$user->id.' Subscription Product Admin';
        $log->save();

        return response()->json(['error' => 0, 'msg' => 'Subscription product was updated']);
    }

    public function internDetail($distid) {
        $rec = \App\User::getByDistId($distid);
        if (empty($rec))
            abort(404);
        //
        $enteredBy = "Self Registration";
        if ($rec->entered_by > 0) {
            $eb = \App\User::getById($rec->entered_by);
            $enteredBy = $eb->firstname . " " . $eb->lastname;
        }
        //
        $sponsor = "";
        if (!\utill::isNullOrEmpty($rec->sponsorid)) {
            $s = \App\User::getByDistId($rec->sponsorid);
            if (!empty($s))
                $sponsor = $s->distid . " - " . $s->username;
            else
                $sponsor = $rec->sponsorid;
        }
        //
        $d['rec'] = $rec;
        $d['entered_by'] = $enteredBy;
        $d['sponsor'] = $sponsor;
        $d['shipping_address'] = \App\Address::getRec($rec->id, \App\Address::TYPE_SHIPPING, 1);
        $d['billing_address'] = \App\Address::getRec($rec->id, \App\Address::TYPE_BILLING, 1);
        $d['primary_address'] = \App\Address::getRec($rec->id, \App\Address::TYPE_REGISTRATION, 1);
        $d['countries'] = \App\Country::getAll();

        $d['subscription_products'] = \App\Product::getSubscriptionProducts();
        $d['site_agreement'] = \App\ProductTermsAgreement::getByUserId($rec->id, 'both');
        $d['lifetime_rank'] = (User::where('id', $rec->id)->first())->getHighestRank();
        $d['canEditCoApplicantForm'] = Auth::user()->usertype === \App\UserType::TYPE_ADMIN
            && in_array(Auth::user()->admin_role, [
            \App\UserType::ADMIN_SUPER_ADMIN,
            \App\UserType::ADMIN_SUPER_EXEC,
            \App\UserType::ADMIN_CS_EXEC
        ]);

        return view('admin.user.detail')->with($d);
    }

    // for select 2
    public function getAllIntern() {
        $req = request();
        $q = $req->q;
        $query = DB::table('users');
        $query->select('distid as id', DB::raw("CONCAT(distid,' - ',username) AS text"));
        $query->where('usertype', \App\UserType::TYPE_DISTRIBUTOR);

        if (!$req->has('status')) {
            $query->where('account_status', \App\User::ACC_STATUS_APPROVED);
        }

        if (!\App\AdminPermission::fn_corp_people_in_search()) {
            $query->whereNotIn('distid', ['TSA0707550', 'TSA5138270', 'TSA9834283']);
        }
        $recs = $query->where(function ($sq) use ($q) {
            $sq->where('distid', 'ilike', $q . "%")
                ->orWhere('username', 'ilike', $q . "%");
        })->paginate(10);
        return $recs->toJson();
    }

    public function showEnrollements($distId) {
        $d = array();
        $d['rec'] = DB::table('users')
            ->select('username', 'distid')
            ->where('distid', $distId)
            ->first();
        $d['distid'] = $distId;
        return view('admin.user.enrollements')->with($d);
    }

    public function showQuestionList() {
        return view('affiliate.user.dlg_questions_list');
    }

    public function getQuestionListContent($currentStep) {
        $req = request();
        if ($currentStep == 0) {
            $v = $this->goToPage(1);
        } else if ($currentStep == 1) {
            $iqUserId = $req->iq_user_id;
            if (!\utill::isNullOrEmpty($iqUserId)) {
                $valid = \App\IQCredits::isValid($iqUserId);
                if (!$valid)
                    return response()->json(['error' => 1, 'msg' => 'Invalid ID']);
                else {
                    \App\User::updateLegacyId($iqUserId);
                }
            } else {
                \App\User::updateLegacyId("");
            }
            $v = $this->goToPage(2);
        } else if ($currentStep == 2) {
            $tag = $req->tag; // vip checked or not
            if ($tag == 1) {
                // check if this user already purchased VIP first class
                $purchased = \App\OrderItem::isAlreadyPurchased(Auth::user()->id, \App\Product::ID_EB_FIRST_CLASS);
                if ($purchased) {
                    $v = $this->goToPage(3, false);
                } else {
                    $v = $this->goToPage(3, true);
                }
            } else {
                $v = $this->goToPage(4);
            }
        } else if ($currentStep == 3) {
            $vali = \App\CommonValidations::validatePaymentPage($req);
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            } else {
                // save primary cc information here
                // generate token ex
                $tokenExResult = \App\PaymentMethod::generateTokenEx($req->number);
                if ($tokenExResult['error'] == 1) {
                    return response()->json(['error' => 1, 'msg' => "Invalid card number<br/>" . $tokenExResult['msg']]);
                }
                $tokenEx = $tokenExResult['token'];
                //
                $addressId = \App\Address::updateRec(Auth::user()->id, \App\Address::TYPE_BILLING, 1, $req);
                $paymentMethodId = \App\PaymentMethod::addNewRec(Auth::user()->id, 1, $tokenEx, $addressId, \App\PaymentMethodType::TYPE_CREDIT_CARD, $req);
                //
                if ($req->has('do_immediate_payments')) {
                    // if this user is purchasing VIP first class then remove pre enrollments
                    \App\PreEnrollmentSelection::deleteRec(Auth::user()->id);
                    // check from the order item table, whether he has record for 'VIP first class'
                    $vip_payment_completed = \App\OrderItem::isAlreadyPurchased(Auth::user()->id, \App\Product::ID_EB_FIRST_CLASS);
                    if (!$vip_payment_completed) {
                        $vipPaymentResult = $this->doVIPPayments($tokenEx, $req->number, $paymentMethodId, $req);
                        if ($vipPaymentResult['error'] == 1) {
                            return response()->json(['error' => 1, 'msg' => $vipPaymentResult['msg']]);
                        }
                    }
                }
                $v = $this->goToPage(7);
            }
        } else if ($currentStep == 4) {
            $my_package = $req->my_package;
            if (!\utill::isNullOrEmpty($my_package)) {
                \App\PreEnrollmentSelection::addNewRec(Auth::user()->id, $my_package);
            }
            //
            $v = $this->goToPage(3, false);
        } else if ($currentStep == 5) {
            if (!$req->has('same_card')) {
                return response()->json(['error' => 1, 'msg' => 'Please select one option']);
            }
            $tag = $req->same_card; // user same card or not
            if ($tag == 1) {
                // delete secondary card
                \App\PaymentMethod::deleteSecondary(Auth::user()->id, \App\PaymentMethodType::TYPE_CREDIT_CARD);
                \App\Address::deleteSecondary(Auth::user()->id, \App\Address::TYPE_BILLING);
                //
                $v = $this->goToPage(7);
            } else {
                $v = $this->goToPage(6);
            }
        } else if ($currentStep == 6) {
            $vali = \App\CommonValidations::validatePaymentPage($req);
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            } else {
                // save secondary credit card information here
                $tokenExResult = \App\PaymentMethod::generateTokenEx($req->number);
                if ($tokenExResult['error'] == 1) {
                    return response()->json(['error' => 1, 'msg' => "Invalid card number<br/>" . $tokenExResult['msg']]);
                }
                $tokenEx = $tokenExResult['token'];

                $addressId = \App\Address::updateRec(Auth::user()->id, \App\Address::TYPE_BILLING, 0, $req);
                \App\PaymentMethod::addNewRec(Auth::user()->id, 0, $tokenEx, $addressId, \App\PaymentMethodType::TYPE_CREDIT_CARD, $req);
                //
                $v = $this->goToPage(7);
            }
        }
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function goToPage($step, $showPaymentProceedLink = false) {
        $d = array();
        if ($step == 1) {
            $currentProductId = \App\User::getCurrentProductId(Auth::user()->id);
            if ($currentProductId == 14) {
                $d['iq_id'] = "";
            } else {
                $d['iq_id'] = Auth::user()->legacyid;
            }
            $d['button_name'] = \utill::isNullOrEmpty(Auth::user()->legacyid) ? "Skip" : "Enter";
            return (string) view('affiliate.user.question_list.step_1')->with($d);
        } else if ($step == 2) {
            return (string) view('affiliate.user.question_list.step_2')->with($d);
        } else if ($step == 3) {
            $d['showPaymentProceedLink'] = $showPaymentProceedLink;
            $d['countries'] = \App\Country::getAll();
            $d['address'] = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_BILLING);
            $d['payment'] = \App\PaymentMethod::getRec(Auth::user()->id, 1, \App\PaymentMethodType::TYPE_CREDIT_CARD);
            return (string) view('affiliate.user.question_list.step_3')->with($d);
        } else if ($step == 4) {
            $myPack = \App\PreEnrollmentSelection::getProductId(Auth::user()->id);
            $d['my_package'] = $myPack;
            if ($myPack == null || $myPack == 1) {
                $d['button_name'] = "Skip";
            } else {
                $d['button_name'] = "Next";
            }
            return (string) view('affiliate.user.question_list.step_4')->with($d);
        } else if ($step == 5) {
            return (string) view('affiliate.user.question_list.step_5')->with($d);
        } else if ($step == 6) {
            $d['countries'] = \App\Country::getAll();
            $d['address'] = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_BILLING, 0);
            $d['payment'] = \App\PaymentMethod::getRec(Auth::user()->id, 0, \App\PaymentMethodType::TYPE_CREDIT_CARD);
            return (string) view('affiliate.user.question_list.step_6')->with($d);
        } else if ($step == 7) {
            return (string) view('affiliate.user.question_list.step_7')->with($d);
        }
    }

    private function doVIPPayments($tokenEx, $cardNumber, $paymentMethodId, $req) {
        $expiry_date = $req->expiry_date;
        $temp = explode("/", $expiry_date);
        //
        $product = \App\Product::getProduct(\App\Product::ID_EB_FIRST_CLASS);
        $amount = $product->price;
        $deductedAmount = $amount;
        // if TV 14, then reduce 500
        $currentProductId = \App\User::getCurrentProductId(Auth::user()->id);
        if ($currentProductId == 14) {
            $deductedAmount = 500;
        } else {
            // deduct the IQ credit from it
            $iqCredit = \App\IQCredits::getCreditAmount(Auth::user()->legacyid);
            $deductedAmount = $amount - $iqCredit;
        }
        //
        if ($deductedAmount > 0) {
            //
            $nmiResult = NMIGateway::processPayment($cardNumber, $req->first_name, $req->last_name, $temp[0], $temp[1], $req->cvv, $deductedAmount, $req->address1, $req->city, $req->stateprov, $req->postalcode, $req->countrycode);
            //
            if ($nmiResult['error'] == 1) {
                $error = 1;
                $msg = "Payment Failed:<br/>" . $nmiResult['msg'];
            } else {
                //
                \App\PreEnrollmentSelection::addNewRec(Auth::user()->id, \App\Product::ID_EB_FIRST_CLASS);
                // set IQ credit used
                \App\IQCredits::setUsed(Auth::user()->legacyid);
                // place order
                $authorization = $nmiResult['authorization'];
                // create new order
                $orderId = \App\Order::addNew(Auth::user()->id, $deductedAmount, $deductedAmount, $product->bv, $product->qv, $product->cv, $authorization, $paymentMethodId, null, null);
                // create new order item
                \App\OrderItem::addNew($orderId, $product->id, 1, $amount, $product->bv, $product->qv, $product->cv);
                // set package info
                \App\User::setCurrentProductId(Auth::user()->id, $product->id);
                //
                $error = 0;
                $msg = "";
            }

            $result = array();
            $result['error'] = $error;
            $result['msg'] = $msg;

            return $result;
        } else {
            \App\PreEnrollmentSelection::addNewRec(Auth::user()->id, \App\Product::ID_EB_FIRST_CLASS);
            //
            $authorization = "";
            // create new order
            $orderId = \App\Order::addNew(Auth::user()->id, 0, 0, $product->bv, $product->qv, $product->cv, $authorization, $paymentMethodId, null, null);
            // create new order item
            \App\OrderItem::addNew($orderId, $product->id, 1, 0, $product->bv, $product->qv, $product->cv);
            // set package info
            \App\User::setCurrentProductId(Auth::user()->id, $product->id);
            //
            $error = 0;
            $msg = "";

            $result = array();
            $result['error'] = $error;
            $result['msg'] = $msg;

            return $result;
        }
    }

    public function questionListCompleted() {
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update([
                'basic_info_updated' => 1
            ]);
        return response()->json(['error' => 0, 'url' => 'reload']);
    }

    public function dlgCheckOutPayment() {
        $d = array();
        return view('affiliate.user.dlg_check_out_payment')->with($d);
    }

    public function getStates() {
        $req = request();
        $d['states'] = DB::table('states')
            ->select('*')
            ->where('country_code', $req->country_code)
            ->orderBy('name', 'asc')
            ->get()->toArray();
        $v = (string) view('affiliate.user.dlg_check_out_states_lists')->with($d);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    private function validateRec($recId = 0, $distid = null, $sponsorTSAid = null) {
        $req = request();
        $data = $req->all();
        $data['email'] = strtolower($data['email']);
        $data['username'] = strtolower($data['username']);
        if ($recId == 0) {
            $validator = Validator::make($data, [
                'firstname' => 'required',
                'lastname' => 'required',
                'distid' => 'required|unique:users,distid',
                'current_product_id' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'address1' => 'max:50',
                'address2' => 'max:50',
                'city' => 'max:25',
                'stateprov' => 'max:50',
                'postalcode' => 'max:10',
                'countrycode' => 'max:10',
            ], [
                'firstname.required' => 'First name is required',
                'lastname.required' => 'Last name is required',
                'distid.required' => 'Distributor ID is required',
                'distid.unique' => 'This distributor ID is already used',
                'current_product_id.required' => 'Select a product',
                'username.required' => 'Username is required',
                'username.unique' => 'This username is already used',
                'email.required' => 'Email is required',
                'email.email' => 'Invalid email format',
                'email.unique' => 'This email is already used',
                'address1.max' => 'Address 1 cannot exceed 50 charactors',
                'address2.max' => 'Address 2 cannot exceed 50 charactors',
                'city.max' => 'City cannot exceed 25 charactors',
                'stateprov.max' => 'State / Provice cannot exceed 50 charactors',
                'postalcode.max' => 'Postal code cannot exceed 10 charactors',
                'countrycode.max' => 'Country code cannot exceed 10 charactors',
            ]);
        } else {
            $data = $req->all();
            $data['email'] = strtolower($data['email']);
            $data['username'] = strtolower($data['username']);
            $validator = Validator::make($data, [
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required|unique:users,username,' . $recId,
                'email' => 'required|email|unique:users,email,' . $recId,
                'address1' => 'max:50',
                'address2' => 'max:50',
                'city' => 'max:25',
                'stateprov' => 'max:50',
                'postalcode' => 'max:10',
                'countrycode' => 'max:10',
            ], [
                'firstname.required' => 'First name is required',
                'lastname.required' => 'Last name is required',
                'username.required' => 'Username is required',
                'username.unique' => 'This username is already used',
                'email.required' => 'Email is required',
                'email.email' => 'Invalid email format',
                'email.unique' => 'This email is already used',
                'address1.max' => 'Address 1 cannot exceed 50 charactors',
                'address2.max' => 'Address 2 cannot exceed 50 charactors',
                'city.max' => 'City cannot exceed 25 charactors',
                'stateprov.max' => 'State / Provice cannot exceed 50 charactors',
                'postalcode.max' => 'Postal code cannot exceed 10 charactors',
                'countrycode.max' => 'Country code cannot exceed 10 charactors',
            ]);
        }

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
            // validate sponsor tree
            if ($recId > 0) {
                if ($distid == $req->sponsorid) {
                    $valid = 0;
                    $msg .= "<div>Dist ID and Sponsor cannot be same</div>";
                } else if ($sponsorTSAid != $req->sponsorid) {
                    $sponsorRes = DB::select("select * from valid_sponsor('" . $distid . "','" . $req->sponsorid . "')");
                    $sponsorRes = (array) $sponsorRes[0];
                    if ($sponsorRes['valid_sponsor'] != 1) {
                        $valid = 0;
                        $msg .= "<div>Invalid sponsor id found at sponsor tree</div>";
                    }
                }
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    private function validateChangePassword() {
        $req = request();
        $validator = Validator::make($req->all(), [
            'current_pass' => 'required',
            'pass_1' => 'required|min:6',
            'pass_2' => 'same:pass_1',
        ], [
            'current_pass.required' => 'Current password is required',
            'pass_1.required' => 'New password is required',
            'pass_1.min' => 'New password must be at least 6 charactors',
            'pass_2.same' => 'Passwords do not match',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
            //
            if (!Hash::check($req->current_pass, Auth::user()->password)) {
                $valid = 0;
                $msg = "Invalid current password";
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    private function validateSaveProfile($userId) {
        $req = request();
        $validator = Validator::make($req->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
        ], [
            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    private function validateSavePlacements() {
        $req = request();
        $validator = Validator::make($req->all(), [
            'binary_placement' => 'required'
        ], [
            'binary_placement.required' => 'You need to check atleast one option'
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    private function validatePrimaryAddress() {
        $req = request();
        $validator = Validator::make($req->all(), [
            'address1' => 'required|max:255',
            'city' => 'required|max:255',
            'stateprov' => 'required|max:50',
            'postalcode' => 'required|max:10',
            'countrycode' => 'required|max:10',
        ], [
            'address1.required' => 'Address is required',
            'address1.max' => 'Address exceed the limit',
            'countrycode.required' => 'Country is required',
            'countrycode.max' => 'Country exceed the limit',
            'city.required' => 'City / Town is required',
            'city.max' => 'City / Town exceed the limit',
            'stateprov.required' => 'State / Province is required',
            'stateprov.max' => 'State / Province exceed the limit',
            'postalcode.required' => 'Postal code is required',
            'postalcode.max' => 'Postal code exceed the limit'
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    private function validatePayAppTaxInformation() {
        $req = request();
        $validator = Validator::make($req->all(), [
            'ssn' => 'required|max:50',
//            'ein_or_fid' => 'required|max:50',
        ], [
            'ssn.required' => 'SSN is required',
            'ssn.max' => 'SSN cannot exceed 50 charactors',
//            'ein_or_fid.required' => 'EIN or FID is required',
//            'last_name.max' => 'EIN or FID cannot exceed 50 charactors',
        ]);
        $msg = "";
        $valid = 1;
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function frmEditAdminUser($userId) {
        if (\App\AdminPermission::sidebar_admin_users() || \App\AdminPermission::sidebar_cs_users()) {
            $user = \App\User::getById($userId);

            if ($user->usertype != \App\UserType::TYPE_ADMIN) {
                abort(404);
            } else {
                $d = array();
                $d['user'] = $user;
                if (\App\User::admin_cs_manager()) {
                    $d['back_to'] = url('/users/cs-users');
                    $d['cs'] = 1;
                    $d['saveBtnId'] = 'btnUpdateCSUser';
                    $d['saveLoginBtnId'] = 'btnUpdateCSUserLogin';
                } else {
                    $d['back_to'] = url('/users/admins');
                    $d['cs'] = 0;
                    $d['saveBtnId'] = 'btnUpdateAdminUser';
                    $d['saveLoginBtnId'] = 'btnUpdateAdminUserLogin';
                }

                // Don't allow super exec to edit super admin or super exec
                if (User::admin_super_exec() &&
                    in_array($user->admin_role, [\App\UserType::ADMIN_SUPER_ADMIN, \App\UserType::ADMIN_SUPER_EXEC])) {
                    return redirect($d['back_to']);
                }

                return view('admin.user.admin_user_edit')->with($d);
            }
        } else {
            return redirect('/');
        }
    }

    public function updateAdminUser() {
        if (\App\AdminPermission::sidebar_admin_users()) {
            $req = request();
            $vali = $this->validateAdminDetail();
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }
            //
            $rec = \App\User::find($req->rec_id);
            $rec->firstname = $req->firstname;
            $rec->lastname = $req->lastname;
            $rec->admin_role = $req->admin_role;
            $rec->mobilenumber = $req->mobilenumber;
            $rec->phone_country_code = $req->phone_country_code;
            $rec->save();
            return response()->json(['error' => '0', 'msg' => 'Saved']);
        } else {
            return response()->json(['error' => '1', 'msg' => 'Permission Denied']);
        }
    }

    public function updateCSUser() {
        if (\App\AdminPermission::sidebar_cs_users()) {
            $req = request();
            $vali = $this->validateAdminDetail();
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }
            //
            $rec = \App\User::find($req->rec_id);
            $rec->firstname = $req->firstname;
            $rec->lastname = $req->lastname;
            $rec->mobilenumber = $req->mobilenumber;
            $rec->phone_country_code = $req->phone_country_code;
            $rec->save();
            return response()->json(['error' => '0', 'msg' => 'Saved']);
        } else {
            return response()->json(['error' => '1', 'msg' => 'Permission Denied']);
        }
    }

    private function validateAdminDetail() {
        $rules = [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
        ];

        if (User::admin_super_exec()) {
            $rules['admin_role'] = 'gt:' . \App\UserType::ADMIN_SUPER_EXEC;
        }

        $errorMessages = [
            'firstname.required' => 'First name is required',
            'firstname.max' => 'First name exceed the limit',
            'lastname.required' => 'Last name is required',
            'lastname.max' => 'Last name exceed the limit',
            'admin_role.gt' => 'Unable set admin user to higher level than logged in admin user'
        ];

        $req = request();

        $validator = Validator::make($req->all(), $rules, $errorMessages);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function updateAdminUserLogin() {
        if (\App\AdminPermission::sidebar_admin_users()) {
            $req = request();
            $vali = $this->validateAdminLoginDetail($req->rec_id);
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }
            //
            $rec = \App\User::find($req->rec_id);
            $rec->email = $req->email;
            if (!\utill::isNullOrEmpty($req->default_password)) {
                $rec->default_password = $req->default_password;
                $rec->password = password_hash($req->default_password, PASSWORD_BCRYPT);
            }
            $rec->save();
            return response()->json(['error' => '0', 'msg' => 'Saved']);
        } else {
            return response()->json(['error' => '1', 'msg' => 'Permission Denied']);
        }
    }

    public function updateCSUserLogin() {
        if (\App\AdminPermission::sidebar_cs_users()) {
            $req = request();
            $vali = $this->validateAdminLoginDetail($req->rec_id);
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }
            //
            $rec = \App\User::find($req->rec_id);
            $rec->email = $req->email;
            if (!\utill::isNullOrEmpty($req->default_password)) {
                $rec->default_password = $req->default_password;
                $rec->password = password_hash($req->default_password, PASSWORD_BCRYPT);
            }
            $rec->save();
            return response()->json(['error' => '0', 'msg' => 'Saved']);
        } else {
            return response()->json(['error' => '1', 'msg' => 'Permission Denied']);
        }
    }

    private function validateAdminLoginDetail($recId) {
        $req = request();
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|unique:users,email,' . $recId,
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already used',
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function frmChangePasswordAdmin() {
        return view('admin.user.change_password');
    }

    public function frmNewAdmin() {
        if (\App\AdminPermission::sidebar_admin_users()) {
            $d = array();
            $d['title'] = "Add new administrator";
            $d['cs'] = 0;
            $d['back_to'] = url('/users/admins');
            return view('admin.user.frmNewAdmin')->with($d);
        } else if (\App\AdminPermission::sidebar_cs_users()) {
            $d = array();
            $d['title'] = "Add new CS user";
            $d['back_to'] = url('/users/cs-users');
            $d['cs'] = 1;
            return view('admin.user.frmNewAdmin')->with($d);
        } else {
            return redirect('/');
        }
    }

    public function addNewAdmin() {
        if (\App\AdminPermission::sidebar_admin_users() ||
            \App\AdminPermission::sidebar_cs_users()) {
            $req = request();
            $vali = $this->validateNewAdmin($req->rec_id);
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            }
            //
            $r = new \App\User();
            $r->firstname = $req->firstname;
            $r->lastname = $req->lastname;
            $r->email = $req->email;
            $r->default_password = $req->default_password;
            $r->password = password_hash($req->default_password, PASSWORD_BCRYPT);
            if (\App\User::admin_cs_manager()) {
                $r->admin_role = \App\UserType::ADMIN_CS;
                $redirecTo = url('/users/cs-users');
            } else {
                $r->admin_role = $req->admin_role;
                $redirecTo = url('/users/admins');
            }
            $r->usertype = \App\UserType::TYPE_ADMIN;
            $r->save();
            return response()->json(['error' => '0', 'url' => $redirecTo]);
        } else {
            return response()->json(['error' => '1', 'msg' => "Permission Denied"]);
        }
    }

    private function validateNewAdmin() {
        $req = request();

        $rules = [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'default_password' => 'required|min:6'
        ];

        if (User::admin_super_exec()) {
            $rules['admin_role'] = 'gt:' . \App\UserType::ADMIN_SUPER_EXEC;
        }

        $errorMessages =  [
            'firstname.required' => 'First name is required',
            'firstname.max' => 'First name exceed the limit',
            'lastname.required' => 'Last name is required',
            'lastname.max' => 'Last name exceed the limit',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already used',
            'default_password.required' => 'Default password is required',
            'default_password.min' => 'Default password must be atleast 6 charactors',
            'admin_role.gt' => 'Admin level is higher than logged in admin user'
        ];

        $validator = Validator::make($req->all(), $rules, $errorMessages);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    public function getEnrollmentsChart() {
        $req = request();
        $query = DB::table('venrollmentsbyday');
        if (isset($req->year)) {
            $query->whereYear("created_dt", $req->year);
            $query->whereMonth("created_dt", $req->month);
        } else {
            $query->whereYear("created_dt", date("Y"));
            $query->whereMonth("created_dt", date("m"));
        }
        $query->orderBy('created_dt', "asc");
        $recs = $query->get();
        return response()->json(['error' => '0', 'data' => $recs]);
    }

    public function deletePaymentMethod() {
        $request = request();

        $paymentMethod = PaymentMethod::getById($request->payment_method_id);
        if (!$paymentMethod) {
            return response()->json(['error' => '1', 'data' => 'Access denied']);
        }

        PaymentMethod::markAsDeleted($request->payment_method_id);

        if (Auth::user()->subscription_payment_method_id == $request->payment_method_id) {
            \App\User::where('id', Auth::user()->id)->update(['subscription_payment_method_id' => null]);
        }
        return response()->json(['error' => '0', 'data' => 'Payment method deleted', 'url' => 'reload']);
    }

    public function savePlacements() {
        $req = request();
        $loginUser = Auth::user();
        $vali = $this->validateSavePlacements();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        } else {
            \App\User::updatePlacements($loginUser->id, $req);
            return response()->json(['error' => '0', 'msg' => 'Saved']);
        }
    }

//    public function exportDistSpon() {
//        $recs = DB::table('users')
//                ->select('distid', 'sponsorid')
//                ->get();
//        $columns = array('dist_id', 'sponsor_id');
//        $file = fopen(storage_path('/dist_sponsor/20190404.csv'), 'w');
//        fputcsv($file, $columns);
//
//        foreach ($recs as $rec) {
//            fputcsv($file, array($rec->distid, $rec->sponsorid));
//        }
//        fclose($file);
//        dd('done');
//    }
//
//    public function importDistSpon() {
//        foreach (glob(storage_path('/dist_sponsor/20190404.csv')) as $filename) {
//            $file = fopen($filename, 'r');
//            $row = 0;
//            while (($line = fgetcsv($file)) !== FALSE) {
//                // skip header
//                if ($row > 0) {
//                    //\App\User::makeTVuserFirstClass($line[1]);
//                    DB::table('users')
//                            ->where('distid', $line[0])
//                            ->update([
//                                'sponsorid' => $line[1]
//                            ]);
//                }
//                $row++;
//            }
//            fclose($file);
//            //unlink($filename);
//        }
//        dd('done');
//    }
    public function showUpgradeControl() {
        $isCsExecOrManager = false;

        if (User::admin_cs_exec() || User::admin_cs_manager()) {
            $isCsExecOrManager = true;
        }

        if (\App\AdminPermission::sidebar_upgrade_control()) {
            return view('admin.upgrade_control.upgrade_control', ['isCsExecOrManager' => $isCsExecOrManager]);
        }

        return redirect('/');
    }

    public function saveCountdownExpiryDate() {
        if (\App\AdminPermission::sidebar_misc()) {
            $req = request();
            $vali = $this->validateCountdownExpiryDate();
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            } else {

                \App\User::updateCountdownExpiryDate($req);
                return response()->json(['error' => 0, 'msg' => 'Saved']);
            }
        }
        return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
    }

    public function saveCountdownExpiryDateBulk() {
        if (\App\AdminPermission::sidebar_misc()) {
            $req = request();
            $vali = $this->validateCountdownExpiryDate();
            if ($vali['valid'] == 0) {
                return response()->json(['error' => 1, 'msg' => $vali['msg']]);
            } else {

                \App\User::updateCountdownExpiryDateBulk($req);
                return response()->json(['error' => 0, 'msg' => 'Saved']);
            }
        }
        return response()->json(['error' => 1, 'msg' => 'Permission Denied']);
    }

    public function frmActiveOverride()
    {
        if (AdminPermission::sidebar_active_override()) {
            return view('admin.user.active_override');
        } else {
            return redirect('/');
        }
    }

    public function frmSubscriptionReactivate()
    {
        if (\App\AdminPermission::sidebar_subscription_reactivate()) {
            return view('admin.user.subscription_reactivate');
        } else {
            return redirect('/');
        }
    }

    public function subscriptionReactivate()
    {
        $req = request();
        $data = $req->all();
        if (!$req->exists('distid')) {
            $validator = Validator::make($data, [
                'distid' => 'required',
                'mode' => 'required',
            ], [
                'distid.required' => 'Dist ID is required',
                'mode.required' => 'Subscription re-activate status is required',
            ]);
        } else {
            $validator = Validator::make($data, [
                'distid' => 'required',
                'mode' => 'required',
            ], [
                'distid.required' => 'Dist ID is required',
                'mode.required' => 'Subscription re-activate status is required',
            ]);
        }
        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
            return response()->json(['error' => 1, 'msg' => $msg]);
        }
        if ($data['mode'] == "on") {
            DB::table('users')->where('distid', $data['distid'])->update(['is_sites_deactivate' => 1]);
            return response()->json(['error' => 0, 'msg' => $data['distid'] . " - Subscription reactivate set to ON", 'url' => 'reload']);
        } else {
            DB::table('users')->where('distid', $data['distid'])->update(['is_sites_deactivate' => 0]);
            return response()->json(['error' => 0, 'msg' => $data['distid'] . " - Subscription reactivate set to OFF", 'url' => 'reload']);
        }
    }

    public function activeOverrideCsvUpload(){
        $req = request();
        $fileName = "";
        if ($req->hasFile('tsa_override_csv')) {
            // unlink previous file
            $extension = $req->tsa_override_csv->getClientOriginalExtension();
            if ($extension != 'csv') {
                return response()->json(['error' => 1, 'msg' => 'Please upload csv file.']);
            }
            $fileName = $req->tsa_override_csv->store('csv/tsa_override_csv', 'local');
        }
        $filename = storage_path('app/'.$fileName);
        $fp = fopen($filename, 'r');
        $error_tsa = "";
        $count = 1;
        if (($headers = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($headers) {
                while (($line = fgetcsv($fp, 0, ",")) !== FALSE) {
                    if ($line) {
                        $distId = trim($line[0]);
                        if (utill::isNullOrEmpty($distId)) {
                            $error_tsa .= $distId . " - Tsa cannot be empty<br>";
                        } else {
                            // check if the user already is in-active
                            $user = User::where('distid', $distId)->first();
                            // get payment method of "admin" type
                            $paymentMethodId = PaymentMethod::getPaymentMethodIdOfPayMethodTypeAdmin($user->id);
                            // create empty order
                            \App\Order::addNew(
                                $user->id,
                                0,
                                0,
                                0,
                                100,
                                0,
                                'active_override',
                                $paymentMethodId,
                                null,
                                null
                            );
                            $count = $count + 1;
                            // $error_tsa .= $distId . " - Processed<br>";
                        }
                    }
                }
            }
        }
        if($count<2){
            return response()->json(['error' => 1, 'msg' => 'Could not read the file, please check the format and try again.']);
        }
        
        $error_tsa .= $count . " Records Processed";
        return response()->json(['error' => 0, 'msg' => $error_tsa]);
    }

    private function createFolder()
    {
        $response = Storage::makeDirectory(storage_path() . '/csv/tsa_override_csv/');
    }
    
    public function activeOverride()
    {
        $req = request();
        $distId = $req->distid;
        if(utill::isNullOrEmpty($distId))
            return response()->json(['error' => 1, 'msg' => 'Please select the user']);
        else {
            // check if the user already is in-active
            $user = User::where('distid', $distId)->first();
            $isActive = $user->isUserActive();
            if($isActive){
                return response()->json(['error' => 1, 'msg' => 'This user already is in active']);
            } else {
                // get payment method of "admin" type
                $paymentMethodId = PaymentMethod::getPaymentMethodIdOfPayMethodTypeAdmin($user->id);

                // create empty order
                \App\Order::addNew(
                    $user->id,
                    0,
                    0,
                    0,
                    100,
                    0,
                    'active_override',
                    $paymentMethodId,
                    null,
                    null
                );
                return response()->json(['error' => 0, 'msg' => 'Done']);
            }

        }
    }

    public function getUserInfo()
    {
        $request = request();

        $token = $request->header('ibuumerang-token');
        if (!$token) {
            return response()->json(['error' => 1, 'msg' => 'Token not found.']);
        }

        $isValid = \App\ApiToken::isValidToken($token);

        if (!$isValid) {
            return response()->json(['error' => 1, 'msg' => 'Invalid token.']);
        }

        $username = $request->input('username');

        $user = User::where('username', $username)
            ->where('account_status', User::ACC_STATUS_APPROVED)
            ->first();

        if (!$user) {
            return response()->json();
        }

        $rank = UserRankHistory::getCurrentMonthUserInfo($user->id);

        if ($user) {
            $preferences = $user->replicatedPreferences;
            $name = $user->firstname . ' ' . $user->lastname;

            if ($preferences) {
                switch ($preferences->show_name) {
                    case ReplicatedPreferences::TYPE_DISPLAYED:
                        $name = $preferences->displayed_name;
                        break;
                    case ReplicatedPreferences::TYPE_CO_NAME:
                        $name = $preferences->co_name;
                        break;
                    case ReplicatedPreferences::TYPE_BUSINESS:
                        $name = $preferences->business_name;
                        break;
                }
            }

            $name = $name ?: $user->firstname . ' ' . $user->lastname;
            $email = $user->email;
            $phone = $user->phonenumber;

            if ($preferences) {
                if (!$preferences->show_email) {
                    $email = '';
                } elseif ($preferences->email) {
                    $email = $preferences->email;
                }

                if (!$preferences->show_phone) {
                    $phone = '';
                } elseif ($preferences->phone) {
                    $phone = $preferences->phone;
                }
            }

            return response()->json([
                'username' => $name,
                'rank' => $rank ? $rank->achieved_rank_desc : 'Ambassador',
                'phone' => $phone,
                'email' => $email,
            ]);
        }

        return response()->json();
    }

    private function validateCountdownExpiryDate() {
        $req = request();
        $data = $req->all();
        if ($req->exists('distid')) {
            $validator = Validator::make($data, [
                'distid' => 'required',
                'coundown_expire_on' => 'required',
            ], [
                'distid.required' => 'Dist ID is required',
                'coundown_expire_on.required' => 'Countdown End Date is required',
            ]);
        } else {
            $validator = Validator::make($data, [
                'coundown_expire_on' => 'required',
            ], [
                'coundown_expire_on.required' => 'Countdown End Date is required',
            ]);
        }


        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
        } else {
            $valid = 1;
        }
        $res = array();
        $res["valid"] = $valid;
        $res["msg"] = $msg;
        return $res;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function updateUserTaxInfo(Request $request)
    {
        $user = Auth::user();

        try {
            if (!$data = $request->all()) {
                return [
                    "error" => 1,
                    'msg'   => 'Invalid data sent. Please enter your tax information and try again!'
                ];
            }

            // build validation
            $requiredValidations = [
                'ssn'                => 'required',
                'primary_payment_id' => 'required'
            ];
            $validationMessages = [
                'ssn.required' => 'Your SSN is required',
                'primary_payment_id.required' => 'You must select a primary payment card'
            ];

            // if set, ein is not required
            if (!isset($data['has_no_ein'])) {
                $requiredValidations['ein'] = 'required';
                $validationMessages['ein.required'] = 'Your business EIN is required';
            }

            // validate data
            $validator = Validator::make($data, $requiredValidations, $validationMessages);
            if ($validator->fails()) {
                $msg = '';
                $messages = $validator->messages();
                foreach ($messages->all() as $m) {
                    $msg .= "<div> - " . $m . "</div>";
                }

                return [
                    'error' => 1,
                    'msg'   => $msg
                ];
            }

            // save tax information
            self::saveTaxInfo($user, $data['ein'], $data['ssn']);

            //update primary card
            $paymentMethods = PaymentMethod::where('userID', $user->id)->get();
            /**@var PaymentMethod $paymentMethod */
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethod->primary = (int)$paymentMethod->id == (int)$data['primary_payment_id'] ? 1 : 0;
                $paymentMethod->save();
            }

            // update user
            $user->is_tax_confirmed = 1;
            $user->save();

            return [
                'error' => 0,
                'msg'   => 'Thank you you tax information has been confirmed!',
                'url'   => 'reload'
            ];
        } catch (\Exception $ex) {
            return [
                'error' => 1,
                'msg'   => $ex->getMessage()
            ];
        }
    }


    /**
     * @param Request $request
     * @return array
     */
    public function updateUserTaxInfoInternational(Request $request)
    {
        $user = Auth::user();

        try {
            if (!$data = $request->all()) {
                return [
                    "error" => 1,
                    'msg' => 'Invalid data sent. Please enter your card Information and try again.'
                ];
            }

            if ( $user->is_tax_confirmed === 0 ){
                return [
                    "error" => 1,
                    'msg' => 'You must fill out and sign the required tax form. Please click the SIGN FORM button to proceed.'
                ];
            }


            //update primary card
            $paymentMethods = PaymentMethod::where('userID', $user->id)->get();
            /**@var PaymentMethod $paymentMethod */
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethod->primary = (int)$paymentMethod->id == (int)$data['primary_payment_id'] ? 1 : 0;
                $paymentMethod->save();
            }

            return [
                'error' => 0,
                'msg' => 'Thank you you tax information has been confirmed!',
                'url' => 'reload'
            ];
        } catch (\Exception $ex) {
            return [
                'error' => 1,
                'msg' => $ex->getMessage()
            ];
        }

    }


//    /**
//     * @return array
//     */
//    public function updateUserTaxConfirmedFlag($distid)
//    {
//        $user = \App\User::getByDistId($distid);
//
//        try {
//            // update user
//            $user->is_tax_confirmed = 1;
//            $user->save();
//
//            return "saved";
//
//        } catch (\Exception $ex) {
//            throw new \Exception('Unable to update tax information flag! Please try again or contact support.');
//        }
//    }


    /**
     * @param User $user
     * @param string $ein
     * @param string $ssn
     * @return array
     * @throws \Exception
     */
    private function saveTaxInfo($user, $ein, $ssn)
    {
        try {
            $hasChange = false;
            if (strcasecmp($user->ssn, $ssn) !== 0) {
                $hasChange = true;
                $user->ssn = $ssn;
            }
            if (strcasecmp($user->ein, $ein) !== 0) {
                $hasChange = true;
                $user->ein = $ein;
            }

            if ($hasChange) {
                $user->save();
            }
        } catch (\Exception $ex) {
            throw new \Exception('Unable to save tax information! Please try again or contact support');
        }
    }


    public function frmAmbassadorReactivate()
    {
        if (\App\AdminPermission::sidebar_ambassador_reactivate()) {
            return view('admin.user.ambassador_reactivate');
        } else {
            return redirect('/');
        }
    }

    public function ambassadorReactivate()
    {
        $req = request();
        $data = $req->all();

        $validator = Validator::make($data, [
            'distid' => 'required'
        ], [
            'distid.required' => 'Dist ID is required'
        ]);

        $msg = "";
        if ($validator->fails()) {
            $valid = 0;
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
            return response()->json(['error' => 1, 'msg' => $msg]);
        }

        if (!$user = User::where('distid', $data['distid'])) {
            return response()->json(['error' => 1, 'msg' => 'Unable to find user record for this distributor']);
        }

        $user->update([
            'payment_fail_count' => 0,
            'account_status' => \App\User::ACC_STATUS_SUSPENDED
        ]);

        return response()->json([
            'error' => 0,
            'msg' => $data['distid'] . " - Distribuitor is now reactivated",
            'url' => 'reload'
        ]);
    }

    /**
     * Check user data.
     *
     * This method checks and returns the user info. It's used to
     * display and populate modal with their information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checksUserData()
    {
        $data = [];

        // Gets the current logged user data
        $user = auth()->user();

        if (!$user->modal_is_complete) {
            // Personal Data Info
            $data['personalUserData'] = [
                'firstName' => $user->firstname,
                'lastName' => $user->lastname,
                'phone' => $user->phonenumber,
                'username' => $user->username,
                'recognitionName' => $user->recognition_name,
                'businessName' => $user->business_name,
                'emailAddress' => $user->email,
                'informationIsCorrect' => (bool) $user->information_is_correct
            ];

            // Address Info
            // Gets the user's personal address data
            $personalAddress = $user->addresses()->where('addrtype', '3')->first();

            // Gets the user's billing address data
            $billingAddress = $user->addresses()->where('addrtype', '1')->first();

            $data['addressUser'] = [
                'primaryAddress' => $personalAddress->address1,
                'primaryAptSuite' => $personalAddress->apt,
                'primaryCountry' => $personalAddress->countrycode,
                'primaryCityTown' => $personalAddress->city,
                'primaryStateProvince' => $personalAddress->stateprov,
                'primaryPostalCode' => $personalAddress->postalcode,
                'billingAddress' => $billingAddress->address1,
                'billingAptSuite' => $billingAddress->apt,
                'billingCountry' => $billingAddress->countrycode,
                'billingCityTown' => $billingAddress->city,
                'billingStateProvince' => $billingAddress->stateprov,
                'billingPostalCode' => $billingAddress->postalcode
            ];

            // Credit Cards Info
            $creditCards = $user->paymentMethods()->whereNotNull('token')->select(['id', 'token', 'primary'])->get()->each(function ($cc) {
                $cc->token = substr_replace($cc->token, 'XXXX XXXX XXXX ', 0, 12);
                $cc->primary = (bool) $cc->primary;
            });

            $data['ccTax'] = [
                'creditCards' => $creditCards,
                'newCreditCards' => [],
                'ssn' => $user->ssn,
                'isUSCitizen' => $personalAddress->countrycode === 'US'
            ];

            // Esign
            $data['ccTax']['formIsSigned'] = (bool) \DB::table('user_assets')->where('user_id', '=', Auth::id())->first();

            // Ticket for Xccelerate
            $query = DB::table('orders')->join('orderItem', function ($join) {
                $join
                    ->on('orders.id', '=', 'orderItem.orderid')
                    ->whereIn('orderItem.productid', ['38']);
            })
                ->select('orders.*')
                ->where('orders.userid', '=', $user->id)
                ->where('orders.statuscode', '=', '1')
                ->get();

            $data['ticket'] = [
                'hasTicket' => $query->count() >= 1,
                'willBeAttending' => false
            ];
        }

        // Returns the data
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function savesUserData(Request $request)
    {
        $data = [];

        $user = Auth::user();

        if (array_key_exists('personalUserData', $request->userData)) {
            $personalUserData = $request->userData['personalUserData'];
            try {
                $user->fill([
                    'recognition_name' => $personalUserData['recognitionName'],
                    'phonenumber' => $personalUserData['phone'],
                    'information_is_correct' => $personalUserData['informationIsCorrect']
                ])->save();
            } catch (\Exception $e) {
                return response()->json(['error_message' => $e->getMessage()], 500);
            }

            $data['response'][] = 'User data updated successfully.';
        }

        if (array_key_exists('addressUser', $request->userData)) {
            $personalUserAddress = $request->userData['addressUser'];

            // Primary address
            $personalAddress = $user->addresses()->where('addrtype', '3')->first();

            try {
                $personalAddress->update([
                    'address1' => $personalUserAddress['primaryAddress'],
                    'city' => $personalUserAddress['primaryCityTown'],
                    'apt' => $personalUserAddress['primaryAptSuite'],
                    'stateprov' => $personalUserAddress['primaryStateProvince'],
                    'postalcode' => $personalUserAddress['primaryPostalCode'],
                    'countrycode' => $personalUserAddress['primaryCountry']
                ]);
            } catch (\Exception $e) {
                return response()->json(['error_message' => $e->getMessage()], 500);
            }

            // Billing address
            $billingAddress = $user->addresses()->where('addrtype', '1')->first();

            try {
                $billingAddress->update([
                    'address1' => $personalUserAddress['billingAddress'],
                    'city' => $personalUserAddress['billingCityTown'],
                    'apt' => $personalUserAddress['billingAptSuite'],
                    'stateprov' => $personalUserAddress['billingStateProvince'],
                    'postalcode' => $personalUserAddress['billingPostalCode'],
                    'countrycode' => $personalUserAddress['billingCountry']
                ]);
            } catch (\Exception $e) {
                return response()->json(['error_message' => $e->getMessage()], 500);
            }

            // Saves the location from Global API
            $user->update([
                'country_code_by_ip' => $this->globalApi()
            ]);

            $data['response'][] = 'User address data updated successfully.';
        }

        if (array_key_exists('ccTax', $request->userData)) {
            $ccData = $request->userData['ccTax'];

            foreach ($ccData['creditCards'] as $cc) {
                if (isset($cc['id'])) {
                    \App\PaymentMethod::find($cc['id'])->update([
                        'primary' => $cc['primary']
                    ]);
                } else {
                    \App\PaymentMethod::create([
                        'userID' => $user->id,
                        'primary' => false,
                        'token' => $cc['token'],
                        'cvv' => $cc['cvv'],
                        'expMonth' => $cc['expMonth'],
                        'expYear' => $cc['expYear'],
                        'firstname' => $cc['firstName'],
                        'lastname' => $cc['lastName']
                    ]);
                }
            }

            $data['response'][] = 'Credit cards data updated successfully.';

            $user->update([
                'ssn' => str_replace('-', '', $ccData['ssn'])
            ]);
        }

        if (array_key_exists('ticket', $request->userData) && $request->userData['ticket']['hasTicket']) {
            try {
                $idOrderItem = DB::table('orders')->join('orderItem', function ($join) {
                    $join
                        ->on('orders.id', '=', 'orderItem.orderid')
                        ->whereIn('orderItem.productid', ['38']);
                })
                    ->select('orderItem.id')
                    ->where('orders.userid', '=', $user->id)
                    ->where('orders.statuscode', '=', '1')
                    ->first();

                \App\OrderItem::findOrFail($idOrderItem->id)->update([
                    'will_be_attend' => $request->userData['ticket']['willBeAttending']
                ]);
            } catch (\Exception $e) {
                return response()->json(['error_message' => $e->getMessage()], 500);
            }

            $data['response'][] = 'Ticket data updated successfully.';
        }

        $user->update([
            'modal_is_complete' => true
        ]);

        return response()->json($data);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function globalApi(): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf("https://ip-geo-location.p.rapidapi.com/ip/%s?format=json", $_SERVER['REMOTE_ADDR']),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: ip-geo-location.p.rapidapi.com",
                "x-rapidapi-key: b970b70813msh347d85ee7183160p121e09jsn10280da14c4d"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception($err);
        }
        return json_decode($response)->country->code;
    }

    /**
     * @param $distid
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendWelcomeEmail($distId) {
        try {
            if (!$user = User::where('distid', $distId)->first()) {
                return response()->json(['error' => 1, 'msg' => 'User could not be found']);
            }

            $msg = 'Welcome Email resent succesfully!';

            if ($error = MyMail::resendWelcomeEmail($user)) {
                $msg = "Couldn't resend Welcome Email";
            }

            return response()->json(['error' => $error, 'msg' => $msg]);
        } catch(Exception $e) {
            $exceptionMessage = '[' . __CLASS__ . '][' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($exceptionMessage);

            return response()->json(['error' => 1, 'msg' => 'Unknown error']);
        }
    }

    /**
     * @param $distid
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromMailgun($distId, MailGunMailList $mailgun) {
        try {
            if (!$user = User::where('distid', $distId)->first()) {
                return response()->json(['error' => 1, 'msg' => 'User could not be found']);
            }
            $productId = $user->current_product_id;
            $result = $mailgun->query()->select(['address'])
                ->where('product_id', $productId)
                ->first();
            if (!$result) {
                
                return;
            }

            $mailgun->deleteMail($result->address, $user->email);
            $user->update([
                'sync_with_mailgun' => 1
            ]);

            return response()->json(['error' => '', 'msg' => 'Email successfully removed']);

        } catch(Exception $e) {
            $exceptionMessage = '[' . __CLASS__ . '][' . __FUNCTION__ . '] ' . $e->getMessage();
            Log::error($exceptionMessage);

            return response()->json(['error' => 1, 'msg' => 'Connection Error: '.$e->getMessage()]);
        }
    }

    /**
     * Create token for the user to authenticate as ambassador
     */
    public function authTokenAmbassador($distId){
        try {
            if(!User::isAdmin()){
                return response()->json(['error' => 1, 'msg' => 'Invalid Token. Please contact system administrator.']);
            }

            $user = User::getByDistId($distId);

            $unique_token = md5($distId . time());

            $expiration             = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');

            $token                  = new UserAuthSsoToken();
            $token->user_id         = $user->id;
            $token->token           = trim($unique_token);
            $token->user_id         = $user->id;
            $token->expiration_date = $expiration;
            $token->save();


            $msg = 'Token Created successfully';

            $url = Config::get('app.ambassador_url').'/login-as-user/'.$distId.'/'.trim($unique_token);
            return response()->json(['error' => 0,
                'msg' => $msg,
                'ambassador_url' => $url
            ]);
        } catch(Exception $e) {
            return response()->json(['error' => 1, 'msg' => 'Could not create token. Please contact system administrator.']);
        }
    }
}
