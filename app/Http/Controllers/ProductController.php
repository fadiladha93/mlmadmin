<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
use Validator;
use App\Product;
use App\ProductType;
use App\Country;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    //const ENROLLMENT_PRODUCTS = ['1', '2', '3', '4', '13', '14', '52']; 

    public function __construct()
    {
        $this->middleware('auth.admin');
        //
        $this->middleware(function ($request, $next) {
            if (!(\App\User::admin_super_admin() || \App\User::admin_super_exec() || \App\User::admin_cs_manager())) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                } else {
                    return redirect('/');
                }
            }
            return $next($request);
        });
    }

    public function adminProduct($type)
    {
        if ($type == "products") {
            return view('admin.products.products');
        } else {
            abort(404);
        }
    }

    public function getProductsDataTable()
    {
        $query = DB::table('vproductsproducttype');
        return DataTables::of($query)->toJson();
    }

    public function productDetail($id)
    {
        $product = Product::getWithImageTerritoriesEnrollementsById($id);
        $producttype = ProductType::select('id', 'typedesc', 'statuscode')->get();
        $territories = Country::getAll();
        $enrollment_products = Product::getProductsEnrollment();

        $d['territories'] = $territories;
        $d['enrollment_products'] = $enrollment_products;

        $d['product'] = $product;
        $d['producttype'] = $producttype;

        return view('admin.products.detail')->with($d);
    }

    public function updateProduct()
    {
        $req = request();

        $productId = $req->rec_id;
        $rec = Product::getById($productId);
        $valid = $this->validateProductUpdateForm($productId);
        ;
        if ($valid['valid'] != 1) return response()->json(['error' => 1, 'msg' => $valid['msg']]);

        // \App\Product::updateProduct($productId, $req);
        // \App\UpdateHistory::productUpdate($productId, $rec, $req);

        // dd($req->hasFile('productimage'));
        if ($req->hasFile('productimage')) {
            $uploadedFile = $req->file('productimage');
            $imageName = Storage::put('/images/products', $uploadedFile, 'public');            
            $req->imageName = $imageName;
            Product::deleteProductImage($productId);      
        }

        $valid = $this->validateProductUpdateForm($productId);
        if ($valid['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $valid['msg']]);
        }
        
        Product::updateProduct($productId, $req);
        \App\UpdateHistory::productUpdate($productId, $rec, $req);
        return response()->json(['error' => 0, 'msg' => 'Saved']);
    }

    private function validateProductUpdateForm($productId)
    {
        $req = request();
        $data = $req->all();
        $validator = Validator::make($data, [
            'productname' => 'required|unique:products,productname,' . $productId,
            'productdesc' => 'required',
            'long_description' => 'required',
            'price' => 'required|numeric|min:0',
            'shipping_price' => 'required|numeric|min:0',
            'itemcode' => 'required',
            'bv' => 'required|numeric|min:0',
            'cv' => 'nullable|numeric|min:0',
            'qv' => 'nullable|numeric|min:0',
            'qc' => 'nullable|numeric|min:0',
            'ac' => 'nullable|numeric|min:0',
            'territories' => 'required',
            'visible_by_enrollment_class' => 'required',
        ], [
            'productname.required' => 'Product Name is required',
            'productname.unique' => 'Product Name is already used',
            'productdesc.required' => 'Description is required',
            'long_description.required' => 'Long Description is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be numeric',
            'price.min' => 'Price value is invalid',
            'territories.required' => 'A minimum of 1 territory is required',
            'visible_by_enrollment_class.required' => 'A minimum of 1 enrolment class is required',
            'shipping_price.required' => 'Shipping Price is required',
            'shipping_price.numeric' => 'Shipping Price must be numeric',
            'shipping_price.min' => 'Shipping Price value is invalid',
            'itemcode.required' => 'Item code is required',
            'bv.required' => 'BV is required',
            'bv.numeric' => 'BV must be numeric',
            'bv.min' => 'BV value is invalid',
            'cv.numeric' => 'CV must be numeric',
            'cv.min' => 'CV value is invalid',
            'qv.numeric' => 'QV must be numeric',
            'qv.min' => 'QV value is invalid',
            'qc.numeric' => 'QC must be numeric',
            'qc.min' => 'QC value is invalid',
            'ac.numeric' => 'AC must be numeric',
            'ac.min' => 'AC value is invalid',
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

    private function validateProductAddForm()
    {
        $req = request();
        $data = $req->all();
        $validator = Validator::make($data, [
            'productname' => 'required|unique:products,productname',
            'producttype' => 'required|numeric|min:0',
            'productdesc' => 'required',
            'long_description' => 'required',
            'price' => 'required|numeric|min:0',
            'shipping_price' => 'required|numeric|min:0',
            'itemcode' => 'required',
            'sku' => 'required|unique:products,sku',
            'bv' => 'required|numeric|min:0',
            'cv' => 'nullable|numeric|min:0',
            'qv' => 'nullable|numeric|min:0',
            'qc' => 'nullable|numeric|min:0',
            'ac' => 'nullable|numeric|min:0',
            'territories' => 'required',
            'visible_by_enrollment_class' => 'required',
        ], [
            'productname.unique' => 'Product name is already used',
            'productname.required' => 'Product name is required',
            'producttype.required' => 'Category is required',
            'productdesc.required' => 'Description is required',
            'long_description.required' => 'Long Description is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be numeric',
            'price.min' => 'Price value is invalid',
            'territories.required' => 'A minimum of 1 territory is required',
            'visible_by_enrollment_class.required' => 'A minimum of 1 enrolment class is required',
            'shipping_price.required' => 'Shipping Price is required',
            'shipping_price.numeric' => 'Shipping Price must be numeric',
            'shipping_price.min' => 'Shipping Price value is invalid',
            'itemcode.required' => 'Item code is required',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'SKU you have entered is already exist',
            'bv.required' => 'BV is required',
            'bv.min' => 'BV value is invalid',
            'bv.numeric' => 'BV must be numeric',
            'cv.numeric' => 'CV must be numeric',
            'cv.min' => 'CV value is invalid',
            'qv.numeric' => 'QV must be numeric',
            'qv.min' => 'QV value is invalid',
            'qc.numeric' => 'QC must be numeric',
            'qc.min' => 'QC value is invalid',
            'ac.numeric' => 'AC must be numeric',
            'ac.min' => 'AC value is invalid',
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

    public function frmNewProduct()
    {
        $data = [];

        $producttype = ProductType::select('id', 'typedesc', 'statuscode')->get();
        $territories = Country::getAll();
        $enrollment_products = Product::getProductsEnrollment();

        $data['territories'] = $territories;
        $data['enrollment_products'] = $enrollment_products;
        $data['producttype'] = $producttype;
        return view('admin.products.frmNewProduct')->with($data);
    }

    public function addNewProduct()
    {
        $req = request();
        $productId = $req->rec_id;

        if ($req->hasFile('productimage')) {
            $uploadedFile = $req->file('productimage');
            $imageName = Storage::put('/images/products', $uploadedFile, 'public');
            $req->imageName = $imageName;
        }
        
        $valid = $this->validateProductAddForm($productId);
        if ($valid['valid'] == 0) {
            return response()->json(['error' => 1, 'msg' => $valid['msg']]);
        }        

        $productId = \App\Product::addProduct($req);
        \App\UpdateHistory::productAdd($productId);
        

        return response()->json(['error' => 0, 'msg' => 'save successfully', 'url' => url('/product/products')]);
    }
}
