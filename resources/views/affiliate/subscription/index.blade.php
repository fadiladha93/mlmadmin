@extends('affiliate.layouts.main')

@section('main_content')
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="m-portlet" style="margin-top:20px;">
                <div class="m-portlet__head our_head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Subscription
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:15px;">
                    <div id="frmSubscription" class="m-form m-form--fit m-form--label-align-right">
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Subscription Level</label>
                            <div class="col-md-8">
                                {{--@if(Auth::user()->current_product_id == \App\Product::ID_NCREASE_ISBO)
                                    <input class="form-control" value="Not Applicable" disabled>
                                @else--}}
                                    <input class="form-control" value="{{$current_plan}}" disabled>
                                {{--@endif--}}
                            </div>
                            {{--<button type="button" class="btn btn-primary">Upgrade</button>--}}
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Next Billing Date</label>
                            <div class="col-md-8">
                                {{--@if(Auth::user()->current_product_id == \App\Product::ID_NCREASE_ISBO)
                                    <input class="form-control" value="Not Applicable" disabled>
                                @else--}}
                                    <input class="form-control date_picker3" name="next_subscription_date"
                                           id="next-subscription-date"
                                           value="{{$next_subscription_date}}" autocomplete="off">
                               {{-- @endif--}}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Payment Method</label>
                            <div class="col-md-8">
                                {{--@if(Auth::user()->current_product_id == \App\Product::ID_NCREASE_ISBO)
                                    <input class="form-control" value="Not Applicable" disabled>
                                @else--}}
                                    <select class="form-control" name="subscription_payment_method_id"
                                            id="subscription_payment_method_type_id">
                                        {!! $payment_method !!}
                                        @if(empty($subscription_card_added))
                                            <option value="0">Add New Card</option>
                                        @endif
                                    </select>
                                {{--@endif--}}
                            </div>
                        </div>
{{--                        <div class="form-group m-form__group row">--}}
{{--                            <label class="col-md-4 col-form-label">Status</label>--}}
{{--                            <div class="col-md-8">--}}
{{--                                @if(Auth::user()->is_sites_deactivate == 1 || Auth::user()->subscription_attempts == 1)--}}
{{--                                    @if(Auth::user()->is_sites_deactivate == 1)--}}
{{--                                        <label class="col-md-3 col-form-label m--font-danger" id="subscription-status"><strong>Inactive</strong></label>--}}
{{--                                    @elseif(Auth::user()->subscription_attempts == 1)--}}
{{--                                        <label class="col-md-3 col-form-label m--font-warning" id="subscription-status"><strong>Active</strong></label>--}}
{{--                                    @endif--}}

{{--                                    <a href="javascript:;" class="col-md-6 col-form-label m--font-success"--}}
{{--                                       id="reactivate-subscription">--}}
{{--                                        @if(Auth::user()->is_sites_deactivate == 1)--}}
{{--                                            Reactivate--}}
{{--                                        @elseif(Auth::user()->subscription_attempts == 1)--}}
{{--                                            Pay Now--}}
{{--                                        @endif--}}

{{--                                    </a>--}}
{{--                                @else--}}
{{--                                    <label class="col-md-3 col-form-label m--font-success" id="subscription-status"><strong>Active</strong></label>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        // TODO: Remove when display Active/Inactive status--}}
                        @if(Auth::user()->is_sites_deactivate == 1 || Auth::user()->subscription_attempts == 1)
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Status</label>
                                <div class="col-md-8">
                                    <label class="col-form-label m--font-danger" id="subscription-status"></label>
                                    <a href="javascript:;" class="col-md-6 col-form-label m--font-success"
                                       id="reactivate-subscription">
                                        @if(Auth::user()->is_sites_deactivate == 1)
                                            Reactivate
                                        @elseif(Auth::user()->subscription_attempts == 1)
                                            Pay Now
                                        @endif
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="form-group m-form__group row" style="margin-top:10px;">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <input type="hidden" name="gflag" id="gflag" value="{{$gflag}}"/>
                                    @if(Auth::user()->current_product_id != \App\Product::ID_NCREASE_ISBO)
                                        <button id="btnSaveSubscription"
                                                class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save
                                        </button>
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
