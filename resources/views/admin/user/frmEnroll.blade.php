@extends('admin.layouts.main')

@section('main_content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            New Enrollment
                        </h3>
                    </div>
                </div>

                <div class="m-portlet__head-tools">
                    <button id="btnPrevStep" class="btn btn-primary mr-2" disabled="disabled">Prev Step</button>
                    <button id="btnNextStep" class="btn btn-primary">Next Step</button>
                </div>
            </div>
            <div class="m-portlet__body">
                <div id="frmEnroll">
                    <form class="step active-step row">
                        <div class="col-md-6">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text">
                                                Personal Information
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">First Name</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="firstname" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Last Name</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="lastname" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Business Name</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="business_name">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Phone</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="phonenumber" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Mobile</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="mobilenumber" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Date of birth</label>
                                                <div class="col-md-8">
                                                    <input class="form-control date_picker2" name="date_of_birth" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text">
                                                Primary Address
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Address 1</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="address1" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Address 2</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="address2">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Apt / Suite</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="apt">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">City</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="city" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">State / Province</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="stateprov" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Postal Code</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="postalcode" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Country Code</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="countrycode"
                                                            id="countryCodeSelect" required>
                                                        @foreach($countries as $country)
                                                            <option @if ($country->countrycode == 'US') selected @endif
                                                            data-tier3="{{$country->is_tier3}}"
                                                                    value="{{$country->countrycode}}">{{$country->country}}
                                                                ({{$country->countrycode}})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class="step row">
                        <div class="col-md-6">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text">
                                                Basic Information
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Dist ID</label>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="distid" value="{{$distId}}">
                                                    <input class="form-control" disabled="disabled"
                                                           value="{{$distId}}">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Username</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="username" id="usernameInput" required>
                                                </div>
                                            </div>
                                            <div class="m-form m-form__section--first m-form--label-align-right">
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Email</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" name="email" id="emailInput" required>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Default Password</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" name="default_password" id="defaultPasswordInput" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Sponsor</label>
                                                <div class="col-md-8">
                                                    <select class="form-control m-select2" id="select2_sponsor" style="width: 100%" name="sponsorid" required></select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Enrollment Date</label>
                                                <div class="col-md-8">
                                                    <input class="form-control date_picker2" name="enrollment_date" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Added by</label>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="entered_by" value="{{Auth::user()->id}}">
                                                    <input class="form-control" disabled="disabled"
                                                           value="{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Remarks</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="remarks" value=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text">
                                                Enrollment Information
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Enrollment Package</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="subscription_package"
                                                            id="enrollmentPackageSelect">
                                                        @foreach($packages as $index=>$package)
                                                            <option @if ($package->id == 1) selected @endif
                                                                    data-price="{{$package->price}}"
                                                                    value="{{$package->id}}">{{$package->productname}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Subscription</label>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="subscription_product" value="33">
                                                    <select class="form-control"
                                                            id="subscriptionProductSelect" disabled="disabled">
                                                        @foreach($subscription_products as $subscription_product)
                                                            <option @if ($subscription_product->id == 33) selected
                                                                    @endif value="{{$subscription_product->id}}">{{$subscription_product->productname}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Subscription Start Date</label>
                                                <div class="col-md-8">
                                                    <input class="form-control date_picker2"
                                                           id="subscriptionStartDateInput"
                                                           name="subscription_start_date" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <div class="col-md-8 offset-md-4" style="margin-top: 8px;">
                                                        {{--                                                    <div class="form-group form-check">--}}
                                                        {{--                                                        <input type="checkbox" class="form-check-input"--}}
                                                        {{--                                                               name="activateIDecide" checked>--}}
                                                        {{--                                                        <label>Add iDecide account</label>--}}
                                                        {{--                                                    </div>--}}
                                                        {{--                                                    <div class="form-group form-check">--}}
                                                        {{--                                                        <input type="checkbox" class="form-check-input"--}}
                                                        {{--                                                               name="activateSor" checked>--}}
                                                        {{--                                                        <label>Add SOR account</label>--}}
                                                        {{--                                                    </div>--}}
                                                        <div class="form-group form-check">
                                                            <label>
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="activateIPayout" checked>
                                                                Add iPayout account</label>
                                                        </div>
                                                        <div class="form-group form-check">
                                                            <label>
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="subscribe" checked>
                                                                Subscribe to MailChimp</label>
                                                        </div>
                                                        <div class="form-group form-check" id="addEventTicketDiv"
                                                             style="display: none;">
                                                            <label>
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="addEventTicket" id="addEventTicketCheckbox"
                                                                    data-price="{{$eventTicketPrice}}"
                                                                >
                                                                Add Event Ticket</label>
                                                        </div>
                                                        <div class="form-group form-check" id="addVideoTrainingDiv"
                                                             style="display: none;">
                                                            <label>
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="addVideoTraining" id="addVideoTrainingCheckbox"
                                                                       data-price="{{$videoTrainingPrice}}"
                                                                >
                                                                Add Xccelerated Video Training</label>
                                                        </div>
                                                        <div class="form-group form-check">
                                                            <label>
                                                                <input type="checkbox" class="form-check-input"
                                                                       name="sendSponsorEmail" checked>
                                                                Send Sponsor Email</label>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class="step row">
                        <div class="col-md-6">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text" id="billingInformationWithTotal">
                                                Billing Information
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Payment Method</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="payment_method_type"
                                                            id="paymentMethodSelect" required>

                                                        <optgroup label="United States (Credit card)">
                                                            <option selected
                                                                    value="{{ \App\PaymentMethodType::TYPE_METROPOLITAN }}">
                                                                Metropolitan
                                                            </option>
                                                            <option value="{{ \App\PaymentMethodType::TYPE_PAYARC }}">
                                                                PayArc
                                                            </option>
                                                        </optgroup>

                                                        <optgroup label="International (Credit card)">
                                                            <option
                                                                value="{{ \App\PaymentMethodType::TYPE_T1_PAYMENTS }}">
                                                                T1
                                                                Payments
                                                            </option>
                                                        </optgroup>

                                                        <optgroup label="Other">
                                                            <option value="voucher">Voucher Code</option>
                                                            <option value="comp">Comp</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="compSection" style="display: none;">
                                                <div class="form-group m-form__group row">
                                                    <div class="col-md-8 offset-md-4">
                                                        <label>
                                                            <input type="checkbox" class="form-check-input"
                                                                   name="comp_agreed" id="compCheckbox" required>
                                                            I understand that this waves all enrollment fees with exception of monthly fees.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="voucherSection" style="display: none;">
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Voucher Code</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" name="voucher_code" id="voucherCodeInput" required>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <div class="col-md-8 offset-4">
                                                        <button type="button" class="btn btn-info" id="btnVerifyVoucher">Verify Voucher Code</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="creditCardSection">
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Cardholder Name</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" name="credit_card_name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Card Number</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" name="credit_card_number" required>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Expiration Date</label>
                                                    <div class="col-md-8">
                                                        <input name="expiration_date" class="input-box"
                                                               placeholder="MM/YYYY" required>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">CVV</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control cc-number" name="cvv" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group form-check">
                                                <div class="col-md-8 offset-md-4">
                                                    <label>
                                                        <input type="checkbox" class="form-check-input"
                                                               name="billingSame" id="billingSameCheckbox">
                                                        Billing address is the same as primary
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m-portlet" id="billingAddress">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text">
                                                Billing Address
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Address 1</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="billing_address1" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Address 2</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="billing_address2">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Apt / Suite</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="billing_apt">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">City</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="billing_city" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">State / Province</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="billing_stateprov" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Postal Code</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" name="billing_postalcode" required>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Country Code</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="billing_countrycode"
                                                            id="billingCountryCodeSelect" required>
                                                        @foreach($countries as $country)
                                                            <option @if ($country->countrycode == 'US') selected @endif
                                                            data-tier3="{{$country->is_tier3}}"
                                                                    value="{{$country->countrycode}}">{{$country->country}}
                                                                ({{$country->countrycode}})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class="step row">
                        <div class="col-md-6 offset-md-3">
                            <div class="m-portlet">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                            <h3 class="m-portlet__head-text" id="billingInformationWithTotal">
                                                Enroll User
                                            </h3>
                                        </div>
                                    </div>
{{--                                    <div class="m-portlet__head-tools">--}}
{{--                                        <button type="button" class="btn btn-danger" id="btnPdf">PDF</button>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="m-form">
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__section--first m-form--label-align-right">
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Dist ID</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" disabled="disabled"
                                                           value="{{$distId}}">
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group row">
                                                <label class="col-md-4 col-form-label">Username</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="usernameCopy" disabled="disabled">
                                                </div>
                                            </div>
                                            <div class="m-form m-form__section--first m-form--label-align-right">
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Email</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="emailCopy" disabled="disabled">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Default Password</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="defaultPasswordCopy" disabled="disabled">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label">Total</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="totalInput" data-price="{{$standByPrice}}" value="${{number_format($standByPrice, 2)}}" disabled="disabled">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row">
                                                    <label class="col-md-4 col-form-label"></label>
                                                    <div class="col-md-8">
                                                        <button type="button" class="btn btn-success" id="btnEnroll">Enroll User</button>
                                                        <button type="button" class="btn btn-primary" id="btnComplete" style="display: none;">Complete / Reload</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <style>
        .step {
            display: none;
        }

        .step.active-step {
            display: flex;
        }
    </style>
@endpush
