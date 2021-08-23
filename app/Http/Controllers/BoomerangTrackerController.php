<?php

namespace App\Http\Controllers;

use App\Core\BoomerangManager;
use Auth;
use DataTables;
use DB;
use Validator;
use App\BoomerangTracker;

class BoomerangTrackerController extends Controller {
    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'indexInd',
                'indexGroup',
                'getInternDataTableInd',
                'getInternDataTableGroup',
                'generateInd',
                'generateGroup',
                'validateCode',
                'sendSMS',
                'sendMail'
        ]]);
        $this->middleware('auth', ['except' => [
            'validateCode',
        ]]);
//        $this->middleware('auth', ['except' => [
//                'validateCode',
//        ]]);
    }

    public function boomerangList($type) {
        $d = array();
        if ($type == "leads_ind") {
            return view('admin.lead.list_leads_ind');
        } else if ($type == "leads_grp") {
            return view('admin.lead.list_leads_grp');
        } else {
            abort(404);
        }
    }

    public function indexInd() {
        $d = array();
        return view('affiliate.boomerangs.index_ind')->with($d);
    }

    public function indexGroup() {
        $d = array();
        return view('affiliate.boomerangs.index_group')->with($d);
    }

    public function getInternDataTableInd() {
        $query = DB::table('boomerang_tracker')
                ->where('mode', \App\BoomerangTracker::MODE_INDIVIDUAL)
                ->where('userid', Auth::user()->id);
        return DataTables::of($query)->toJson();
    }

    public function getInternDataTableGroup() {
        $query = DB::table('boomerang_tracker')
                ->where('mode', \App\BoomerangTracker::MODE_GROUP)
                ->where('userid', Auth::user()->id);
        return DataTables::of($query)->toJson();
    }

    public function generateInd() {
        $req = request();
        $vali = $this->validateInd();

        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }

        if ($req->buumerang_product == 'igo') {
            $agreed = \App\ProductTermsAgreement::getByUserId(Auth::user()->id, 'sor');

            if (empty($agreed)) {
                $v = (string)view('affiliate.agreement.igo');
                return response()->json([
                    'error' => '1',
                    'msg' => 'To use your büümerangs you must first click on the iGO logo ABOVE
                     and activate your iGO account.'
                ]);
            }
            if (!Auth::user()->current_product_id) {
                return response()->json(['error' => '1', 'msg' => 'Get your enrollment pack now!']);
            }
        }

        try {
            // create the boomerang
            $userType = BoomerangTracker::$matchTextToTypes[$req->buumerang_product];
            $boomerangManager = new BoomerangManager($userType);
            $boomerangManager->manager->createBoomerang(
                Auth::user()->id,
                $req->firstname,
                $req->lastname,
                $req->email,
                $req->mobile,
                $req->exp_date,
                $req->buumerang_product
            );
        } catch (\Exception $ex) {
            return response()->json(['error' => 1, 'msg' => $ex->getMessage()]);
        }

        return response()->json([
            'error' => 0,
            'code' => $boomerangManager->manager->getBoomerangTracker()->boomerang_code,
            'available' => $boomerangManager->manager->getBoomerangInventory()->available_tot,
            'pending' => $boomerangManager->manager->getBoomerangInventory()->pending_tot
        ]);
    }

    public function generateGroup() {
        $req = request();
        $vali = $this->validateGroup();
        if ($vali['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $vali['msg']]);
        }
        // check if there are any available
        $boomerangInv = \App\BoomerangInv::getInventory(Auth::user()->id);
        if (empty($boomerangInv) || $boomerangInv->available_tot == 0)
            return response()->json(['error' => 1, 'msg' => 'There are no available Boomerangs']);
        if ($req->no_of_uses > $boomerangInv->available_tot)
            return response()->json(['error' => 1, 'msg' => 'Only ' . $boomerangInv->available_tot . ' Boomerangs are available']);
        //
        $result = \App\BoomerangTracker::addNewGroup(Auth::user()->id, $req->campaign_name, $req->no_of_uses, $req->exp_date);
        $code = $result['code'];
        $boomerangTrackerId = $result['insert_id'];

        $buumerangProduct = \App\BuumerangProduct::addNewProduct(Auth::user()->id, $boomerangTrackerId, $req->buumerang_product);
        //
        $newInv = \App\BoomerangInv::updateInventory(Auth::user()->id, $req->no_of_uses, $req->buumerang_product);
        //
        return response()->json(['error' => 0, 'code' => $code, 'available' => $newInv->available_tot, 'pending' => $newInv->pending_tot]);
    }

    public function validateCode($code) {
        if (\utill::isNullOrEmpty($code)) {
            return response()->json(['status' => 'error', 'msg' => 'Code missing']);
        }
        $available = \App\BoomerangTracker::isAvailable($code);
        if ($available) {
            $expDate = \App\BoomerangTracker::getValidCodeExpiryDate($code);
            if ($expDate == null)
                return response()->json(['status' => 'error', 'msg' => 'Invalid code or expired']);
            else
                return response()->json(['status' => 'success', 'exp_date' => $expDate]);
        } else
            return response()->json(['status' => 'error', 'msg' => 'Invalid code or expired']);
    }

    private function validateGroup() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'campaign_name' => 'required',
                    'exp_date' => 'required',
                    'no_of_uses' => 'required|numeric',
                    'buumerang_product' => 'required',
                        ], [
                    'campaign_name.required' => 'Campaign name is required',
                    'exp_date.required' => 'Expiration date is required',
                    'no_of_uses.required' => 'Number of uses is required',
                    'no_of_uses.numeric' => 'Number of uses must be numeric',
                    'buumerang_product.required' => 'Type of Buumerang is required',
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

    private function validateInd() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'email' => 'required',
                    'mobile' => 'required',
                    'exp_date' => 'required',
                    'buumerang_product' => 'required',
                        ], [
                    'firstname.required' => 'First name is required',
                    'lastname.required' => 'Last name is required',
                    'email.required' => 'Email Address is required',
                    'mobile.required' => 'Mobile phone is required',
                    'exp_date.required' => 'Expiration date is required',
                    'buumerang_product.required' => 'Buumerang Type is required',
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

    public function getLeadIndividualDataTable() {
        $query = DB::table('vboomerangtrackerusers')
                ->where('mode', \App\BoomerangTracker::MODE_INDIVIDUAL);
        return DataTables::of($query)->toJson();
    }

    public function exportLeadsIndividualData($sort_col, $asc_desc, $q = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Individual Boomerangs.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            $recs = DB::table('vboomerangtrackerusers')
                    ->where('mode', \App\BoomerangTracker::MODE_INDIVIDUAL)
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        } else {
            $recs = DB::table('vboomerangtrackerusers')
                    ->where('mode', \App\BoomerangTracker::MODE_INDIVIDUAL)
                    ->where(function($sq) use($q) {
                        $sq->where('lead_firstname', 'ilike', "%" . $q . "%")
                        ->orWhere('lead_lastname', 'ilike', "%" . $q . "%")
                        ->orWhere('distid', 'ilike', "%" . $q . "%")
                        ->orWhere('lead_email', 'ilike', "%" . $q . "%")
                        ->orWhere('lead_mobile', 'ilike', "%" . $q . "%")
                        ->orWhere('boomerang_code', 'ilike', "%" . $q . "%")
                        ->orWhere('date_created', 'ilike', "%" . $q . "%")
                        ->orWhere('exp_dt', 'ilike', "%" . $q . "%")
                        ->orWhere('date_created', 'ilike', "%" . $q . "%")
                        ->orWhere('exp_dt', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        }


        $columns = array('First Name', 'Last Name', 'Email', 'Mobile', 'Code', 'Date Created', 'Expiration Date', 'Used');

        $callback = function() use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->distid, $rec->lead_firstname, $rec->lead_lastname, $rec->lead_email, $rec->lead_mobile, $rec->boomerang_code, $rec->date_created, $rec->exp_dt));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function getLeadGroupDataTable() {
        $query = DB::table('vboomerangtrackerusers')
                ->where('mode', \App\BoomerangTracker::MODE_GROUP);
        return DataTables::of($query)->toJson();
    }

    public function exportLeadsGroupData($sort_col, $asc_desc, $q = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Group Boomerangs.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            $recs = DB::table('vboomerangtrackerusers')
                    ->where('mode', \App\BoomerangTracker::MODE_GROUP)
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        } else {
            $recs = DB::table('vboomerangtrackerusers')
                    ->where('mode', \App\BoomerangTracker::MODE_GROUP)
                    ->where(function($sq) use($q) {
                        $sq->where('group_campaign', 'ilike', "%" . $q . "%")
                        ->orWhere('group_no_of_uses', 'ilike', "%" . $q . "%")
                        ->orWhere('distid', 'ilike', "%" . $q . "%")
                        ->orWhere('group_available', 'ilike', "%" . $q . "%")
                        ->orWhere('boomerang_code', 'ilike', "%" . $q . "%")
                        ->orWhere('date_created', 'ilike', "%" . $q . "%")
                        ->orWhere('exp_dt', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        }


        $columns = array('Dist ID', 'Campaign Name', 'Number of uses', 'Available', 'Boomerang Code', 'Date Created', 'Expiration date');

        $callback = function() use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($recs as $rec) {
                fputcsv($file, array($rec->distid, $rec->group_campaign, $rec->group_no_of_uses, $rec->group_available, $rec->boomerang_code, $rec->date_created, $rec->exp_dt));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function getBoomerangsSentChart() {
        $req = request();
        $query = DB::table('vboomerangcountbyday');
        if (isset($req->year)) {
            $query->whereYear("date_created", $req->year);
            $query->whereMonth("date_created", $req->month);
        } else {
            $query->whereYear("date_created", date("Y"));
            $query->whereMonth("date_created", date("m"));
        }
        $query->orderBy('date_created', "asc");
        $recs = $query->get();
        return response()->json(['error' => '0', 'data' => $recs]);
    }

    public function sendSMS() {
        $req = request();
        $mobile = $req->m;
        $code = $req->c;
        $customerFirstName = $req->firstname;
        $customerLastName = $req->lastname;
        $product = $req->buumerang_product;

        if (\utill::isNullOrEmpty($code)) {
            return response()->json(['error' => 1, 'msg' => 'Please generate the code']);
        }
        if (\utill::isNullOrEmpty($mobile)) {
            return response()->json(['error' => 1, 'msg' => 'Mobile number is required']);
        }
        //
        $saved = BoomerangTracker::hasSentTextOrEmail($code);

        if ($saved == 1) {
            return response()->json(['error' => 1, 'msg' => 'SMS has already been sent for this code']);
        }

        switch($product) {
            case 'vibe-rider':
                return $this->sendVibeRiderInvitationSMS($customerFirstName, $customerLastName, $code, $mobile);
                break;
            case 'vibe-driver':
                return $this->sendVibeDriverInvitationSMS($customerFirstName, $customerLastName, $code, $mobile);
                break;
            case 'igo':
                return $this->sendBoomerangInvitationSMS($customerFirstName, $customerLastName, $code, $mobile);
        }
    }

    private function sendVibeRiderInvitationSMS($customer_firstname, $customer_lastname, $code, $mobile)
    {
        $content = \MyMail::VIBE_RIDER_TEMPLATE;
        $content = str_replace("<dist_first_name>", Auth::user()->firstname, $content);
        $content = str_replace("<dist_last_name>", Auth::user()->lastname, $content);
        $content = str_replace("<customer_first_name>", $customer_firstname, $content);
        $content = str_replace("<customer_last_name>", $customer_lastname, $content);
        $content = str_replace("<boomerang_code>", $code, $content);
        $mobile = "+" . $mobile;
        $message = $content;
        $response = \App\Twilio::sendSMS($mobile, $message);

        if ($response['status'] == 'success') {
            \App\BoomerangTracker::setMobileNumber(Auth::user()->id, $code, $mobile);
            BoomerangTracker::setTextOrEmailSent($code);
            return response()->json(['error' => 0, 'msg' => 'SMS successfully sent']);
        } else {
            //return response()->json(['error' => 1, 'msg' => $response['msg']]);
            return response()->json(['error' => 1, 'msg' => "Invalid mobile number. Please enter with country code"]);
        }
    }

    private function sendVibeDriverInvitationSMS($customer_firstname, $customer_lastname, $code, $mobile)
    {
        $content = \MyMail::VIBE_DRIVER_TEMPLATE;
        $content = str_replace("<dist_first_name>", Auth::user()->firstname, $content);
        $content = str_replace("<dist_last_name>", Auth::user()->lastname, $content);
        $content = str_replace("<customer_first_name>", $customer_firstname, $content);
        $content = str_replace("<customer_last_name>", $customer_lastname, $content);
        $content = str_replace("<boomerang_code>", $code, $content);
        $mobile = "+" . $mobile;
        $message = $content;
        $response = \App\Twilio::sendSMS($mobile, $message);

        if ($response['status'] == 'success') {
            \App\BoomerangTracker::setMobileNumber(Auth::user()->id, $code, $mobile);
            BoomerangTracker::setTextOrEmailSent($code);
            return response()->json(['error' => 0, 'msg' => 'SMS successfully sent']);
        } else {
            //return response()->json(['error' => 1, 'msg' => $response['msg']]);
            return response()->json(['error' => 1, 'msg' => "Invalid mobile number. Please enter with country code"]);
        }
    }

    private function sendBoomerangInvitationSMS($customer_firstname, $customer_lastname, $code, $mobile)
    {
        $template = \App\MailTemplate::getRec(\App\MailTemplate::TYPE_BOOMERANG_INVITATION_SMS);
        if ($template->is_active == 1) {
            $content = $template->content;
            //
            $content = str_replace("<dist_first_name>", Auth::user()->firstname, $content);
            $content = str_replace("<dist_last_name>", Auth::user()->lastname, $content);
            $content = str_replace("<customer_first_name>", $customer_firstname, $content);
            $content = str_replace("<customer_last_name>", $customer_lastname, $content);
            $content = str_replace("<boomerang_code>", $code, $content);
            //
            $mobile = "+" . $mobile;
            $message = $content;
            $response = \App\Twilio::sendSMS($mobile, $message);
            if ($response['status'] == 'success') {
                \App\BoomerangTracker::setMobileNumber(Auth::user()->id, $code, $mobile);
                BoomerangTracker::setTextOrEmailSent($code);
                return response()->json(['error' => 0, 'msg' => 'SMS successfully sent']);
            } else {
                //return response()->json(['error' => 1, 'msg' => $response['msg']]);
                return response()->json(['error' => 1, 'msg' => "Invalid mobile number. Please enter with country code"]);
            }
        } else {
            return response()->json(['error' => 1, 'msg' => 'SMS service is not configured. Please contact us']);
        }
    }

    public function sendMail() {
        $req = request();
        $email = trim($req->e);
        $code = $req->c;
        $customer_firstname = $req->firstname;
        $customer_lastname = $req->lastname;
        $product = $req->buumerang_product;

        if (\utill::isNullOrEmpty($code)) {
            return response()->json(['error' => 1, 'msg' => 'Please generate the code']);
        }
        if (\utill::isNullOrEmpty($email)) {
            return response()->json(['error' => 1, 'msg' => 'Email is required']);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error' => 1, 'msg' => 'Invalid email format']);
        }
        //
        $saved = \App\BoomerangTracker::hasSentTextOrEmail($code);

        if ($saved == 1) {
            return response()->json(['error' => 1, 'msg' => 'An email has already been sent for this code']);
        }

        $sent = false;

        switch ($product) {
            case 'igo':
                $sent = \MyMail::sendBoomerangInvitation($email, $customer_firstname, $customer_lastname, $code);
                break;
            case 'vibe-rider':
                $sent = \MyMail::sendVibeRiderInvitation($email, $customer_firstname, $customer_lastname, $code);
                break;
            case 'vibe-driver':
                $sent = \MyMail::sendVibeDriverInvitation($email, $customer_firstname, $customer_lastname, $code);
                break;
        }

        if ($sent) {
            \App\BoomerangTracker::setEmail(Auth::user()->id, $code, $email);
            BoomerangTracker::setTextOrEmailSent($code);
            return response()->json(['error' => 0, 'msg' => 'Email successfully sent']);
        } else {
            return response()->json(['error' => 1, 'msg' => 'Email service is not configured. Please contact us']);
        }
    }

}
