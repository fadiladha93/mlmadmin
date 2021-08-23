@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <div class="card-wrap" id="frmReactivateSuspendedSubscription">
                        <div class="">
                            <div class="card-field">
                                <table class="product-tbl" id="ibuumerang_product_table" style="margin-left: 0px; margin-bottom: 5px;">
                                    <tbody>
                                    <tr class="prdct-head">
                                        <td>PRODUCT</td>
                                        <td>PRICE</td>
                                    </tr>
                                    <tr class="prdct-item">
                                        <td class="prdct-name">
                                            <p>
                                                <span style="font-size: 12px;"> Subscription Fee </span>
                                            </p>
                                        </td>
                                        <td class="text-left">
                                            <p>
                                                <strong style="font-size: 12px;">${{$subscription_amount}}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                    @if($subscription_fee->is_enabled)
                                        <tr class="prdct-item" style="border-bottom: 1px solid #ffffff;">
                                        <td class="prdct-name">
                                            <p>
                                                <span style="font-size: 12px;"> Reactivation Fee </span>
                                            </p>
                                        </td>
                                        <td class="text-left">
                                            <p>
                                                <strong style="font-size: 12px;">${{number_format($subscription_fee->price,2)}}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr class="prdct-item">
                                        <td class="prdct-name">
                                            <p>
                                                <span style="font-size: 12px;"> Total </span>
                                            </p>
                                        </td>
                                        <td class="text-left">
                                            <p>
                                                <strong style="font-size: 12px;">${{$total}}</strong>
                                            </p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="coupon-code" style="margin-left: 0px; margin-top: 20px">
                                    <form id="reactivateSubscriptionAddCouponCode">
                                        <div class="copuon-lt">
                                            <label> Do you have a voucher code?</label>
                                            <input type="text" name="coupon" class="input-box"
                                                   value="{{isset($coupon_code)?$coupon_code:''}}">
                                        </div>
                                    </form>
                                    <div class="coupn-btn">
                                        <button type="button" id="btnReactivateSubscriptionAddCouponCode"
                                                class="yellow-btn" value="Apply">Apply
                                        </button>
                                    </div>
                                </div>
                                <div id="addNewCard" style="margin-top: 15px">
                                    <label>Payment Method <span class="req">*</span></label>
                                    <select class="form-control form-control-sm" name="subscription_payment_method_id"
                                            id="subscription_payment_method_type_id">
                                        {!! $payment_method !!}
                                        <option value="add_new_card">Add New Card</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="submit-cart submit-btn-grp">
                            <button class="blue-btn" data-dismiss="modal">CLOSE
                            </button>
                            <button id="btnSubscriptionReactivateSubmitButton" class="yellow-btn">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







