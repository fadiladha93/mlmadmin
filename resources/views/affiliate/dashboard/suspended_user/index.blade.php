@extends('affiliate.layouts.main')
@section('main_content')
    <div class="m-content">
        <div class="row" style="margin-top:20px;">
            <div class="col-lg-12">
                <div class="m-portlet m-portlet--mobile" id="divBusinessSnapshot">
                    <div class="m-portlet__head our_head" id="divBusinessSnapshotHead">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title" id="subscription-reactivation-title">
                                <h5 class="m-portlet__head-text">
                                    Your payment did not successfully run with your current payment method. Your access has been temporarily restricted. Please add an alternative payment method to reactivate your subscription.
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body ri-wrapper">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h4> Would you like to reactivate your account?</h4>
                                <br>
                                <button id="reactivate-subscription-suspended-user"
                                        class="btn m-btn m-btn--air btn-info">Yes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
