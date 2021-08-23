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
                                    <label>Your SSN <span class="req">*</span></label>
                                    <input type="text" name="ssn" class="input-box" value="{{$user->ssn}}">
                                </div>
                                <div class="card-field-full">
                                    <label>Business EIN <span class="req">*</span></label>
                                    <input id='ein' class="input-box" name="ein" value="{{$user->ein}}">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="has_no_ein" name="has_no_ein" >
                                    <label class="form-check-label font-weight-bold" for="has_no_ein"
                                           style="font-size:13px">I DON'T HAVE AN EIN</label>
                                </div>
                            </div>
                        </div>
                        <div class="submit-cart submit-btn-grp">
                            <button  id="btnConfirmTaxInformation" class="yellow-btn">CONFIRM</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



