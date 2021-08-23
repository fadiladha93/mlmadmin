@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Edit Admin user
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{$back_to}}" class="btn btn-info btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmUpdateIntern">
                <input type="hidden" name="rec_id" value="{{$user->id}}" />
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        User Detail
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right" id="frmEditAdminUser">
                                    <input type="hidden" name="rec_id" value="{{$user->id}}" />
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">First Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="firstname" value="{{$user->firstname}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Last Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="lastname" value="{{$user->lastname}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Mobile Number</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="mobilenumber" value="{{$user->mobilenumber}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Phone Country Code</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="phone_country_code" value="{{$user->phone_country_code}}">
                                        </div>
                                    </div>
                                    @if($cs == 0)
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Role</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="admin_role">
                                                @if (\App\User::admin_super_admin())
                                                    <option @if($user->admin_role == App\UserType::ADMIN_SUPER_ADMIN) selected @endif value="{{App\UserType::ADMIN_SUPER_ADMIN}}">Super Admin</option>
                                                    <option @if($user->admin_role == App\UserType::ADMIN_SUPER_EXEC) selected @endif value="{{App\UserType::ADMIN_SUPER_EXEC}}">Super Exec</option>
                                                @endif
                                                <option @if($user->admin_role == App\UserType::ADMIN_SALES) selected @endif value="{{App\UserType::ADMIN_SALES}}">Sales</option>
                                                <option @if($user->admin_role == App\UserType::ADMIN_CS_EXEC) selected @endif value="{{App\UserType::ADMIN_CS_EXEC}}">CS Exec</option>
                                                <option @if($user->admin_role == App\UserType::ADMIN_CS_MGR) selected @endif value="{{App\UserType::ADMIN_CS_MGR}}">CS Manager</option>
                                                <option @if($user->admin_role == App\UserType::ADMIN_CS) selected @endif value="{{App\UserType::ADMIN_CS}}">CS</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label"></label>
                                        <div class="col-md-8">
                                            <a class="btn btn-success btn-sm m-btn--air" id="{{$saveBtnId}}">Save</a>
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
                                        Login Detail
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right" id="frmEditAdminUserLogin">
                                    <input type="hidden" name="rec_id" value="{{$user->id}}" />
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Email</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="email" value="{{$user->email}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Default Password</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="default_password" value="{{$user->default_password}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label"></label>
                                        <div class="col-md-8">
                                            <a class="btn btn-success btn-sm m-btn--air" id="{{$saveLoginBtnId}}">Save</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
