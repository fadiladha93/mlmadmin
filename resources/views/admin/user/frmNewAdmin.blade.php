@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                {{$title}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a class="btn btn-danger btn-sm m-btn--air" id="btnAddAdminUser">Save</a>&nbsp;
                        <a href="{{$back_to}}" class="btn btn-info btn-sm m-btn--air">Back</a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="frmNewAdmin">
                        <div class="col-md-12">
                            <div class="m-form m-form__section--first m-form--label-align-right">
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">First Name</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="firstname">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Last Name</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="lastname">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Email</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="email">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Default Password</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="default_password">
                                    </div>
                                </div>
                                @if($cs == 0)
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Role</label>
                                    <div class="col-md-8">
                                        <select class="form-control" name="admin_role">
                                            @if (\App\User::admin_super_admin())
                                                <option value="{{App\UserType::ADMIN_SUPER_ADMIN}}">Super Admin</option>
                                                <option value="{{App\UserType::ADMIN_SUPER_EXEC}}">Super Exec</option>
                                            @endif
                                            <option value="{{App\UserType::ADMIN_SALES}}">Sales</option>
                                            <option value="{{App\UserType::ADMIN_CS_EXEC}}">CS Exec</option>
                                            <option value="{{App\UserType::ADMIN_CS_MGR}}">CS Manager</option>
                                            <option value="{{App\UserType::ADMIN_CS}}">CS</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
