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
                            Basic Information
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmMyProfile" class="m-form m--align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">First Name</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="firstname" value="{{$rec->firstname}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Last Name</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="lastname" value="{{$rec->lastname}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Username</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly value="{{$rec->username}}">
                        </div>
                    </div>
{{--                    <div class="form-group m-form__group row">--}}
{{--                        <label class="col-md-4 col-form-label">Display Name</label>--}}
{{--                        <div class="col-md-8">--}}
{{--                            <input class="form-control" name="display_name" value="{{$rec->display_name}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Recognition Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="recognition_name" value="{{$rec->recognition_name}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Business Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="business_name" value="{{$rec->business_name}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Beneficiary</label>
                        <div class="col-md-8">
                            <input class="form-control" name="beneficiary" value="{{$rec->beneficiary}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Email</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly value="{{$rec->email}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Phone</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="phonenumber" value="{{$rec->phonenumber}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Mobile</label>
                        <div class="col-md-8">
                            <input class="form-control readonly_override" readonly name="mobilenumber" value="{{$rec->mobilenumber}}">
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
</div>

@endsection
