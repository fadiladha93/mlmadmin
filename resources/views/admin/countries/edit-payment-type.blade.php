@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Update Payment Type for {{$country->country}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-success btn-sm m-btn--air" id="btnSaveCountryPaymentMethod">Save</button>&nbsp;
                    &nbsp;
                    <a class="btn btn-danger btn-sm m-btn--air" href="{{url('/countries')}}"
                       id="btnCancelCountryPaymentMethod">Cancel</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row" id="frmEditCountry">
                    @csrf
                    <div class="col-md-8">
                        <div class="m-form m-form__section">
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Country</label>
                                <div class="col-md-8">
                                    <input class="form-control" disabled value="{{$country->country}}">
                                    <input value="{{$country->id}}" name="country_id" type="hidden">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Payment Method Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="payment_method_type">
                                        <option value=""></option>
                                        <option value="t1" {{($country->payment_type=='NMI - T1'?'selected':'')}}>NMI -
                                            T1
                                        </option>
                                        <option
                                            value="trust_my_travel" {{($country->payment_type=='Trust my travel'?'selected':'')}}>
                                            Trust my travel
                                        </option>
                                    </select>
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
