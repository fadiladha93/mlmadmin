<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use Validator;

class CustomerController extends Controller {

    public function __construct() {
        $this->middleware('auth.admin', ['except' => [
                'distCustomersList',
                'getDistCustomersDataTable',
                'saveRec'
        ]]);
        $this->middleware('auth.affiliate', ['except' => [
            'saveRec',
        ]]);
    }

    public function customerList() {
        return view('admin.user.list_customers');
    }

    public function frmEditCustomer($id) {
        $customer = DB::table('customers')
                ->where("id", $id)
                ->first();
        $d = array();
        $d['customer'] = $customer;
        return view('admin.user.frmEditCustomer')->with($d);
    }

    public function distCustomersList() {
        return view('affiliate.customer.dist_customers');
    }

    public function saveRec() {
        $req = request();
        $vali = $this->validateRec();
        if ($vali['valid'] == 0) {
            return response()->json(['status' => 'error', 'msg' => $vali['msg']]);
        }
        //
        $available = \App\BoomerangTracker::isAvailable($req->boomerang_code);
        if (!$available) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid code or expired']);
        }
        // create SOR for customer
        $userId = \App\BoomerangTracker::getUserId($req->boomerang_code);
        $password = \utill::getRandomString(10);
        $res = $this->createRecAtSOR($req->first_name, $req->last_name, $req->email, $req->phone, $password, $userId, $req->boomerang_code);
        //
        if ($res['status'] == "success") {
            \App\Customer::addNewRec($req, $userId, $password);
            \App\BoomerangTracker::markAsUsed($userId, $req->boomerang_code);
            // send email
            \MyMail::sendCustomerNewAccount($req->first_name, $req->last_name, $req->email, $password);
            return response()->json(['status' => 'success', 'email' => $req->email, 'password' => $password, 'url' => 'https://bookings.igo4less.com']);
        } else {
            return response()->json(['status' => 'error', 'msg' => $res['msg']]);
        }
    }

    public function createRecAtSOR($firstName, $lastName, $email, $phone, $password, $userId, $boomerangCode) {
        $referringUserSORID = \App\SaveOn::getSORUserId($userId);
        return \App\SaveOn::SORCreateUserWithToken_customers($firstName, $lastName, $email, $phone, $password, $referringUserSORID, $boomerangCode);
    }

    private function validateRec() {
        $req = request();
        $validator = Validator::make($req->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:customers,email',
                    'phone' => 'required',
                    'boomerang_code' => 'required',
                        ], [
                    'first_name.required' => 'First name is required',
                    'last_name.required' => 'Last name is required',
                    'email.required' => 'Email is required',
                    'email.email' => 'Invalid email format',
                    'email.unique' => 'Email already taken',
                    'phone.required' => 'Phone number is required',
                    'boomerang_code.required' => 'Code is required',
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

    public function getCustomersDataTable() {
        $query = DB::table('vcustomersusers');
        return DataTables::of($query)->toJson();
    }

    public function getDistCustomersDataTable() {
        $query = DB::table('customers')
                ->where('userid', Auth::user()->id);
        return DataTables::of($query)->toJson();
    }

    public function exportCustomersData($sort_col, $asc_desc, $q = null) {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Customers.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        if ($q == null) {
            $recs = DB::table('vcustomersusers')
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        } else {
            $recs = DB::table('vcustomersusers')
                    ->where(function($sq) use($q) {
                        $sq->where('name', 'ilike', "%" . $q . "%")
                        ->orWhere('email', 'ilike', "%" . $q . "%")
                        ->orWhere('mobile', 'ilike', "%" . $q . "%")
                        ->orWhere('boomerang_code', 'ilike', "%" . $q . "%")
                        ->orWhere('sor_default_password', 'ilike', "%" . $q . "%")
                        ->orWhere('distid', 'ilike', "%" . $q . "%")
                        ->orWhere('custid', 'ilike', "%" . $q . "%");
                    })
                    ->orderBy($sort_col, $asc_desc)
                    ->get();
        }


        $columns = array('Dist ID', 'Customer ID', 'Name', 'Email', 'Mobile', 'Code', 'SOR Default Password', 'Created Date');

        $callback = function() use ($recs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($recs as $rec) {
                fputcsv($file, array($rec->distid, $rec->custid, $rec->name, $rec->email, $rec->mobile, $rec->boomerang_code, $rec->sor_default_password, $rec->created_date));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function updateCustomer() {
        $req = request();
        $rec = \App\Customer::getById($req->id);
        $valid = $this->validateCustomerUpdateForm();
        if ($valid['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $valid['msg']]);
        } else {
            \App\Customer::updateCustomer($req->id, $req);
            \App\UpdateHistory::customerUpdate($req->id, $rec, $req);
            return response()->json(['error' => 0, 'msg' => 'Saved']);
        }
    }

    public function setCustomerId() {
        set_time_limit(0);
        \App\Customer::setCustomerId();
    }

    private function validateCustomerUpdateForm() {
        $req = request();
        $data = $req->all();
        $validator = Validator::make($data, [
                    'email' => 'required|email',
                    'name' => 'required',
                    'mobile' => 'required',
                        ], [
                    'email.required' => 'Customer Email is required',
                    'email.email' => 'Invalid Email Format',
                    'name.required' => 'Customer Name is required',
                    'mobile.required' => 'Customer Mobile is required',
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

}
