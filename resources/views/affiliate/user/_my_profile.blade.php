@extends('affiliate.layouts.main')

@section('main_content')
<div class="row">
    <div class="col-md-4">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Basic Information
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmMyProfile" class="m-form">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">First Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="firstname" value="{{$rec->firstname}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Last Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="lastname" value="{{$rec->lastname}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Username</label>
                        <div class="col-md-8">
                            <input class="form-control" name="username" value="{{$rec->username}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Business Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="business_name" value="{{$rec->business_name}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Email</label>
                        <div class="col-md-8">
                            <input class="form-control" disabled="disabled" value="{{$rec->email}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Phone</label>
                        <div class="col-md-8">
                            <input class="form-control" name="phonenumber" value="{{$rec->phonenumber}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Mobile</label>
                        <div class="col-md-8">
                            <input class="form-control" name="mobilenumber" value="{{$rec->mobilenumber}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Payap Mobile</label>
                        <div class="col-md-8">
                            <input class="form-control" name="payap_mobile" value="{{$rec->payap_mobile}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button id="btnSaveProfile" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save basic information</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Primary credit card detail
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmMyPrimaryCard" class="m-form">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">First Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="first_name" value="{{$payment_method? $payment_method->firstname : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Last Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="last_name" value="{{$payment_method? $payment_method->lastname : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Credit card number</label>
                        <div class="col-md-8">
                            <input class="form-control" name="number">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">CVV</label>
                        <div class="col-md-8">
                            <input class="form-control" name="cvv" value="{{$payment_method? $payment_method->cvv : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Expiration Date</label>
                        <div class="col-md-8">
                            <input class="form-control" name="expiry_date" value="{{$expiry_date}}" placeholder="mm/yyyy">
                        </div>
                    </div>
                    <div class="form-group m-form__group row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button id="btnSavePrimaryCard" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save primary card detail</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Primary address
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmMyPrimaryAddress" class="m-form">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Address 1</label>
                        <div class="col-md-8">
                            <input class="form-control" name="address1" value="{{$primary_address? $primary_address->address1 : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Address 2</label>
                        <div class="col-md-8">
                            <input class="form-control" name="address2" value="{{$primary_address? $primary_address->address2 : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Apt / Suite</label>
                        <div class="col-md-8">
                            <input class="form-control" name="apt" value="{{$primary_address? $primary_address->apt : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">City</label>
                        <div class="col-md-8">
                            <input class="form-control" name="city" value="{{$primary_address? $primary_address->city : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">State / Province</label>
                        <div class="col-md-8">
                            <input class="form-control" name="stateprov" value="{{$primary_address? $primary_address->stateprov : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Postal Code</label>
                        <div class="col-md-8">
                            <input class="form-control" name="postalcode" value="{{$primary_address? $primary_address->postalcode : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Country Code</label>
                        <div class="col-md-8">
                            <select class="form-control" name="countrycode">
                                <option></option>
                                @foreach($countries as $c)
                                <?php
                                $selectedCountry = isset($primary_address) ? $primary_address->countrycode : "";
                                ?>
                                <option value="{{$c->countrycode}}" @if($selectedCountry == $c->countrycode) selected @endif>{{$c->country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button id="btnSaveAddress" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save primary address</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection