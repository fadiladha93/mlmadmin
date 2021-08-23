<div>
    Please enter your Primary Credit Card to be used<br/>
    for paying your monthly membership
</div>
<div class="row" style="margin:15px 0px;text-align:left;">
    <div class="col-md-6">
        <form class="m-form">
            @if($showPaymentProceedLink)
            <input type="hidden" name="do_immediate_payments" value="1"/>
            @endif
            <div class="m-portlet__body">
                <div class="m-form__section m-form__section--first">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-6">
                                <label>FIRST NAME</label>
                                <input type="text" name="first_name" class="form-control m-input" @if($payment!=null) value="{{$payment->firstname}}" @endif>
                            </div>
                            <div class="col-6">
                                <label>LAST NAME</label>
                                <input type="text" name="last_name" class="form-control m-input" @if($payment!=null) value="{{$payment->lastname}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label>CARD NUMBER</label>
                        <input type="text" name="number" class="form-control m-input">
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-6">
                                <label>CVV</label>
                                <input type="text" name="cvv" class="form-control m-input" @if($payment!=null) value="{{$payment->cvv}}" @endif>
                            </div>
                            <div class="col-6">
                                <label>EXPIRATION DATE</label>
                                <input type="text" name="expiry_date" class="form-control m-input" placeholder="mm/yyyy" @if($payment!=null) value="{{$payment->expMonth."/".$payment->expYear}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group" style="margin-top:10px">
                        <div class="m-checkbox-list">
                            <label class="m-checkbox">
                                <input type="checkbox" name="terms"> I AGREE TO THE TERMS
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <form class="m-form">
            <div class="m-portlet__body">
                <div class="m-form__section m-form__section--first">
                    <div class="form-group m-form__group">
                        <label>ADDRESS</label>
                        <input type="text" name="address1" class="form-control m-input" @if($address!=null) value="{{$address->address1}}" @endif>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-6">
                                <label>APT / SUITE</label>
                                <input type="text" name="apt" class="form-control m-input" @if($address!=null) value="{{$address->apt}}" @endif>
                            </div>
                            <div class="col-6">
                                <label>COUNTRY</label>
                                <select name="countrycode" class="form-control">
                                    <option></option>
                                    @foreach($countries as $c)
                                    <?php
                                    $selectedC = $address!=null ? $address->countrycode : "";
                                    ?>
                                    <option @if($selectedC == $c->countrycode) selected @endif value="{{$c->countrycode}}">{{$c->country}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-6">
                                <label>CITY / TOWN</label>
                                <input type="text" name="city" class="form-control m-input" @if($address!=null) value="{{$address->city}}" @endif>
                            </div>
                            <div class="col-6">
                                <label>STATE / PROVINCE</label>
                                <input type="text" name="stateprov" class="form-control m-input" @if($address!=null) value="{{$address->stateprov}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-6">
                                <label>POSTAL CODE</label>
                                <input type="text" name="postalcode" class="form-control m-input" @if($address!=null) value="{{$address->postalcode}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div style="margin-top:15px;">
    <div class="pull-left">
        <a class="btn m-btn m-btn--air btn-warning btnStartAgain">Start Over</a>
    </div>
    <div class="pull-right">
        @if($showPaymentProceedLink)
        <a currentStep="3" class="btn m-btn m-btn--air btn-warning btnNextStep">Proceed with payment and continue</a>
        @else
        <a currentStep="3" class="btn m-btn m-btn--air btn-warning btnNextStep">Next</a>
        @endif
    </div>
    <div class="clearfix"></div> 
</div>