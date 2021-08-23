@include('affiliate.layouts.new_modal_style')
@extends('affiliate.layouts.main')
@section('main_content')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgPaymentList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <h3><strong>Please confirm your payment method and tax information
                            <br> All fields are required.</strong>
                    </h3>
                    <div class="card-wrap" id="frmTaxInfo">
                        <div class="card-lt">
                            <ul class="list-group" style="margin-top: 10px">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>payment methods <span class="req">*</span></label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <label>primary card</label>
                                    </div>
                                </div>
                                <li class="list-group-item">
                                    <?php
                                    if (!empty($cvv)) {
                                        foreach($cvv as $key => $cv) {
                                            if (!empty($cv->token)) {
                                                echo '
                                                <div class="form-check">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="form-check-label text-dark" for="payment-option- ' . $key .'">XXXX XXXX XXXX ' . substr($cv->token, -4) .'</label>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <input class="form-check-input" name="primary_payment_id" type="radio" id="payment-option- ' . $key .'" name="payment_method" value="'. $cv->id .'">
                                                        </div>
                                                    </div>
                                                </div>
                                            ';
                                            }
                                        }
                                    }
                                    ?>
                                </li>
                            </ul>
                            <div class="pt-2">
                                <a href="#" class="text-light" id="add-new-payment-card"><small style="text-decoration: underline">ADD NEW CARD</small></a>
                            </div>

                        </div>

                        <div class="card-rt">
                            <div class="card-field">
                                <div class="card-field-full" style="margin-top: 10px;">
                                    Please confirm that you live outside the United States by filling out the attached form. This form has no tax implications for you. It just helps the company by confirming that you live outside of the US. Thank you for taking the time to do this.
                                </div>
                                <div class="card-field-full pt-3 pb-3">
                                    <button id="btnSignFormW8BEN" class="btn btn-dark btn-sm">SIGN FORM</button>
                                </div>
                                <div class="card-field-full">
                                    Once completed, you will be able to return to your dashboard.  <strong>It may take  a few minutes for your account to update after signing.</strong>
                                </div>
                            </div>
                        </div>
                        <div class="submit-cart submit-btn-grp">
                            <button  id="btnConfirmTaxInformationIntl" class="yellow-btn">CONFIRM</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



