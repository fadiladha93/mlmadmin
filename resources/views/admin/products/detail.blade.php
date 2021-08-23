@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <form method="post" id="formUpdateProduct" name="formUpdateProduct" action="/update-product">
            @csrf @method('POST')
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Product Detail
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    
                    <input class="btn btn-success btn-sm m-btn--air" id="btnUpdateProduct" style="color:#FFFFFF;" type="submit" value="Save">
                    <a href="{{url('/product/products')}}" class="btn btn-dark btn-sm m-btn--air">Back</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row" id="frmUpdateProduct">
                    <input type="hidden" name="rec_id" value="{{$product->id}}" />
                    <div class="col-md-12">
                        <div class="m-portlet">
                            <div class="m-form">
                                <div class="m-portlet__body">                               
                                    <div class="m-form m-form__section--first m-form--label-align-right">
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Product Image</label>
                                            <div class="col-md-8">                                           
                                                @if(!empty($product->image))
                                                    <img id="output_image" src="{{ \Storage::url($product->image) }}" style="width: 100%; height: 200px; object-fit: contain;"/>
                                                    <br>
                                                    {{--  {{ \Storage::url($product->image) }}  --}}
                                                @else
                                                    <img id="output_image" style="display: none; width: 100%; height: 200px; object-fit: contain;"/>
                                                @endif
                                                <input class="form-control" type="file" id="product_image" name="productimage" onchange="preview_image(event)">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Product Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="productname" value="{{isset($product->productname) ? $product->productname : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Category</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="producttype">
                                                    @foreach($producttype as $type)
                                                    <option value="{{$type->id}}" @if($product->producttype == $type->id) selected @endif>{{$type->typedesc}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Description</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="productdesc" value="{{ $product->productdesc ?? ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Long Description</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" name="long_description">{{isset($product->long_description) ? $product->long_description : ""}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Is Enabled</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="is_enabled">
                                                    <option value="1" @if($product->is_enabled == 1) selected @endif>Yes</option>
                                                    <option value="0" @if($product->is_enabled == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Price</label>
                                            <div class="col-md-8">
                                                    <input class="form-control" type="text" name="price" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="{{isset($product->price) ? $product->price : ""}}" data-type="currency" placeholder="$0.00"> 
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Shipping Price</label>
                                            <div class="col-md-8">
                                                <div class="d-flex">
                                                    <input class="form-control" type="text" name="shipping_price" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="{{isset($product->shipping_price) ? $product->shipping_price : ""}}" data-type="currency" placeholder="$0.00"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Item Code</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="itemcode" value="{{isset($product->itemcode) ? $product->itemcode : ""}}">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">BV</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="bv" value="{{isset($product->bv) ? $product->bv : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">CV</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="cv" value="{{isset($product->cv) ? $product->cv : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">QV</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="qv" value="{{isset($product->qv) ? $product->qv : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">QC</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="qc" value="{{isset($product->qc) ? $product->qc : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">AC</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="ac" value="{{isset($product->ac) ? $product->ac : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Allow Quantity Change</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="allow_quantity_change">
                                                    <option value="1" @if(isset($product->allow_quantity_change) && $product->allow_quantity_change == 1) selected @endif>Yes</option>
                                                    <option value="0" @if(isset($product->allow_quantity_change) && $product->allow_quantity_change == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Taxable</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="is_taxable">
                                                    <option value="1" @if(isset($product->is_taxable) && $product->is_taxable == 1) selected @endif>Yes</option>
                                                    <option value="0" @if(isset($product->is_taxable) && $product->is_taxable == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Tax Code</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="tax_code" value="{{isset($product->tax_code) ? $product->tax_code : ""}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Enable Shipping</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="shipping_enabled">
                                                    <option value="1" @if(isset($product->shipping_enabled) && $product->shipping_enabled == 1) selected @endif>Yes</option>
                                                    <option value="0" @if(isset($product->shipping_enabled) && $product->shipping_enabled == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Allow Multiple In Cart</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="allow_multiple_on_cart">
                                                    <option value="1" @if(isset($product->allow_multiple_on_cart) && $product->allow_multiple_on_cart == 1) selected @endif>Yes</option>
                                                    <option value="0" @if(isset($product->allow_multiple_on_cart) && $product->allow_multiple_on_cart == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- fdgf --}}
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Territories</label>
                                            <div class="col-md-8">
                                                <input onclick="console.log('dfsd'); $(`select[name='territories[]'] option`).prop('selected', true) " type="button" class="form-control" value="Select All" />

                                                <select class="form-control" name="territories[]" multiple="multiple">
                                                @if(isset($territories))
                                                    @foreach($territories as $territory)
                                                    <option value="{{$territory->id}}" @if(in_array($territory->id, $product->territories->toArray())) selected @endif>{{$territory->country}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Is Visible</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="is_visible">
                                                    <option value="1" @if(isset($product->is_visible) && $product->is_visible == 1) selected @endif>Yes</option>
                                                    <option value="0"  @if(isset($product->is_visible) && $product->is_visible == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Enrollment class Visibility</label>
                                            <div class="col-md-8">
                                                <input onclick="console.log('dfsd'); $(`select[name='visible_by_enrollment_class[]'] option`).prop('selected', true) " type="button" class="form-control" value="Select All" />
                                                <select class="form-control" name="visible_by_enrollment_class[]" multiple>
                                                @if(isset($enrollment_products))
                                                @foreach($enrollment_products as $enrollment_product)
                                                    <option value="{{$enrollment_product->id}}" @if(in_array($enrollment_product->id, $product->enrollments->toArray())) selected @endif>{{$enrollment_product->productname}}</option>
                                                @endforeach
                                                @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Visible days from enrollment</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="visible_days_from_enrollment" type="number" value="{{$product->visible_days_from_enrollment ?? ""}}">
                                            </div>
                                        </div>

                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}
</script>