<?php

namespace App\Http\Controllers;

use App\Facades\BinaryPlanManager;
use App\Models\ReplicatedPreferences;
use App\PaymentMethod;
use App\RankDefinition;
use App\RankInterface;
use App\Services\BinaryPlanService;
use App\Services\SubscriptionGroupService;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use utill;
use Validator;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /** @var BinaryPlanService */
    private $binaryPlanService;

    /** @var SubscriptionGroupService */
    private $subscriptionGroupService;

    /**
     * DashboardController constructor.
     * @param BinaryPlanService $binaryPlanService
     * @param SubscriptionGroupService $subscriptionGroupService
     */
    public function __construct(
        BinaryPlanService $binaryPlanService,
        SubscriptionGroupService $subscriptionGroupService
    ) {
        $this->binaryPlanService = $binaryPlanService;
        $this->subscriptionGroupService = $subscriptionGroupService;
        $this->middleware('auth.admin', ['only' => [
            'getTotalOrderSumChart'
        ]]);
        $this->middleware('auth.affiliate', ['only' => [
            'gotoStoreFront',
            'iGo',
            'idecide',
            'getCurrentMonthTotalCV',
            'savePreferences',
            'resetPreferences'
        ]]);
        $this->middleware('auth');
    }

    public function index() {
        $user = Auth::user();

        if (\App\User::isAdmin()) {
            return $this->adminDashboard();
        } elseif (!\App\User::isAffiliateUser()) {
            return view('affiliate.dashboard.suspended_user.index');
        } elseif (!(int)$user->is_tax_confirmed) {
            if ( $user->country_code  === 'US' || $user->country_code  === 'VI' || $user->country_code  === 'UM' )
            {
                $paymentMethods = PaymentMethod::getAllRec($user->id);
                $data = [
                    'user' => $user,
                    'cvv' => $paymentMethods
                ];

                return view('affiliate.dashboard.user.confirm_tax_payment')->with($data);

            } else {
                // NON US - ignore tax for now
                 return $this->internDashboard();

                // Send users to form W8BEN
//                $paymentMethods = PaymentMethod::getAllRec($user->id);
//                $data = [
//                    'user' => $user,
//                    'cvv' => $paymentMethods
//                ];
//                return view('affiliate.dashboard.user.confirm_tax_payment_intl')->with($data);
            }
        } else {
            // Return Ambassador/intern dash
            return $this->internDashboard();
        }
    }

    public function pageNotFound() {
        if (\App\User::isAdmin())
            return view('admin.errors.404');
        else
            return view('affiliate.errors.404');
    }

    public function gotoStoreFront() {
        $user = Auth::user();
        $data = array(
            "action" => "sso",
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email,
            "timestamp" => time()
        );
        $url = \App\StoreAPI::getUrl($data);
        return redirect($url);
    }

    private function adminDashboard() {
        $d = array();
        //
        $standby_count = 0;
        $coach_count = 0;
        $business_count = 0;
        $first_count = 0;
        $vip_count = 0;
        $grandfathering_count = 0;
        $premium_first_count = 0;
        //
        $d['enrollments'] = \App\User::getTotalEnrollments();
        $packs = \App\User::getGroupCount();
        foreach ($packs as $pack) {
            if ($pack->current_product_id == \App\Product::ID_NCREASE_ISBO) {
                $standby_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_BASIC_PACK) {
                $coach_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_VISIONARY_PACK) {
                $business_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_FIRST_CLASS) {
                $first_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_EB_FIRST_CLASS) {
                $vip_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_Traverus_Grandfathering) {
                $grandfathering_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_PREMIUM_FIRST_CLASS) {
                $premium_first_count = $pack->total;
            }
        }
        $d['coach_count'] = $coach_count;
        $d['business_count'] = $business_count;
        $d['first_count'] = $first_count;
        $d['vip_count'] = $vip_count;
        $d['grandfathering_count'] = $grandfathering_count;
        $d['standby_count'] = $standby_count;
        $d['premium_first_count'] = $premium_first_count;

        $years = DB::select("select extract(year from created_dt) as year from orders group by 1 order by year asc");
        $d['order_years'] = $years;
        $d['order_months'] = array(
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December",
        );
        //
        $d['show_graph'] = \App\AdminPermission::fn_show_graph();
        return view('admin.dashboard.index')->with($d);
    }

    private function internDashboard() {
        $d = array();
        $d['promo'] = \App\PromoInfo::getPromoSummary();
        //
        $standby_count = 0;
        $coach_count = 0;
        $business_count = 0;
        $first_count = 0;
        $vip_count = 0;
        $grandfathering_count = 0;
        //
        $d['enrollments'] = \App\User::getTotalEnrollments(Auth::user()->distid);
        $packs = \App\User::getGroupCountByUser(Auth::user()->distid);
        foreach ($packs as $pack) {
            if ($pack->current_product_id == \App\Product::ID_NCREASE_ISBO) {
                $standby_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_BASIC_PACK) {
                $coach_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_VISIONARY_PACK) {
                $business_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_FIRST_CLASS) {
                $first_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_EB_FIRST_CLASS) {
                $vip_count = $pack->total;
            }
            if ($pack->current_product_id == \App\Product::ID_Traverus_Grandfathering) {
                $grandfathering_count = $pack->total;
            }
        }

        $d['coach_count'] = $coach_count;
        $d['business_count'] = $business_count;
        $d['first_count'] = $first_count;
        $d['standby_count'] = $standby_count;
        $d['vip_count'] = $vip_count;
        $d['grandfathering_count'] = $grandfathering_count;
        $d['totalFirst'] = $vip_count + $first_count;
        //
        $currentProductId = \App\User::getCurrentProductId(Auth::user()->id);
        if ($currentProductId == \App\Product::ID_FIRST_CLASS ||
            $currentProductId == \App\Product::ID_EB_FIRST_CLASS) {
            $currentPackageName = "Founders";
        } else {
            $currentPackageName = \App\Product::getProductName($currentProductId);
        }

        $d['currentPackageName'] = $currentPackageName;
        // show package options
        $showCoachClass = false;
        $showBusinssClass = false;
        $showFirstClass = false;
        $showPremiumFC = false;
        $showVibeOverdrive = false;
        $showUpgradeBtn = false;
        $colWidth = 3;

        // check for premium first class
        $accessToPrimaryFC = false;
        $primaryAddress = \App\Address::getRec(Auth::user()->id, \App\Address::TYPE_BILLING);
        if (!empty($primaryAddress)) {
            $countryCode = $primaryAddress->countrycode;
            if ($countryCode == "FO") {
                $accessToPrimaryFC = true;
            }
        }

        if ($currentProductId == \App\Product::ID_NCREASE_ISBO) {
            $showCoachClass = true;
            $showBusinssClass = true;
            $showFirstClass = true;
            $showUpgradeBtn = true;
            $showVibeOverdrive = true;
            if ($accessToPrimaryFC) {
                $showPremiumFC = true;
                $colWidth = 2;
            }
        } else if ($currentProductId == \App\Product::ID_VIBE_OVERDRIVE_USER) {
            $showCoachClass = true;
            $showBusinssClass = true;
            $showFirstClass = true;
            $showUpgradeBtn = true;
            if ($accessToPrimaryFC) {
                $showPremiumFC = true;
                $colWidth = 3;
            } else {
                $colWidth = 4;
            }
        } else if ($currentProductId == \App\Product::ID_BASIC_PACK) {
            $showBusinssClass = true;
            $showFirstClass = true;
            $showUpgradeBtn = true;
            if ($accessToPrimaryFC) {
                $showPremiumFC = true;
                $colWidth = 4;
            } else {
                $colWidth = 6;
            }
        } else if ($currentProductId == \App\Product::ID_VISIONARY_PACK ||
            $currentProductId == \App\Product::ID_Traverus_Grandfathering
        ) {
            $showFirstClass = true;
            $showUpgradeBtn = true;
            if ($accessToPrimaryFC) {
                $showPremiumFC = true;
                $colWidth = 6;
            } else {
                $colWidth = 12;
            }
        } else if ($currentProductId == \App\Product::ID_EB_FIRST_CLASS ||
            $currentProductId == \App\Product::ID_FIRST_CLASS
        ) {
            if ($accessToPrimaryFC) {
                $showUpgradeBtn = true;
                $showPremiumFC = true;
                $colWidth = 12;
            }
        }

        $d['showVibeOverdrive'] = $showVibeOverdrive;
        $d['showCoachClass'] = $showCoachClass;
        $d['showBusinssClass'] = $showBusinssClass;
        $d['showFirstClass'] = $showFirstClass;
        $d['showPremiumFC'] = $showPremiumFC;
        $d['colWidth'] = $colWidth;
        $d['showUpgradeBtn'] = $showUpgradeBtn;
        $d['currentProductId'] = $currentProductId;

        $d['show_reload_button'] = true;


        //
        $isTvUser = \App\User::isTvUser(Auth::user()->id);
        if ($isTvUser) {
            $d['idecide_info'] = \App\IDecide::getIDecideUserInfo(Auth::user()->id);
        }
        $d['is_tv_users'] = $isTvUser;
        // rank
        $current_rank_info = \App\UserRankHistory::getCurrentMonthUserInfo(Auth::user()->id);
        if ($current_rank_info == null) {
            $rank = 10;
            $achieved_rank_desc = strtoupper("Ambassador");
            $monthly_rank_desc = strtoupper("Ambassador");
            $monthly_qv = 0;
            $monthly_tsa = 0;
            $monthly_qc = 0;
        } else {
            $rank = $current_rank_info->monthly_rank;
            $achieved_rank_desc = strtoupper($current_rank_info->achieved_rank_desc);
            $monthly_rank_desc = strtoupper($current_rank_info->monthly_rank_desc);
            $monthly_qv = number_format($current_rank_info->monthly_qv);
            $monthly_tsa = number_format($current_rank_info->monthly_tsa);
            $monthly_qc = number_format($current_rank_info->monthly_qc);
        }

        $paidRank = Auth::user()->getCommissionRank(Carbon::now());
        $paidRank = RankDefinition::where('id', $paidRank)->first();

        $d['achieved_rank_desc'] = $achieved_rank_desc;
        $d['monthly_rank_desc'] = $monthly_rank_desc;
        $d['paidRank'] = strtoupper($paidRank->rankdesc);
        $d['monthly_qv'] = Auth::user()->current_month_qv;
        $d['monthly_tsa'] = $monthly_tsa;
        $d['monthly_qc'] = $monthly_qc;
        $d['upper_ranks'] = \App\RankDefinition::getUpperRankInfo($rank);
        $d['rank_matric'] = \App\UserRankHistory::getRankMatrics(Auth::user()->distid, $rank);
        $d['qcContributors'] = Auth::user()->getTopQCLegs();
        $d['contributors'] = \App\UserRankHistory::getTopContributors(Auth::user()->distid, $rank);
        $d['qv'] = $current_rank_info ? $current_rank_info->qualified_qv : 0;
        $d['tsaRank'] = $rank >= RankInterface::RANK_VALUE_EXECUTIVE;
        $d['activeQC'] = Auth::user()->getActiveQC();
        $d['qualifyingQC'] = Auth::user()->getQualifyingQC();
        $d['limit'] = Auth::user()->getRankLimit();
        $d['font'] = ['brand', 'success', 'info', 'warning', 'danger'];
        $d['binaryQualified'] = Auth::user()->getBinaryQualifiedValues();
        $prevRank = \App\UserRankHistory::getRankInMonth(
            Auth::user(),
            utill::getUserCurrentDate()->modify('last day of previous month')->endOfMonth()->startOfDay()
        );

        if ($prevRank) {
            $d['prevRank'] = strtoupper($prevRank->monthly_rank_desc);
            $d['prevQv'] = $prevRank->qualified_qv;
        } else {
            $d['prevRank'] = strtoupper("Ambassador");
            $d['prevQv'] = 0;
        }

        // business snapshot
        $businessSS = \App\UserRankHistory::getCurrentMonthlyRec(Auth::user()->id);
        if (empty($businessSS)) {
            $biz_acheived_rank = "-";
            $biz_monthly_qv = 0;
            $biz_qulified_vol = 0;
            $biz_monthly_cv = 0;
        } else {
            $biz_acheived_rank = $businessSS->rankdesc;
            $biz_monthly_qv = $businessSS->monthly_qv;
            $biz_qulified_vol = $businessSS->qualified_qv;
            $biz_monthly_cv = $businessSS->monthly_cv;
        }
        //
        $d['biz_acheived_rank'] = $biz_acheived_rank;
        $d['biz_monthly_qv'] = $biz_monthly_qv;
        $d['biz_monthly_cv'] = $biz_monthly_cv;
        $d['biz_qulified_vol'] = $biz_qulified_vol;
        $d['is_active'] = Auth::user()->getCurrentActiveStatus();
        $pv = \App\Order::getThisMonthOrderQV(Auth::user()->id);
        $d['pv'] = $pv > 100 ? 100 : $pv;
        // get current month total cv of all personal enrollments
        $d['total_current_month_cv'] = $this->getCurrentMonthTotalCV(Auth::user()->distid);
        //
        $targetNode = \App\Models\BinaryPlanNode::where('user_id', Auth::user()->id)->first();
        if (empty($targetNode)) {
            $d['total_left'] = 0;
            $d['total_right'] = 0;
        } else {
            $d['total_left'] = $this->getLeftBinaryTotal($targetNode);
            $d['total_right'] = $this->getRightBinaryTotal($targetNode);
        }
        //
        $d['current_month_commission'] = \App\Commission::getCurrentMonthCommission(Auth::user()->id);
        // get previous month recs
        $prev_rec = \App\UserRankHistory::getPreviousMonthlyRec(Auth::user()->id);
        if (empty($prev_rec)) {
            $prev_biz_acheived_rank = "-";
        } else {
            $prev_biz_acheived_rank = strtoupper($prev_rec->rankdesc);
        }
        $d['prev_biz_acheived_rank'] = $prev_biz_acheived_rank;
        $d['subscriptionTypes'] = $this->subscriptionGroupService->getSubscriptionTypes(Auth::user());

        $user = Auth::user();
        $preferences = $user->replicatedPreferences;

        $d['preferences'] = [
            'buiness_name' => $preferences && $preferences->displayed_name ? $preferences->displayed_name : '',
            'displayed_name' => $preferences && $preferences->business_name ? $preferences->business_name : $user->firstname . ' ' . $user->lastname,
            'name' => $user->firstname . ' ' . $user->lastname,
            'co_name' => $user->co_applicant_name,
            'co_display_name' => $preferences && $preferences->co_name ? $preferences->co_name : $user->co_applicant_name,
            'phone' => $preferences && $preferences->phone ? $preferences->phone : $user->phonenumber,
            'email' => $preferences && $preferences->email ? $preferences->email : $user->email,
            'show_email' => $preferences ? $preferences->show_email : 0,
            'show_phone' => $preferences ? $preferences->show_phone : 0,
            'show_name' => $preferences ? $preferences->show_name : 1,
            'disable_co_app' => !$user->co_applicant_name,
        ];

        $d['user'] = $user;
        $d['showVibeAgreementModal'] = User::isVibeImportUser() && Auth::user()->has_agreed_vibe == false;

        return view('affiliate.dashboard.index')->with($d);
    }

    public function vibeAgreeForm()
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'sometimes|unique:users,username',
            'email' => 'sometimes|email|unique:users,email',
            'phonenumber' => 'required',
            'agree_all' => 'required|accepted'
        ];

        $messages = [
            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'username.required' => 'Username is required',
            'username.unique' => 'This username is already used',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'This email is already used',
            'phonenumber.required' => 'A phone number is required',
            'agree_all.required' => 'You must read & agree to continue',
        ];

        $data = request()->post();

        // If the user's username and/or email are unchanged, we don't need to do anything with the information

        $user = Auth::user();

        if ($data['username'] == $user->username) {
            unset($data['username']);
        }

        if ($data['email'] == $user->email) {
            unset($data['email']);
        }

        $validator = Validator::make($data, $rules, $messages);

        $errorMessage = $this->generateErrorMessageFromValidator($validator);

        if (!empty($errorMessage)) {
            return response()->json(['error' => '1', 'msg' => $errorMessage]);
        }

        $data['has_agreed_vibe'] = true;
        $user->fill($data);
        $user->save();

        return response()->json(['error' => '0', 'msg' => 'Account details saved', 'url' => '/my-profile/billing']);
    }

    public function iGo() {
        $agreed = \App\ProductTermsAgreement::getByUserId(Auth::user()->id, 'sor');
        if (empty($agreed)) {
            $v = (string)view('affiliate.agreement.igo');
            return response()->json(['error' => 1, 'v' => $v]);
        }

        $currentProductId = \App\User::getCurrentProductId(Auth::user()->id);
        if ($currentProductId == 0)
            return response()->json(['error' => '1', 'msg' => 'Get your enrollment pack now!']);

        if ($currentProductId == 13)
            $currentProductId = 4;
        if ($currentProductId == 14)
            $currentProductId = 3;


        $response = \App\SaveOn::SSOLogin($currentProductId, Auth::user()->distid);
        return response()->json($response);
    }

    public function idecide() {
        $agreed = \App\ProductTermsAgreement::getByUserId(Auth::user()->id, 'idecide');
        if (empty($agreed)) {
            $v = (string)view('affiliate.agreement.idecide');
            return response()->json(['error' => 1, 'v' => $v]);
        }
        $idecideUserRec = DB::table('idecide_users')
            ->where('user_id', Auth::user()->id)
            ->first();
        if (empty($idecideUserRec)) {
            return response()->json(['error' => '1', 'msg' => 'iDecide service not available for your account. Please contact us to activate your iDecide Services']);
        }
        $response = \App\IDecide::SSOLogin($idecideUserRec);
        return response()->json($response);
    }

    public function getTotalOrderSumChart() {
        $req = request();
        $query = DB::table('v_total_order_sum_for_month');
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

    private function getLeftBinaryTotal($targetNode) {
        $mondayDate = date('Y-m-d', strtotime('monday this week'));
        $leftLeg = BinaryPlanManager::getLeftLeg($targetNode);
        $currentLeftAmount = 0;
        if ($leftLeg) {
            $currentLeftAmount = BinaryPlanManager::getNodeTotal($leftLeg, $mondayDate);
        }

        return $currentLeftAmount;
    }

    private function getRightBinaryTotal($targetNode) {
        $mondayDate = date('Y-m-d', strtotime('monday this week'));
        $rightLeg = BinaryPlanManager::getRightLeg($targetNode);
        $currentRightAmount = 0;
        if ($rightLeg) {
            $currentRightAmount = BinaryPlanManager::getNodeTotal($rightLeg, $mondayDate);
        }

        return $currentRightAmount;
    }

    public function getCurrentMonthTotalCV($distId) {
        $rec = DB::select("SELECT sum(current_month_cv) as cv FROM enrolment_tree_tsa('$distId')");
        if (count($rec) > 0)
            return $rec[0]->cv;
        else
            return 0;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePreferences()
    {
        $request = request();

        /** @var User $user */
        $user = Auth::user();

        $preferences = $user->replicatedPreferences;

        if (!$preferences) {
            $preferences = new ReplicatedPreferences();
        }

        $preferences->user_id = $user->id;
        $preferences->displayed_name = $request->display_name;
        $preferences->business_name = $request->business_name;
        $preferences->phone = $request->phone;
        $preferences->email = $request->email;
        $preferences->show_email = $request->show_email ? 1 : 0;
        $preferences->show_phone = $request->show_phone ? 1 : 0;
        $preferences->show_name = $request->show_name ?: 1;

        $preferences->save();

        return response()->json([
            'error' => 0,
            'msg' => 'Preferences have been saved successfully.',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPreferences()
    {
        $request = request();

        /** @var User $user */
        $user = Auth::user();

        $preferences = $user->replicatedPreferences;

        $d['preferences'] = [
            'buiness_name' => $preferences && $preferences->displayed_name ? $preferences->displayed_name : '',
            'displayed_name' => $preferences && $preferences->business_name ? $preferences->business_name : $user->firstname . ' ' . $user->lastname,
            'name' => $user->firstname . ' ' . $user->lastname,
            'co_name' => $user->co_applicant_name,
            'co_display_name' => $preferences && $preferences->co_name ? $preferences->co_name : $user->co_applicant_name,
            'phone' => $preferences && $preferences->phone ? $preferences->phone : $user->phonenumber,
            'email' => $preferences && $preferences->email ? $preferences->email : $user->email,
            'show_email' => $preferences ? $preferences->show_email : 0,
            'show_phone' => $preferences ? $preferences->show_phone : 0,
            'show_name' => $preferences ? $preferences->show_name : 1,
            'disable_co_app' => !$user->co_applicant_name,
            'tab' => 'replicated',
        ];

        return response()->json([
            'error' => 0,
            'template' => view('affiliate.dashboard.replicated_preferences')->with($d)->render(),
        ]);
    }
}
