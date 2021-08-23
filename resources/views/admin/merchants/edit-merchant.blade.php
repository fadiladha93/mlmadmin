@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Update Payment Type for {{$merchant->pay_method_name}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-success btn-sm m-btn--air" id="btnSaveMerchantPaymentTypeAndLimits">Save</button>&nbsp;
                    &nbsp;
                    <a class="btn btn-danger btn-sm m-btn--air" href="{{url('/merchants')}}"
                       id="btnCancelMerchantPaymentTypeAndLimits">Cancel</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row" id="frmEditMerchant">
                    @csrf
                    <div class="col-md-8">
                        <div class="m-form m-form__section">
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Merchant</label>
                                <div class="col-md-8">
                                    <input class="form-control" disabled value="{{$merchant->pay_method_name}}">
                                    <input value="{{$merchant->id}}" name="id" type="hidden">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Payment Method Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="payment_method_type">
                                        <option value=""></option>
                                        <option value="" {{($merchant->type == '' ? 'selected' : '')}}>Not Set</option>
                                        <option
                                            value="CC" {{($merchant->type == 'CC' ? 'selected' : '')}}>
                                            Credit Card
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Coach Limit</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="payment_method_limit_coach" value="{{$merchant->limit_coach}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Business Limit</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="payment_method_limit_business_class" value="{{$merchant->limit_business_class}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">First Class Limit</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="payment_method_limit_first_class" value="{{$merchant->limit_first_class}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Do not include commas or dollar signs in values: example 6000.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
