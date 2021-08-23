@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Distributors Detail
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="#" class="btn btn-info btn-sm m-btn--air showDlgHistory"  tag2="update-history" tag="{{url("/dlg-update-history/USER/".$rec->id)}}">Update history</a>&nbsp;
                <a href="#" class="btn btn-info btn-sm m-btn--air login-as-ambassador" data-distid="{{$rec->distid}}">Login as this distribuitor</a>&nbsp;
                <button class="btn btn-success btn-sm m-btn--air" style="color:#FFFFFF;" id="btnUpdateIntern">Save</button>&nbsp;
                <a href="{{url('/users/ambassadors')}}" class="btn btn-dark btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmUpdateIntern">
                <input type="hidden" name="rec_id" value="{{$rec->id}}" />
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
                                        <label class="col-md-4 col-form-label">Business Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="business_name" value="{{$rec->business_name}}">
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
                                        Co-Applicant Information
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                <button class="btn btn-info btn-sm m-btn--air" id="btnClearCoApplicantInfo" {{!$canEditCoApplicantForm ? 'disabled' : ''}}>Clear Info</button>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="co_applicant_name" id="inputCoApplicantName"
                                                   value="{{$rec->co_applicant_name}}" {{!$canEditCoApplicantForm  ? 'disabled' : ''}}>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Email</label>
                                        <div class="col-md-8">
                                            <input type="email" class="form-control" name="co_applicant_email" id="inputCoApplicantEmail"
                                                   value="{{$rec->co_applicant_email}}" {{!$canEditCoApplicantForm  ? 'disabled' : ''}}>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Phone</label>
                                        <div class="col-md-8">
                                            <input type="tel" class="form-control" name="co_applicant_phone_number" id="inputCoApplicantPhone"
                                                   value="{{$rec->co_applicant_phone_number}}" {{!$canEditCoApplicantForm  ? 'disabled' : ''}}>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Mobile</label>
                                        <div class="col-md-8">
                                            <input type="tel" class="form-control" name="co_applicant_mobile_number" id="inputCoApplicantMobile"
                                                   value="{{$rec->co_applicant_mobile_number}}" {{!$canEditCoApplicantForm  ? 'disabled' : ''}}>
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
                                        Primary Address
                                    </h3>
                                </div>
                            </div>
                            @if(isset($primary_address))
                                <div class="m-portlet__head-tools">
                                    <a href="#" class="btn btn-info btn-sm m-btn--air showDlgHistory"  tag2="update-history" tag="{{url("/dlg-update-history/ADDRESS/".$primary_address->id)}}">Update history</a>
                                </div>
                            @endif
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 1</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="address1" value="{{isset($primary_address) ? $primary_address->address1 : ""}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 2</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="address2" value="{{isset($primary_address) ? $primary_address->address2 : ""}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Apt / Suite</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="apt" value="{{isset($primary_address) ? $primary_address->apt : ""}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">City</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="city" value="{{isset($primary_address) ? $primary_address->city : ""}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">State / Province</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="stateprov" value="{{isset($primary_address) ? $primary_address->stateprov : ""}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Postal Code</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="postalcode" value="{{isset($primary_address) ? $primary_address->postalcode : ""}}">
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
                                            <input class="form-control" name="email" value="{{$rec->email}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Default Password</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="default_password" value="{{$rec->default_password}}">
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
                                        Subscription Product
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form">                            
                            <div class="m-portlet__body" style="padding-bottom:65px">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <form id="frmSubscriptionProduct">
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Subscription Product</label>
                                            <div class="col-md-8">
                                                
                                                    <select class="form-control" name="subscription_product">
                                                        <option value="" {{(empty($rec->subscription_product)?'selected':'')}}></option>
                                                        @foreach($subscription_products as $subscription_product)
                                                            <option
                                                                value="{{$subscription_product->id}}" {{($rec->subscription_product == $subscription_product->id?'selected':'')}}>{{$subscription_product->productname}}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="distid" value="{{$rec->distid}}">
                                                
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Subscription Date</label>
                                            <div class="col-md-8">
                                                <input class="datepicker form-control"  type="text"  name="subscription_product_date"
                                                    value="{{(!empty($rec->next_subscription_date)?$rec->next_subscription_date:'')}}">
                                            </div>
                                        </div>
                                    </form>
                                    <div class="form-group m-form__group">
                                        <div class="col-md-3 pull-right">
                                            <button id="btnUpdateSubscriptionProduct"
                                                    class="btn btn-info m-btn--air btn-block">Update
                                            </button>
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
                                            <input class="form-control" disabled="disabled" value="{{$rec->distid}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Current Package</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{\App\Product::getProductName($rec->current_product_id)}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Account Status</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="account_status">
                                                <option value="{{App\User::ACC_STATUS_PENDING}}" @if($rec->account_status == App\User::ACC_STATUS_PENDING) selected @endif >{{App\User::ACC_STATUS_PENDING}}</option>
                                                <option value="{{App\User::ACC_STATUS_APPROVED}}" @if($rec->account_status == App\User::ACC_STATUS_APPROVED) selected @endif>{{App\User::ACC_STATUS_APPROVED}}</option>
                                                <option value="{{App\User::ACC_STATUS_SUSPENDED}}"  @if($rec->account_status == App\User::ACC_STATUS_SUSPENDED) selected @endif>{{App\User::ACC_STATUS_SUSPENDED}}</option>
                                                <option value="{{App\User::ACC_STATUS_TERMINATED}}"  @if($rec->account_status == App\User::ACC_STATUS_TERMINATED) selected @endif>{{App\User::ACC_STATUS_TERMINATED}}</option>
                                                <option value="{{App\User::ACC_STATUS_PENDING_APPROVAL}}" @if($rec->account_status == App\User::ACC_STATUS_PENDING_APPROVAL) selected @endif >{{App\User::ACC_STATUS_PENDING_APPROVAL}}</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Email Verified</label>
                                        <div class="col-md-8">
                                            <select class="form-control" name="email_verified">
                                                <option @if($rec->email_verified == 0) selected @endif value="0">No</option>
                                                <option @if($rec->email_verified == 1) selected @endif value="1">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Username</label>
                                        <div class="col-md-8">
                                            <input class="form-control" name="username" value="{{$rec->username}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Sponsor</label>
                                        <div class="col-md-8">
                                            <select class="form-control m-select2" id="select2_sponsor" name="sponsorid">
                                                <option value="{{$rec->sponsorid}}">{{$sponsor}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Added by</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$entered_by}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Enrollment Date</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled"
                                                   value="{{ $rec->created_dt ? \Carbon\Carbon::createFromTimeString($rec->created_dt)->toDateString() : ""}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Lifetime Rank</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$lifetime_rank}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Remarks</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" name="remarks">{{$rec->remarks}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class=col-md-6>
                                          <button id="btnRemoveFromMailgun" class="btn btn-success btn-block m-btn--air">Remove From Mailing List
                                            </button>
                                        </div>
                                        <div class=col-md-6>
                                                <button id="btnResendWelcomeEmail" class="btn btn-info btn-block m-btn--air">Resend Welcome Email
                                                </button>
                                            
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
                                        Site Agreements
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <div class="form-group m-form__group row">
                                        {{-- <label class="col-md-3 col-form-label">iDecide Agreed At</label>
                                        <div class="col-md-9">
                                            <input class="form-control" disabled="disabled"
                                                   value="{{(!empty($site_agreement->agreed_idecide_at)?$site_agreement->agreed_idecide_at:'')}}">
                                        </div> --}}
                                    </div>
                                    <div class="form-group m-form__group row">
                                        {{-- <label class="col-md-3 col-form-label">iGo Agreed At</label>
                                        <div class="col-md-9">
                                            <input class="form-control" disabled="disabled"
                                                   value="{{(!empty($site_agreement->agreed_sor_at)?$site_agreement->agreed_sor_at:'')}}">
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    {{-- <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        iDecide
                                    </h3> --}}
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    {{-- <div class="form-group m-form__group row">
                                        <label class="col-md-3 col-form-label">User ID</label>
                                        <div class="col-md-9">
                                            <input class="form-control" disabled="disabled" value="{{$idecideUserId}}">
                                        </div>
                                    </div> --}}
                                    {{-- @if(!$idecideAccountFound)
                                        <div class="pull-right" id="frmIdecide">
                                            <input type="hidden" name="user_id" value="{{$rec->id}}"/>
                                            <button id="btnCreateIDecide" class="btn btn-info btn-sm m-btn--air">Create
                                                iDecide Account
                                            </button>
                                        </div>
                                        <div class="clearfix"></div>
                                    @else
                                        <div class="form-group m-form__group row" id="frmIdecideAccountStatus">
                                            <input type="hidden" name="user_id" value="{{$rec->id}}"/>
                                            <label class="col-md-3 col-form-label">Status</label>
                                            <div class="col-md-5">
                                                <input class="form-control" disabled="disabled"
                                                       value="{{$idecideAccountStatus}}">
                                            </div>
                                            <div class="col-md-4">
                                                <button id="btnToggleIDecideStatus"
                                                        class="btn btn-danger m-btn--air btn-block">Toggle
                                                </button>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    {{-- <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        SOR
                                    </h3> --}}
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    {{-- <div class="form-group m-form__group row">
                                        <label class="col-md-3 col-form-label">User ID</label>
                                        <div class="col-md-9">
                                            <input class="form-control" disabled="disabled" value="{{$sorUserId}}">
                                        </div>
                                    </div> 
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-3 col-form-label">Package</label>
                                        <div class="col-md-9">
                                            <input class="form-control" disabled="disabled" value="{{$sorPackageName}}">
                                        </div>
                                    </div> --}}
                                    <div class="form-group m-form__group row" id="frmSorTransfer">
                                        <input type="hidden" name="user_id" value="{{$rec->id}}" />
                                        {{-- @if($sorAccountFound)
                                            <label class="col-md-3 col-form-label">Transfer To</label>
                                        @else
                                            <label class="col-md-3 col-form-label">Add To</label>
                                        @endif --}}
                                        <div class="col-md-5">
                                            {{-- <select class="form-control" name="sor_transfer_to">
                                                <option></option>
                                                <option value="1">iGo4less0</option>
                                                <option value="2">iGo4less1</option>
                                                <option value="3">iGo4less2</option>
                                                <option value="4">iGo4less3</option>
                                            </select> --}}
                                        </div>
                                        <div class="col-md-4">
                                            {{-- @if($sorAccountFound)
                                                <button id="btnSorTransfer" class="btn btn-info m-btn--air btn-block">
                                                    Transfer
                                                </button>
                                            @else
                                                <button id="btnSorTransfer" class="btn btn-info m-btn--air btn-block">
                                                    Add New
                                                </button>
                                            @endif --}}
                                        </div>
                                    </div>
                                    {{-- @if($sorAccountFound)
                                        <div class="form-group m-form__group row" id="frmSORAccountStatus">
                                            <input type="hidden" name="user_id" value="{{$rec->id}}"/>
                                            <label class="col-md-3 col-form-label">Status</label>
                                            <div class="col-md-5">
                                                <input class="form-control" disabled="disabled"
                                                       value="{{$sorAccountStatus}}">
                                            </div>
                                            <div class="col-md-4">
                                                <button id="btnToggleSORStatus"
                                                        class="btn btn-danger m-btn--air btn-block">Toggle
                                                </button>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    {{-- <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Boomerangs
                                    </h3> --}}
                                </div>
                            </div>
                            {{-- @if($boom_id > 0)
                                <div class="m-portlet__head-tools">
                                    <a href="#" class="btn btn-info btn-sm m-btn--air showDlgHistory"
                                       tag2="update-history"
                                       tag="{{url("/dlg-update-history/BOOMERANG_INV/".$boom_id)}}">Update history</a>
                                </div>
                            @endif --}}
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    {{-- <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Total Boomerang</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$boom_total}} (Available: {{$boom_available}} | Pending: {{$boom_pending}})">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="form-group m-form__group row" id="frmBoomerang">
                                        <input type="hidden" name="user_id" value="{{$rec->id}}" />
                                        <label class="col-md-4 col-form-label">New Total Boomerang</label>
                                        <div class="col-md-5">
                                            <input class="form-control" name="new_boomerang">
                                        </div>
                                        <div class="col-md-3">
                                            <a id="btnUpdateBoomerang" class="btn btn-info m-btn--air btn-block">Update</a>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="form-group m-form__group row" id="frmMaxBoomerang">
                                        <input type="hidden" name="user_id" value="{{$rec->id}}" />
                                        <label class="col-md-4 col-form-label">Max Boomerangs Available</label>
                                        <div class="col-md-5">
                                            <input class="form-control" name="max_available" value="{{$boom_max_available}}">
                                        </div>
                                        <div class="col-md-3">
                                            <a id="btnUpdateMaxBoomerang" class="btn btn-info m-btn--air btn-block">Update</a>
                                        </div>
                                    </div> --}}
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
@push('scripts')
    <script src="{{asset('/assets/js/login-as-ambassador.js')}}" type="text/javascript"></script>
@endpush