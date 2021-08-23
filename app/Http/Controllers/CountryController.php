<?php

namespace App\Http\Controllers;

use App\Models\Country;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;

/**
 * Class CountryController
 * @package App\Http\Controllers
 */
class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin', ['only' => [
            'countryList',
            'frmAdd',
            'createCountry',
            'editCountry',
            'deleteCountry',
            'payoutControl',
            'updatePayout',
            'editPayout',
            'updatePayoutDefault',
            'autoComplete'
        ]]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function countryList() {
        return view('admin.countries.countries');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function frmAdd() {
        return view('admin.countries.frmAdd');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function autoComplete(Request $request)
    {
        $searchQuery = $request->get('country');

//        $countries = DB::table('country')
//            ->select('country', 'countrycode', 'id')
//            ->where('country','LIKE','%'.$searchQuery.'%')
//            ->get();
        $countries = DB::select(DB::raw("select country.* from country left join payment_type_country on payment_type_country.country_id = country.id where country.country like '%" . $searchQuery . "%' AND payment_type_country.payment_type is null"));
        $data=array();
        foreach ($countries as $country) {
            $data[]=array('value'=>$country->country,'id'=>$country->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }

    public function createCountry()
    {
        $requestData = request()->all();
        $validator = Validator::make($requestData, [
            'country' => 'required',
            'country_id' => 'required|unique:payment_type_country,country_id',
            'payment_method_type' => 'required',
        ], [
            'country.required' => 'Country cannot be empty',
            'country_id.unique' => 'Country already exists',
            'country_id.required' => 'Country code cannot be empty',
            'payment_method_type.required' => 'Payment type cannot be empty',
        ]);
        $valid = 0;
        $msg = "";
        if ($validator->fails()) {
            $messages = $validator->messages();
            foreach ($messages->all() as $m) {
                $msg .= "<div> - " . $m . "</div>";
            }
            return response()->json(['error' => 1, 'msg' => $msg]);
        }
        if ($requestData['payment_method_type'] == 't1') {
            $requestData['payment_method_type'] = 'NMI - T1';
        } else {
            $requestData['payment_method_type'] = 'Trust my travel';
        }
        \App\Country::addPaymentTypeCountry($requestData['country_id'], $requestData['payment_method_type']);
        return response()->json(['error' => 0, 'url' => url('/countries')]);
    }

    public function updatePaymentMethodForCountry(Request $request)
    {
        $payment_type = 'Trust my travel';
        if (empty($request->payment_method_type)) {
            return response()->json(['error' => 1, 'msg' => 'Payment method type cannot be empty']);
        } else if ($request->payment_method_type == 't1') {
            $payment_type = 'NMI - T1';
        }
        DB::table('payment_type_country')->where('country_id', $request->country_id)->update(['payment_type' => $payment_type]);
        return response()->json(['error' => 0, 'msg' => 'Payment method type was set successfully']);
    }

    public function editCountry($id)
    {
        $data['country'] = DB::table('payment_type_country')
            ->join('country', 'payment_type_country.country_id', '=', 'country.id')
            ->where('payment_type_country.id', $id)
            ->first();
        return view('admin.countries.edit-payment-type')->with($data);
    }

    public function getCountriesDataTable()
    {
        $countries = DB::table('country as c')
            ->select('*', 'ptc.id as paymid')
            ->join('payment_type_country as ptc', 'c.id', '=', 'ptc.country_id');

        $data = Datatables::of($countries)
            ->addColumn('action', function ($country) {
                return '<a href="' . url('edit-country') . '/' . $country->paymid . '" class="btn btn-sm m-btn--air btn-primary" title="Edit"> <i class="fa fa-edit"> </i></a> <a href="' . url('delete-country') . '/' . $country->id . '" class="btn btn-sm m-btn--air btn-danger" title="Delete"> <i class="fa fa-trash"> </i></a>';
            })
            ->make(true);

        return $data;
    }

    public function deleteCountry($countryId){

        \App\Country::deletePaymentTypeCountry($countryId);

        return redirect('/countries');
    }

    public function payoutControl()
    {
        $countries = DB::table('country')
            ->get();
        foreach ($countries as $country) {
            $rec = \App\PayOutControl::where('country_id', $country->id)->count();
            if (!$rec) {
                \App\PayOutControl::insert([
                    'country_id' => $country->id
                ]);
            }
        }
        return view('admin.payout.index');
    }

    public function dtPayoutControl()
    {
        $countries = DB::table('country as c')
            ->select('*', 'c.id as cid')
            ->leftJoin('payout_type_country as ptc', 'c.id', '=', 'ptc.country_id');

        $data = Datatables::of($countries)
            ->addColumn('action', function ($country) {
                return '<a href="#" title="Edit" data-id="' . $country->cid . '" class="btn btn-primary btn-sm m-btn--air edit-payout" ><i  class="fa fa-edit"></i></a>';
            })
            ->make(true);

        return $data;
    }

    public function editPayout($id)
    {
        $data['pay_type'] = DB::table('payout_type_country')->where('country_id', $id)->first();
        $data['id'] = $id;
        $v = (string)view('admin.payout.dlg_edit')->with($data);
        return response()->json(['error' => 0, 'v' => $v]);
    }

    public function updatePayout(Request $request)
    {
        $rec = \App\PayOutControl::where('country_id', $request->country_id)
            ->first();
        if (empty($rec)) {
            \App\PayOutControl::insert([
                'country_id' => $request->country_id,
                'type' => $request->payout_method,
            ]);
        } else {
            \App\PayOutControl::where('id', $rec->id)
                ->update(['type' => $request->payout_method]);
        }
        return response()->json(['error' => 0, 'msg' => 'Payout control updated successfully']);
    }

    public function updatePayoutDefault(Request $request)
    {
        DB::statement("UPDATE  payout_type_country SET type='" . $request->payout_method . "'");
        return response()->json(['error' => 0, 'msg' => 'Payout control updated successfully']);
    }
}
