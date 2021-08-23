<?php

namespace App\Http\Controllers;

use App\Models\Country;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;

/**
 * Class MerchLimitsController
 * @package App\Http\Controllers
 */
class MerchLimitsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin', ['only' => [
            'merchantList',
            'editLimit'
        ]]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function merchantList() {
        return view('admin.merchants.merchants');
    }

    public function editMerchant($id)
    {
        $data['merchant'] = DB::table('payment_method_type')
            ->where('id', $id)
            ->first();
        return view('admin.merchants.edit-merchant')->with($data);
    }


    public function getMerchantDataTable()
    {

        $merchants = DB::table('payment_method_type')
            ->select('pay_method_name', 'type', 'limit_coach', 'limit_business_class', 'limit_first_class','id')
            ->where(['statuscode' => 1 ])
            ->orderBy('id', 'ASC')
            ->get();

        $data = Datatables::of($merchants)
            ->addColumn('action', function ($merchant) {
                // return '<a href="' . url('edit-merchant') . '/' . $merchant->id . '" class="btn btn-sm m-btn--air btn-primary" title="Edit"> <i class="fa fa-edit"> </i></a> <a href="' . url('delete-merchant') . '/' . $merchant->id . '" class="btn btn-sm m-btn--air btn-danger" title="Delete"> <i class="fa fa-trash"> </i></a>';
                // Deleting merchants is probably too dangerous
                return '<a href="' . url('edit-merchant') . '/' . $merchant->id . '" class="btn btn-sm m-btn--air btn-primary" title="Edit"> <i class="fa fa-edit"> </i></a>';
            })
            ->make(true);

        return $data;
    }

    public function updateMerchant(Request $request)
    {
        try {
            $update = DB::table('payment_method_type')->where('id', $request->id)->update(['type' => $request->payment_method_type, 'limit_coach' => $request->payment_method_limit_coach, 'limit_business_class' => $request->payment_method_limit_business_class, 'limit_first_class' => $request->payment_method_limit_first_class]);
            return response()->json(['error' => 0, 'url' => url('/merchants'), 'msg' => 'Payment method was edited successfully']);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['error' => 1, 'msg' => 'Payment method was not edited, please check values for non-numeric values.']);
        }
    }


}
