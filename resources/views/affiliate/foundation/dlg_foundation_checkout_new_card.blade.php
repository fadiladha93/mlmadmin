@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/ibuum-foundation-logo.png')}}">
            </div>
            <div class="cm-body" style="background: #01B6EB">
                <div class="cm-body-inner">
                    <div class="card-wrap" id="frmIbuumFoundationNewCard">
                        <div class="card-field">
                            <div class="card-field-full">
                                <label>card number <span class="req">*</span></label>
                                <input type="text" name="number" class="input-box cc-number">
                                <div class="card-field">
                                    <div class="card-field-5">
                                        <label>CVV <span class="req">*</span></label>
                                        <input type="text" name="cvv" class="input-box  cc-cvc">
                                    </div>
                                    <div class="card-field-5">
                                        <label>Expiration date <span class="req">*</span></label>
                                        <input type="text" name="expiry_date" class="input-box cc-expires"
                                               placeholder="MM/YYYY">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-cart submit-btn-grp">
                                <input type="hidden" name="session_id" value="{{$sessionId}}">
                                <input type="hidden" name="amount" value="{{$amount}}">
                                <button class="blue-btn" id="btnCheckoutFoundationBack" data-dismiss="modal">BACK
                                </button>
                                <button id="btnNewCardCheckoutFoundation" class="yellow-btn">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



