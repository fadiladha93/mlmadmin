@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <iframe width="1" height="1" frameborder="0" scrolling="no"
                src="<?php echo e(Config::get('api_endpoints.KOUNTIFrameURL')); ?>/logo.htm?m=<?php echo e(Config::get('api_endpoints.kount.KOUNTMerchantID')); ?>&s=<?php echo e($sessionId); ?>">
        </iframe>
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            {{--<div class="content" style="background: #4aafd1">--}}
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <h3><strong>Purchase Products</strong></h3>
                    <p style="font-weight: 400">
                        Büümerangs come in packs of {{$product->num_boomerangs}}.

                        {{-- <br> Please specify how many packs
                        of {{$product->num_boomerangs}}
                        you would like to purchase. --}}

                    </p>
                    <div class="row product-tbl mt-4" id="ibuumerang_product_row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 prdct-head text-center">
                            PRODUCT
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 prdct-head text-center">
                            PRICE
                        </div>
                    </div>
                    <div class="row product-tbl" id="ibuumerang_product_row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 prdct-item text-center">
                             <p>
                                 <span>
                                     <img src="{{url('assets/images/white-icon.png')}}">
                                 </span>
                                 <span> {{$product->productname}} </span>
                             </p>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 prdct-item text-center">
                        {{--  <span class="outer">
                                         <span class="inner"><strong>$125.00</strong></span>
                                     </span> --}}
                                <p><strong>${{number_format($product->price,2)}} <span style="font-size: 10px">LIMITED TIME ONLY</span></strong></p>
                        </div>
                    </div>
{{--                    <p style="font-size: 9px; margin-top: 30px">ENTER PACK QUANTITY</p>--}}
                    <div class="qty-update" style="margin-top: 0px">
                        <form id="frmCheckOut" class="m-form">
                            <input type="hidden" name="product" value="{{$product->id}}">
                            <input type="hidden" name="sessionId" value="{{$sessionId}}">
{{--                            <input type="text" class="qty-box" name="quantity">--}}
                            <input type="hidden" name="quantity" value="1">
                            <input type="button" id="btnConfirmCheckOut" value="ADD TO CART" class="yellow-btn">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




