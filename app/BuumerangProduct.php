<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BuumerangProduct extends Model {
    protected $table = "buumerang_products";
    public $timestamps = true;

    public static function addNewProduct($usersId, $boomerangTrackerId, $buumerangProduct) {

        $r = new BuumerangProduct();
        $r->users_id = $usersId;
        $r->boomerang_tracker_id = $boomerangTrackerId;
        $r->buumerang_product = $buumerangProduct;
        $r->buumerang_status = 0;
        $r->created_at = date('Y-m-d H:i:s');
        $r->save();

        return $buumerangProduct;
    }
}
