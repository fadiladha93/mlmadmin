<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
use App\UpdateHistory;

class UpdateHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function listOrdersHistory($type)
    {
        if ($type == 'orders') {
            $title = "Orders";
        } else if ($type == 'order-items') {
            $title = "Order Items";
        } else if ($type == 'products') {
            $title = "Products";
        } else if ($type == 'customers') {
            $title = "Customers";
        } else if ($type == 'users') {
            $title = "Users";
        } else if ($type == 'addresses') {
            $title = "Address";
        } else if ($type == 'boomeranginv') {
            $title = "Boomerang Inventory";
        } else if ($type == 'adjustments') {
            $title = "Adjustments";
        }
        $d = array();
        $d['title'] = $title;
        $d['type'] = $type;
        return view('admin.update_history.list')->with($d);
    }

    public function getOrdersHistoryDateTable($type)
    {
        if ($type == 'orders') {
            $t = UpdateHistory::TYPE_ORDER;
        } else if ($type == 'order-items') {
            $t = UpdateHistory::TYPE_ORDER_ITEM;
        } else if ($type == 'products') {
            $t = UpdateHistory::TYPE_PRODUCT;
        } else if ($type == 'customers') {
            $t = UpdateHistory::TYPE_CUSTOMER;
        } else if ($type == 'users') {
            $t = UpdateHistory::TYPE_USER;
        } else if ($type == 'addresses') {
            $t = UpdateHistory::TYPE_ADDRESS;
        } else if ($type == 'boomeranginv') {
            $t = UpdateHistory::TYPE_BOOMERANG_INV;
        } else if ($type == 'adjustments') {
            $t = UpdateHistory::TYPE_ADJUSTMENT;
        }
        $query = DB::table('vupdatehistory_users')
            ->select('type_id', 'before_update', 'after_update', 'created_at', 'updated_by', 'mode', 'name')
            ->where('type', $t);
        return DataTables::of($query)->toJson();
    }

    public function dlgUpdateHistory($type, $id)
    {
        $d = array();
        $d["type"] = $type;
        $d["id"] = $id;
        return view('admin.update_history.dlg_update_history')->with($d);
    }

    public function getUpdateHistoryDataTable()
    {
        $req = request();
        $query = DB::table('vupdatehistory_users')
            ->select('type_id', 'before_update', 'after_update', 'created_at', 'updated_by', 'mode', 'name')
            ->where('type', $req->type)
            ->where('type_id', $req->id);
        return DataTables::of($query)->toJson();
    }
}
