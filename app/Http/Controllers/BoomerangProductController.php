<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use DB;
use Validator;
use App\BoomerangTracker;
use App\BuumerangProduct;

class BoomerangProductController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function boomerangs($type = null, $id = null) {
        $d = array();

        if ($type == null)
            return $this->showBuumerangsInd($id);
        else if ($type == "buumerangs_ind") {
            return $this->showBuumerangsInd($id);
        } else if ($type == "buumerangs_grp") {
            return $this->showBuumerangsGroup($id);
        } else {
            return redirect('/');
        }
    }

    public function showBuumerangProductsGroup($id)
    {
    }

    /* two other functions to display the details for the selected buumerang
    * one for individual buumernang
    * one for group buumerang for each use
    *
    */

    public function showIndividualBuumerang($buumerang_products_id = null)
    {
    }

    public function showGroupBuumerang($buumerang_products_id = null)
    {
    }

}
