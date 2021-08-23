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
                    <h3><strong>{{ $product->productname }}</strong></h3>
                    <p style="font-weight: 400">
                        {{ $product->productdesc }}
                    </p>
                    <div class="row product-tbl mt-4" id="show_product_row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                <div class="shiny-new-price">
                                      $ {{$product->price}}
                                </div>
                            </div>
                    </div>

                    <div class="qty-update" style="margin-top: 0px">
                        <form id="frmCheckOut" class="m-form">
                            <input type="hidden" name="product" value="{{$product->id}}">
                            <input type="hidden" name="sessionId" value="{{$sessionId}}">

                            @if ($product->id == 56)
                                <input type="hidden" name="quantity" value="1">
                            @else
                                <p style="font-size: 9px; margin-top: 30px">ENTER QUANTITY</p>
                                <input type="text" class="qty-box" name="quantity">
                            @endif

                            <input type="button" id="btnConfirmCheckOut" value="ADD TO CART" class="yellow-btn">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




