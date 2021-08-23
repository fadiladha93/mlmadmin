@include('affiliate.layouts.new_modal_style');
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/ibuum-foundation-logo.png')}}">
            </div>
            <div class="cm-body" style="background: #01B6EB">
                <div class="cm-body-inner">
                    <div style="padding:20px">
                        <p style=" font-family:'Poppins';
                    font-size: 16px;
                    color: #fff !important;
                    margin-bottom: 0; text-align: center">
                            Choose the amount you would like to donate to the büüm Foundation
                        </p>
                    </div>
                    <div class="card-wrap" id="frmIbuumFoundation">
                        <div class="card-field-full">
                            <label>Amount <span class="req">*</span></label>
                            <input type="text" name="amount" class="input-box">
                        </div>
                        <div class="card-field-full">
                            <label>Payment Method <span class="req">*</span></label>
                            <select name="payment_method" class="form-control input-box">
                                <option value="">Select Payment Method</option>
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
                                <option value="new_card">ADD NEW CARD</option>
                            </select>
                        </div>
                        <div class="submit-cart submit-btn-grp">
                            <input type="hidden" name="session_id" value="{{$sessionId}}">
                            <button class="blue-btn"  data-dismiss="modal">CLOSE</button>
                            <button id="btnCheckoutFoundation" class="yellow-btn">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



