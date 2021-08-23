<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <iframe width="1" height="1" frameborder="0" scrolling="no"
                    src="{{Config::get('api_endpoints.KOUNTIFrameURL')}}/logo.htm?m={{Config::get('api_endpoints.KOUNTMerchantID')}}&s={{$sessionId}}">
            </iframe>
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="content" style="background: #4aafd1">
                <div class="cm-body-inner step-2">
                    <h3>Cart</h3>
                    <div class="order-details">
                        <div class="row">
                            <div class="col-md-8">
                                <h4>Order Details</h4>
                                <table class="table" id="upgrade_product_table">
                                    <tr class="prdct-head">
                                        <td>PRODUCT</td>
                                        <td>PRICE</td>
                                        <td>QUANTITY</td>
                                    </tr>
                                    <tr class="prdct-item">
                                        <td class="prdct-name">
                                            <p>{{$product->productdesc}}</p>
                                        </td>
                                        <td>
                                            ${{number_format($product->price,2)}}
                                        </td>
                                        <td>
                                            {{$checkOutQty}}
                                        </td>
                                    </tr>
                                    <tr class="total-price">
                                        <td> SUB TOTAL</td>
                                        <td> ${{number_format($checkOutQty * $product->price ,2)}}</td>
                                        <td></td>
                                    </tr>
                                    <tr class="total-price">
                                        <td> TOTAL</td>
                                        <td> ${{number_format($checkOutQty * $product->price ,2)}}</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <h4>Quick Checkout</h4>
                                <div class="m-portlet__body">
                                    <div class="m-form__section m-form__section--first">
                                        <div class="form-group m-form__group">
                                            <form id="frmCheckOutPayment">
                                                <label>Payment Method</label>
                                                <select name="payment_method" class="form-control m-input">
                                                    <option value="">Select Payment Method</option>
                                                    <?php
                                                    if (!empty($cvv)) {
                                                        foreach ($cvv as $cv)
                                                            echo '<option value="' . $cv->id . '">CARD ENDING IN ' . substr($cv->token, -4) . '</option>';
                                                    }
                                                    ?>
                                                    <option value="e_wallet">E WALLET</option>
                                                    <option value="new_card">ADD NEW CARD</option>
                                                </select>
                                                <input type="hidden" name="discount_coupon">
                                                <div class="submit-cart submit-btn-grp" style="margin-top: 30px">
                                                    <button type="button" id="btnConfirmCheckOutPaymentIbuumerangPacks"
                                                            class="btn m-btn m-btn--air btn-warning">SUBMIT
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="m-portlet__body">
                                    <div class="m-form__section m-form__section--first">
                                        <div class="form-group m-form__group">
                                            <form id="frmCheckOutPaymentCoupon">
                                                <label>Do you have a coupon code?</label>
                                                <input type="text" name="coupon" class="form-control m-input">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="m-portlet__body">
                                    <div class="m-form__section m-form__section--first">
                                        <div class="form-group m-form__group">
                                            <div class="submit-cart submit-btn-grp" style="margin-top: 33px">
                                                <button type="button" id="btnApplyCheckOutBoomerangCoupon"
                                                        class="btn m-btn m-btn--air btn-danger">APPLY
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
