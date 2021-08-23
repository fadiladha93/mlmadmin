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
                            iDecide - Change Email
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmIdecideEmail" class="m-form m--align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">New email</label>
                        <div class="col-md-8">
                            <input class="form-control" name="idecide_email" value="">
                        </div>
                    </div>
                    <div class="form-group m-form__group row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button id="btnSaveIdecideEmail" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save new email</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            iDecide - Change Password
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmIdecidePassword" class="m-form m--align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">New password</label>
                        <div class="col-md-8">
                            <input class="form-control" name="idecide_new_pass" value="">
                        </div>
                    </div>
                    <div class="form-group m-form__group row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button id="btnSaveIdecidePassword" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save new password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection