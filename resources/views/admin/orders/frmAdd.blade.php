@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Add Order
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-danger btn-sm m-btn--air" id="btnAddOrder">Save Order</button>&nbsp;
                    {{--<a href="#" class="btn btn-info btn-sm m-btn--air">Update history</a>--}}
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row" id="frmAddOrder">
                    <div class="col-md-6">
                        <div class="m-form m-form__section--first m-form--label-align-right">
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Dist ID</label>
                                <div class="col-md-8">
                                    <select class="form-control m-select2" id="select2_sponsor" name="sponsorid">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order Date</label>
                                <div class="col-md-8">
                                    <input class="form-control date_picker2" name="created_date" placeholder="Order Date">
                                </div>
                            </div>
                            {{--<div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Distributor Name</label>
                                <div class="col-md-8">
                                    <input class="form-control" disabled="disabled" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Distributor Username</label>
                                <div class="col-md-8">
                                    <input class="form-control" disabled="disabled" value="">
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="m-form m-form__section--first m-form--label-align-right">
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order total</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="ordertotal" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order subtotal</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="ordersubtotal" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order BV</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="orderbv" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order QV</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="orderqv" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order CV</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="ordercv" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order QC</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="orderqc" value="">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Order AC</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="orderac" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection