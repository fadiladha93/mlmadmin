@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Add new distribuitor
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button class="btn btn-danger btn-sm m-btn--air" id="btnNewIntern">Save</button>&nbsp;
                <a href="{{url('/users/ambassadors')}}" class="btn btn-info btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmNewIntern">
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
                                        <label class="col-md-4 col-form-label">Business Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="business_name">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Phone</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="phonenumber">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Mobile</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="mobilenumber">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet">
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
                                <div class="m-form__section m-form__section--first">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 1</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="address1">
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
                                            <input class="form-control" name="city">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">State / Province</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="stateprov">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Postal Code</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="postalcode">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Country Code</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="countrycode">
                                                <option></option>
                                                @foreach($countries as $c)
                                                <option value="{{$c->countrycode}}">{{$c->country}}</option>
                                                @endforeach
                                            </select>
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
                                            <input class="form-control" readonly="readonly" name="distid" value="{{$new_tsa}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Product</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="current_product_id">
                                                <option></option>
                                                @foreach($enrollment_packs as $p)
                                                @if($p->id != \App\Product::ID_Traverus_Grandfathering)
                                                <option value="{{$p->id}}">{{$p->productname}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Account Status</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="account_status">
                                                <option>{{App\User::ACC_STATUS_PENDING}}</option>
                                                <option selected>{{App\User::ACC_STATUS_APPROVED}}</option>
                                                <option>{{App\User::ACC_STATUS_SUSPENDED}}</option>
                                                <option>{{App\User::ACC_STATUS_TERMINATED}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Email Verified</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="email_verified">
                                                <option value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Username</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="username">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Sponsor</label>
                                        <div class="col-md-8">
                                            <select class="form-control m-select2" id="select2_sponsor" name="sponsorid">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Added by</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$added_by}}">
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
                                <div class="m-form m-form__section--first m-form--label-align-right">
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
