@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/xccelerate.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <h3>Cart</h3>
                    <div class="order-detail-wrap">
                        <div class="order-details">
                            <h4>order details</h4>
                            <table class="product-tbl" id="ticket_product_table">
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
                                        <strong>${{number_format(\App\Product::TICKET_PURCHASE_DISCOUNT_PRICE,2)}}</strong>
                                    </td>
                                    <td>
                                        <strong>1</strong>
                                    </td>
                                </tr>
                                <tr class="total-price" style="font-weight: bold;">
                                    <td> SUB TOTAL</td>
                                    <td> ${{number_format(1 * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE ,2)}}</td>
                                </tr>
                                <tr class="total-price" style="font-weight: bold;">
                                    <td> TOTAL</td>
                                    <td> ${{number_format(1 * \App\Product::TICKET_PURCHASE_DISCOUNT_PRICE ,2)}}</td>
                                </tr>
                            </table>

                            <div class="coupon-code" style="margin-top: 20px">
                                <form id="frmCheckOutPaymentCoupon">
                                    <div class="copuon-lt">
                                        <label> Do you have a coupon code?</label>
                                        <input type="text" name="coupon" class="input-box">
                                    </div>
                                </form>
                                <div class="coupn-btn">
                                    <input type="button" id="btnApplyCheckOutTicketCoupon" class="yellow-btn"
                                           value="Apply">
                                </div>
                            </div>
                        </div>
                        <div class="quick-checkout">
                            <h4>quick checkout</h4>
                            <div class="payment-method">
                                <div class="payment-field">
                                    <form id="frmCheckOutPayment">
                                        <label>payment method <span class="req">*</span></label>
                                        <select name="payment_method">
                                            <option value="">Select Payment Method</option>
                                            <option value="new_card">ADD NEW CARD</option>
                                            <?php
                                            if (!empty($cvv)) {
                                                foreach ($cvv as $cv) {
                                                    if (!empty($cv->token)) {
                                                        echo '<option value="' . $cv->id . '">CARD ENDING IN ' . substr($cv->token, -4) . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="e_wallet">E WALLET</option>
                                        </select>
                                        <input type="hidden" name="discount_coupon">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="submit-cart submit-btn-grp" style="margin-top:30px">
                            <button data-id="1" class="blue-btn" id="btnBackCheckOutPaymentTicketPacks"
                            >BACK
                            </button>
                            <button class="yellow-btn" id="btnConfirmCheckOutPaymentTicketPacks"
                            >SUBMIT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
