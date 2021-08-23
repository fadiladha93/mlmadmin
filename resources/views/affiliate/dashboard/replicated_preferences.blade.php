@extends('affiliate.layouts.main')

@section('main_content')
@include('affiliate.user.my_profile_tab')

<div class="m-portlet m-portlet--mobile" id="replicatedPrefs">
    <div class="m-portlet__head our_head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Replicated Site Preferences
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body ri-wrapper">
        <div class="row">
            <div class="col-md-6">
                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Primary Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="primary_name" placeholder="Primary Name" value="{{$preferences['name']}}" readonly>
                        </div>
                    </div>
                </div>

                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Display Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="display_name" placeholder="Display Name" value="{{$preferences['displayed_name']}}">
                        </div>
                    </div>
                </div>

                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Email</label>
                        <div class="col-md-8">
                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{$preferences['email']}}">

                            <fieldset id="show_email"> Display on site?
                                @if($preferences['show_email'])
                                    <input type="radio" value="1" name="show_email" id="email_yes" checked><label for="email_yes"> Yes</label>
                                    <input type="radio" value="0" name="show_email" id="email_no"><label for="email_no"> No</label>
                                @else
                                    <input type="radio" value="1" name="show_email" id="email_yes"><label for="email_yes"> Yes</label>
                                    <input type="radio" value="0" name="show_email" id="email_no" checked><label for="email_no"> No</label>
                                @endif
                            </fieldset>
                        </div>
                    </div>
                </div>

                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Cell Phone</label>
                        <div class="col-md-8">
                            <input class="form-control" name="phone" placeholder="Cell Phone" value="{{$preferences['phone']}}">

                            <fieldset id="show_phone">Display on site?
                                @if($preferences['show_phone'])
                                    <input type="radio" value="1" name="show_phone" id="phone_yes" checked><label for="phone_yes"> Yes</label>
                                    <input type="radio" value="0" name="show_phone" id="phone_no"><label for="phone_no"> No</label>
                                @else
                                    <input type="radio" value="1" name="show_phone"><label for="phone_yes"> Yes</label>
                                    <input type="radio" value="0" name="show_phone" checked><label for="phone_no"> No</label>
                                @endif
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Co Applicant Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="co_name" placeholder="Co Applicant Name" value="{{$preferences['co_name']}}" readonly>
                        </div>
                    </div>
                </div>

                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Co App Display Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="co_display_name" placeholder="Co App Display Name" value="{{$preferences['co_display_name']}}"
                            @if($preferences['disable_co_app'])
                                readonly
                            @endif
                            >
                        </div>
                    </div>
                </div>

                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Business Name</label>
                        <div class="col-md-8">
                            <input class="form-control" name="business_name" placeholder="Business Name">
                        </div>
                    </div>
                </div>

                <div class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <div class="col-md-12">
                            <p class="m-widget4__text">
                                Name displayed on the replicated website:
                            </p>

                            <fieldset id="show_name">
                                @if($preferences['show_name'] == 3)
                                    <input type="radio" value="1" name="show_name" id="name"><label for="name">Display Name</label>
                                    @if(!$preferences['disable_co_app'])
                                        <input type="radio" value="2" name="show_name" id="co_name"><label for="co_name">Co Applicant Name</label>
                                    @endif
                                    <input type="radio" value="3" name="show_name" id="business_name" checked><label for="business_name">Business Name</label>
                                @elseif($preferences['show_name'] == 2)
                                    <input type="radio" value="1" name="show_name" id="name"><label for="name">Display Name</label>
                                    @if(!$preferences['disable_co_app'])
                                        <input type="radio" value="2" name="show_name" id="co_name" checked><label for="co_name">Co Applicant Name</label>
                                    @endif
                                    <input type="radio" value="3" name="show_name" id="business_name"><label for="business_name">Business Name</label>
                                @else
                                    <input type="radio" value="1" name="show_name" id="name" checked><label for="name">Display Name</label>
                                    @if(!$preferences['disable_co_app'])
                                        <input type="radio" value="2" name="show_name" id="co_name"><label for="co_name">Co Applicant Name</label>
                                    @endif
                                    <input type="radio" value="3" name="show_name" id="business_name"><label for="business_name">Business Name</label>
                                @endif
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center " style="margin-top: 15px;">
            <a href="/my-profile/replicated" class="btn btn-focus m-btn m-btn--block m-btn--air btn-warning">Cancel</a>
            <button id="btnSavePreferences" class="btn btn-focus m-btn m-btn--block m-btn--air btn-info">Save Changes</button>
        </div>
    </div>
</div>
@endsection
