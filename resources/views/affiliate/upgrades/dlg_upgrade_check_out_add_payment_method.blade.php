@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <div class="card-wrap" id="frmUpgrade">
                        <div class="card-lt">
                            <div class="card-field">
                                <div class="card-field-full">
                                    <label>First Name <span class="req">*</span></label>
                                    <input type="text" name="first_name" class="input-box">
                                </div>
                                <div class="card-field-full">
                                    <label>Last Name <span class="req">*</span></label>
                                    <input type="text" name="last_name" class="input-box">
                                </div>
                            </div>
                            <div class="card-field">
                                <div class="card-field-8">
                                    <label>card number <span class="req">*</span></label>
                                    <input type="text" name="number" class="input-box cc-number">
                                </div>
                                <div class="card-field-4">
                                    <div class="card-block">
                                        <label>CVV <span class="req">*</span></label>
                                        <input type="text" name="cvv" class="input-box  cc-cvc">
                                    </div>
                                    <div class="card-block">
                                        <label>Expiration date <span class="req">*</span></label>
                                        <input type="text" name="expiry_date" class="input-box cc-expires"
                                               placeholder="MM/YYYY">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-rt">
                            <div class="card-field">
                                <div class="card-field-full">
                                    <label>Address <span class="req">*</span></label>
                                    <input type="text" name="address1" class="input-box">
                                </div>
                            </div>

                            <div class="card-field">
                                <div class="card-field-5">
                                    <label>Apt/suite</label>
                                    <input type="text" name="apt" class="input-box">
                                </div>
                                <div class="card-field-5">
                                    <label>Country <span class="req">*</span></label>
                                    <select name="countrycode" id="countryCode" class="input-box">
                                        <option></option>
                                        @foreach($countries as $c)
                                            <option value="{{$c->countrycode}}">{{$c->country}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="card-field">
                                <div class="card-field-5">
                                    <label>City / Town <span class="req">*</span></label>
                                    <input type="text" name="city" class="input-box">
                                </div>
                                <div class="card-field-5">
                                    <label>State / Province </label>
                                    <select class="input-box" name="stateprov" id="stateprov">
                                    </select>
                                </div>
                            </div>
                            <div class="card-field">
                                <div class="card-field-5">
                                    <label>Postal Code <span class="req">*</span></label>
                                    <input type="text" name="postalcode" class="input-box">
                                </div>
                            </div>
                            <div class="card-field" style="margin-top: 10px">
                                <div class="card-field-full">
                                    <input type="checkbox" name="terms"> I AGREE TO THE TERMS
                                </div>
                            </div>
                        </div>
                        <div class="submit-cart submit-btn-grp">
                            <button data-id="1" product-id="{{$product_id}}" class="blue-btn"
                                    id="btnBackCheckOutUpgradePayment"
                            >BACK
                            </button>
                            <button id="btnAddNewCardOnCheckOutUpgradeProducts" class="yellow-btn">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



