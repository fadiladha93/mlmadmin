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
class CountrySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin', ['only' => [
            'countrySettingList',
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
        return view('admin.settings.countries.list');
    }


    /**
     * @param Request $request
     * @return array
     */

    public function updateSettingsCountry(Request $request)
    {
        if (is_null($request->is_tier3) || is_null($request->is_open) || is_null($request->enable_2fa)) {
            return response()->json(['error' => 1, 'msg' => 'Please fill in all fields']);
        }

        DB::table('country')->where('id', $request->country_id)
                            ->update([
                                'is_tier3'         => $request->is_tier3,
                                'is_open'          => $request->is_open,
                                'enable_2fa' => $request->enable_2fa,
                                ]);

        return response()->json(['error' => 0, 'msg' => 'Country settings updated successfully']);
    }

    public function editCountry($id)
    {
        $data['country'] = DB::table('country')
            ->where('id', $id)
            ->first();

        return view('admin.settings.countries.edit')->with($data);
    }

    public function getCountriesDataTable()
    {
        $countries = DB::table('country as c');

        $data = Datatables::of($countries)
            ->addColumn('action', function ($country) {
                return '<a href="' . url('edit-settings-country') . '/' . $country->id . '" class="btn btn-sm m-btn--air btn-primary" title="Edit"> <i class="fa fa-edit"> </i></a>';
            })
        ->make(true);

        return $data;
    }
}
