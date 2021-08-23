@extends('affiliate.layouts.main')

@section('main_content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Change Password
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmChangePass" class="m-form">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Current Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="current_pass">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">New Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="pass_1">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Re-enter New Password</label>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="pass_2">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label"></label>
                        <div class="col-md-8">
                            <a id="btnChangePass" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-success">Change Password</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection