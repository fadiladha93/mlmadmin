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
                    <h3><strong>Xccelerate Photo Book</strong></h3>
                    <p style="font-weight: 400">
                        50% of proceeds from Xccelerate Photo books go to the buumfoundation.
                    </p>
                    <table class="product-tbl" id="ibuumerang_product_table">
                        {{--                        <tr class="prdct-head">--}}
                        {{--                            <td>PRODUCT</td>--}}
                        {{--                            <td>PRICE</td>--}}
                        {{--                        </tr>--}}
                        <tr class="prdct-item">
                            <td width="10%"></td>
                            <td class="prdct-name">
                                <div class="pull-left">
                                    <img src="{{url('assets/images/photobook_orderform_2.jpg')}}">
                                </div>
                                <div class="pull-left text-left" style="font-size: 24px;"><p>Eccexerate Event<br>Limited Edition<br>Photobook</p></div>
                            </td>
                            <td class="text-left">
                                <span>
                                        <img src="{{url('assets/images/photobook_orderform_1.jpg')}}">
                                    </span>
                            </td>
                            <td width="10%"></td>
                        </tr>
                    </table>
                    <p style="font-size: 9px; margin-top: 30px">ENTER QUANTITY</p>
                    <div class="qty-update" style="margin-top: 0px">
                        <form id="frmCheckOut" class="m-form">
                            <input type="hidden" name="product" value="{{$product->id}}">
                            <input type="hidden" name="sessionId" value="{{$sessionId}}">
                            <input type="text" class="qty-box" name="quantity">
                            <input type="button" id="btnConfirmCheckOut" value="ADD TO CART" class="yellow-btn">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




