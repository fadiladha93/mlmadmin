@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Add New Product
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmNewProduct">
                <div class="col-md-12">
                    <div class="m-portlet">
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <form method="post" id="formNewProduct" name="formNewProduct" action="/add-new-product">
                                    @csrf @method('POST')
                                    <div class="m-form m-form__section--first m-form--label-align-right">
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Product Image</label>
                                            <div class="col-md-8">
                                                <img id="output_image" style="display: none; width: 100%; height: 200px; object-fit: contain;"/>

                                                <input class="form-control" type="file" id="product_image" name="productimage" onchange="preview_image(event)">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Product Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="productname" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Category</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="producttype">
                                                    <option value=""></option>
                                                    @foreach($producttype as $type)
                                                    <option value="{{$type->id}}">{{$type->typedesc}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Description</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="productdesc" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Long Description</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" name="long_description" value=""></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Price</label>
                                            <div class="col-md-8">
                                                <div class="d-flex">
                                                    <input class="form-control" type="text" name="price" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$0.00"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Shipping Price</label>
                                            <div class="col-md-8">
                                                <div class="d-flex">
                                                    <input class="form-control" type="text" name="shipping_price" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="$0.00"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Item Code</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="itemcode" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">SKU</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="sku" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">BV</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="bv" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">CV</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="cv" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">QV</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="qv" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">QC</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="qc" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">AC</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="ac" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Auto Ship</label>
                                            <div class="col-md-8">
                                                <div class="m-checkbox-inline">
                                                    <label class="m-checkbox">
                                                        <input name="isautoship" type="checkbox" value="1"> Enable if auto ship
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Allow Quantity Change</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="allow_quantity_change">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Taxable</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="is_taxable">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Tax Code</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="tax_code" value="">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Enable Shipping</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="shipping_enabled">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Allow Multiple In Cart</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="allow_multiple_on_cart">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- fdgf --}}
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Territories</label>
                                            <div class="col-md-8">
                                                <input onclick="console.log('dsfffsd'); $(`select[name='territories[]'] option`).prop('selected', true) " type="button" class="form-control" value="Select All" />

                                                <select class="form-control" id="territories" name="territories[]" multiple>
                                                    @foreach($territories as $territory)
                                                    <option value="{{$territory->id}}">{{$territory->country}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Is Visible</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="is_visible">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Enrollment class Visibility</label>
                                            <div class="col-md-8">
                                                <input onclick="console.log('dfsd'); $(`select[name='visible_by_enrollment_class[]'] option`).prop('selected', true) " type="button" class="form-control" value="Select All" />
                                                <select class="form-control" name="visible_by_enrollment_class[]" multiple>
                                                @foreach($enrollment_products as $enrollment_product)
                                                    <option value="{{$enrollment_product->id}}">{{$enrollment_product->productname}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Visible days from enrollment</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="visible_days_from_enrollment" type="number" value="">
                                            </div>
                                        </div>

                                        <div class="row justify-content-center my-3">
                                           
                                            <input class="btn btn-success btn-sm m-btn--air" style="color:#FFFFFF;" type="submit" value="Create Product">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script type='text/javascript'>
function preview_image(event) 
{    
 var reader = new FileReader();
 reader.onload = function()
 {
  var output = document.getElementById('output_image');
  output.style.display = 'initial'
  output.src = reader.result;        return response()->json(['error' => 0, 'msg' => 'Saved']);

 }
 reader.readAsDataURL(event.target.files[0]);
}
</script>