<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $table = "products";
    public $timestamps = false;

    // enrollment packages
    const ID_NCREASE_ISBO  = 1; // OLD ID_STANDBY_CLASS
    const ID_BASIC_PACK = 2; // OLD ID_COACH_CLASS
    const ID_VISIONARY_PACK = 3; // OLD ID_BUSINESS_CLASS
    const ID_FIRST_CLASS = 4;
    const ID_EB_FIRST_CLASS = 13;
    const ID_Traverus_Grandfathering = 14;
    const ID_PREMIUM_FIRST_CLASS = 16;
    // upgrade packages
    const ID_UPG_STAND_FIRST = 7;
    const ID_UPG_STAND_BUSINESS = 6;
    const ID_UPG_STAND_COACH = 5;
    const ID_UPG_STAND_PREMIUM = 17;
    //
    const ID_UPG_COACH_BUSINESS = 8;
    const ID_UPG_COACH_FIRST = 9;
    const ID_UPG_COACH_PREMIUM = 18;
    //
    const ID_UPG_BUSINESS_FIRST = 10;
    const ID_UPG_BUSINESS_PREMIUM = 19;
    //
    const ID_UPG_FIRST_PREMIUM = 20;
    //buy ibuumerang
    const ID_IBUUMERANG_25 = 15;
    const BOOMERANG = 4;
    const ENROLLMENT = 1;
    const UPGRADES = 2;
    const MEMBERSHIP = 3;
    //membership
    const MONTHLY_MEMBERSHIP = 11;
    const MONTHLY_MEMBERSHIP_STAND_BY_USER = 33;
    const TEIR3_COACHSUBSCRIPTION = 26;
    const ID_MONTHLY_MEMBERSHIP = 12;
    const ID_UPG_STANDBY_TO_PREMIUM_FC = 17;
    const ID_UPG_BUSINESS_TO_PREMIUM_FC = 19;
    const ID_UPG_COACH_TO_PREMIUM_FC = 18;
    const ID_MEMBERSHIP = 11;
    const ID_TIER3_COACH = 26;
    const ID_PRE_PAID_CODE = 25;
    const ID_FIRST_TO_PREMIUM = 20;
    const ID_REACTIVATION_PRODUCT = 50;
    const ID_VIBE_IMPORT_USER = 51;
    const ID_VIBE_OVERDRIVE_USER = 52;
    //donations
    const ID_TICKET = 38;
    const ID_FOUNDATION = 39;
    const TICKET_PURCHASE_DISCOUNT_PRICE = '49.98';
    const ID_TRAVEL_SAVING_BONUS = 41;
    //Events tickets
    const ID_EVENTS_TICKET_DREAM_WEEKEND = [46, 47, 48];
    const ID_EVENTS_TICKET_XCCELERATE = [49];
    // Xccelerate products
    const ID_PHOTOBOOK_53 = 53;
    const IGO_SALES_TOOLS_ENG_54 = 54;
    const IGO_SALES_TOOLS_SPAN_55 = 55;
    const IGO_VIDEO_TRAINING_56 = 56;

    const ID_VIBE_COMMISSION = 87;

    protected $fillable = [ 'id',
        'productname',
        'producttype',
        'productdesc',
        'is_taxable',
        'tax_code',
        'shipping_enabled',
        'allow_multiple_on_cart',
        'shipping_price',
        'is_visible',
        'visible_days_from_enrollment',
        'long_description',
        'price',
        'itemcode',
        'bv',
        'cv',
        'qv',
        'qc',
        'ac',
        'sku',
        'num_boomerangs',
        'sponsor_boomerangs'];

    public static function getSubscriptionProducts()
    {
        return DB::table('products')
            ->select('*')
            ->whereIn('id',[
                \App\Product::MONTHLY_MEMBERSHIP,
                \App\Product::ID_MONTHLY_MEMBERSHIP,
                \App\Product::MONTHLY_MEMBERSHIP_STAND_BY_USER,
                \App\Product::TEIR3_COACHSUBSCRIPTION,
            ])
            ->orderBy('productname', 'asc')
            ->get();
    }

    public static function getAll() {
        return DB::table('products')
                        ->select('id', 'productname')
                        ->orderBy('producttype', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();
    }

    public static function getByTypeId($typeId, $orderBy = "price", $ascDesc = "desc") {
        return DB::table('products')
                        ->where('producttype', $typeId)
                        ->where('is_enabled', 1)
                        ->orderBy($orderBy, $ascDesc)
                        ->get();
    }

    public static function getProduct($productId) {
        return DB::table('products')
                        ->where('id', $productId)
                        ->first();
    }

    // public static function getProductsFromProductIdArray($productIdArray) {

    //     $query = DB::table('products')
    //         ->whereIn('id', $productIdArray)
    //         ->where('is_enabled', 1)
    //         ->orderBy('id', 'asc')
    //         ->get();

    //     return $query;
    // }

    public static function getProductsEnrollment(){
        return self::where('is_enabled', 1)
            ->whereHas('productTypes', function($query){
                $query->where('typedesc', 'Enrollment');
            })
            ->orderBy('id', 'asc')
            ->get();
    }

    public function productTypes() {
        return $this->hasOne('App\ProductType', 'id', 'producttype');
    }

    public static function getProductName($productId) {
        if ($productId == 0)
            return "-";
        else if ($productId == self::ID_NCREASE_ISBO)
            return "Ncrease ISBO";
        else if ($productId == self::ID_BASIC_PACK)
            return "Basic Pack";
        else if ($productId == self::ID_VISIONARY_PACK)
            return "Visionary Pack";
    }

    public static function getById($id) {
        return DB::table('products')
                        ->where('id', $id)
                        ->first();
    }
    public static function getWithImageTerritoriesEnrollementsById($id) {


        $imageName = DB::table('product_images')->where('product_id',$id)->first();
        $enrollments = DB::table('product_enrollment_classes')->where('product_id',$id)->get()->pluck('enrollment_product_id');
        $territories = DB::table('product_countries')->where('product_id',$id)->get()->pluck("country_id");
        $product = DB::table('products')
                        ->where('products.id', $id)
                        ->first();
        $product -> territories = $territories;
        $product -> enrollments = $enrollments;
        $product -> image = $imageName->image_path ?? '';
        return $product;


    }

    public static function deleteProductImage($id){
        $productImage = DB::table('product_images')->where('product_id',$id)->delete();
    }

    public static function updateProduct($productId, $req) {
        $rec = Product::find($productId);

        // dd($req, $rec);
        $rec->is_taxable = $req->is_taxable;
        $rec->tax_code = $req->tax_code;
        $rec->shipping_enabled = $req->shipping_enabled;
        $rec->allow_quantity_change = $req->allow_quantity_change;
        $rec->allow_multiple_on_cart = $req->allow_multiple_on_cart;
        $rec->shipping_price = $req->shipping_price;
        $rec->is_visible = $req->is_visible;
        $rec->visible_days_from_enrollment = $req->visible_days_from_enrollment;
        $rec->long_description = $req->long_description;
        $rec->productname = $req->productname;
        $rec->producttype = $req->producttype;
        $rec->is_enabled = $req->is_enabled;
        $rec->productdesc = $req->productdesc;
        $rec->price = $req->price;
        $rec->itemcode = $req->itemcode;
        $rec->bv = $req->bv;
        $rec->cv = $req->cv;
        $rec->qv = $req->qv;
        $rec->qc = $req->qc;
        $rec->ac = $req->ac;
        $rec->num_boomerangs = $req->num_boomerangs;
        $rec->sponsor_boomerangs = $req->sponsor_boomerangs;
        $rec->save();

        if($req->imageName){
            DB::table('product_images')->where('product_id', $rec->id)->delete();
            $maxImgID = DB::table('product_images')
                    ->max('id');
            DB::table('product_images')->insert(['id' => $maxImgID + 1, 'product_id' => $rec->id, 'image_path' => $req->imageName]);
        }

        $rec->territories()->sync($req->territories);

        // DB::table('product_countries')->where('product_id',$rec->id)->delete();
        // foreach($req->territories as $territory){
        //     $maxProdContID = DB::table('product_countries')
        //     ->max('id');

        //     DB::table('product_countries')->insert(
        //         ['id' => $maxProdContID + 1, 'product_id' => $rec->id, 'country_id' => $territory]
        //     );
        // } 
        $rec->productEnrollmentClasses()->sync($req->visible_by_enrollment_class);
        // DB::table('product_enrollment_classes')->where('product_id',$rec->id)->delete();
        // foreach($req->visible_by_enrollment_class as $enrollment_class){
        //     $maxProdContID = DB::table('product_enrollment_classes')
        //     ->max('id');

        //     DB::table('product_enrollment_classes')->insert(
        //         ['id' => $maxProdContID + 1, 'product_id' => $rec->id, 'enrollment_product_id' => $enrollment_class]
        //     );
        // }

        return $rec->id;
    }

    public static function getEnrollmentPacks(){
        $packs = self::where('producttype', 1)->pluck('id', 'productname');
        $enrollmentPacks = [];
        $enrollmentPacks["None"] = 0;

        foreach($packs as $pack => $value){
            $enrollmentPacks[$pack] = $value;
        }
        return $enrollmentPacks; 
    }

    public static function addProduct($req) {
        $maxID = DB::table('products')
                ->max('id');
        $rec = new Product;
        $rec->id = $maxID + 1;
        $rec->productname = $req->productname;
        $rec->producttype = $req->producttype;
        $rec->productdesc = $req->productdesc;
        $rec->is_taxable = $req->is_taxable;
        $rec->tax_code = $req->tax_code;
        $rec->shipping_enabled = $req->shipping_enabled;
        $rec->allow_quantity_change = $req->allow_quantity_change;
        $rec->allow_multiple_on_cart = $req->allow_multiple_on_cart;
        $rec->shipping_price = $req->shipping_price;
        $rec->is_visible = $req->is_visible;
        $rec->visible_days_from_enrollment = $req->visible_days_from_enrollment;
        $rec->long_description = $req->long_description;
        $rec->price = $req->price;
        $rec->itemcode = $req->itemcode;
        $rec->bv = $req->bv;
        $rec->cv = $req->cv;
        $rec->qv = $req->qv;
        $rec->qc = $req->qc;
        $rec->ac = $req->ac;
        $rec->sku = $req->sku;
        $rec->num_boomerangs = $req->num_boomerangs;
        $rec->sponsor_boomerangs = $req->sponsor_boomerangs;

        if (isset($req->isautoship)){
            $rec->isautoship = $req->isautoship;
        }

        $rec->save();

        if($req->imageName){
            $maxImgID = DB::table('product_images')
                    ->max('id');
            DB::table('product_images')->insert(['id' => $maxImgID + 1, 'product_id' => $rec->id, 'image_path' => $req->imageName]);
        }

        $rec->territories()->attach($req->territories);

        // foreach($req->territories as $territory){

        //     $maxProdContID = DB::table('product_countries')
        //     ->max('id');

        //     DB::table('product_countries')->insert(
        //         ['id' => $maxProdContID + 1, 'product_id' => $rec->id, 'country_id' => $territory]
        //     );
        // }
        $rec->productEnrollmentClasses()->attach($req->visible_by_enrollment_class);
   
        // foreach($req->visible_by_enrollment_class as $enrollment_class){

        //     $maxProdContID = DB::table('product_enrollment_classes')
        //     ->max('id');

        //     DB::table('product_enrollment_classes')->insert(
        //         ['id' => $maxProdContID + 1, 'product_id' => $rec->id, 'enrollment_product_id' => $enrollment_class]
        //     );
        // }
        return $rec->id;
    }

    public static function getProductNameForInvoice($orderItem) {
        $product = DB::table('products')
                ->where('id', $orderItem->productid)
                ->first();

        $productName = $product->productname;

        if (!empty($orderItem->discount_voucher_id)) {
            $discountCode = DB::table('discount_coupon')
                    ->where('id', $orderItem->discount_voucher_id)
                    ->first();

            if ($discountCode) {
                $productName = $productName . ' (' . $discountCode->code . ')';
            }
        }

        return $productName;
    }

    public function territories()
    {
        return $this->belongsToMany('App\Country', 'product_countries', 'product_id', 'country_id');
    }

    public function productEnrollmentClasses()
    {
        return $this->belongsToMany('App\ProductEncollmentClasses', 'product_enrollment_classes', 'product_id', 'enrollment_product_id');
    }
}
