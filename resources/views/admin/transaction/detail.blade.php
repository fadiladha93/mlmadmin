@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Transaction Detail
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <h5>Total : {{$tran->total}}</h5>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Customer Detail
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
                                            <input class="form-control" disabled="disabled" value="{{$customer->firstname}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Last Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->last_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Email</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->email}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Phone</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->phone}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 1</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->address1}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 2</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->address2}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">City</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->city}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">State</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->state}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">ZIP</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->zip}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Country</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$customer->country}}">
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
                                        Sponsor Detail
                                    </h3>
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
                                        Product Information
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">SKU</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->product_sku}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->product_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Price</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->product_price}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">CV</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->product_cv}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">PV</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->product_pv}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">QV</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->product_qv}}">
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
                                        Billing Information
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
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_firstname}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Last Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_last_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Phone</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_phone}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 1</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_address_1}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Address 2</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_address_2}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">City</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_city}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">State</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_state}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">ZIP</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_zip}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Country</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_country}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Fax</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_fax}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Credit card number</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_cc_number}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Credit card Expiry date</label>
                                        <div class="col-md-8">
                                            <input class="form-control" disabled="disabled" value="{{$tran->bill_cc_exp}}">
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