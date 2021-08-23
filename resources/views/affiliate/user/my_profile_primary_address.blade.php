@extends('affiliate.layouts.main')

@section('main_content')
@include('affiliate.user.my_profile_tab')
<div class="row">
    <div class="col-md-6 offset-3">
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
                <form id="frmMyPrimaryAddress" class="m-form m--align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Address 1</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="address1" value="{{$primary_address? $primary_address->address1 : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Address 2</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="address2" value="{{$primary_address? $primary_address->address2 : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Apt / Suite</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="apt" value="{{$primary_address? $primary_address->apt : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">City</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="city" value="{{$primary_address? $primary_address->city : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">State / Province</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="stateprov" value="{{$primary_address? $primary_address->stateprov : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Postal Code</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="postalcode" value="{{$primary_address? $primary_address->postalcode : ''}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Country Code</label>
                        <div class="col-md-8">
{{--                            <select class="form-control readonly_override" readonly name="countrycode">--}}
                            <select class="form-control" disabled name="countrycode">

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
{{--                    <div class="form-group m-form__group row" style="margin-top:10px;">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="pull-right">--}}
{{--                                <button id="btnSaveAddress" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save primary address</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
