@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Edit Customer
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a class="btn btn-success btn-sm m-btn--air" style="color:#FFFFFF;" id="btnEditCustomer">Save</a>&nbsp;
                <a href="{{url('/dist-customers')}}" class="btn btn-dark btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmEditCustomer">
                <div class="col-md-12">
                    <div class="m-portlet">
                        <div class="m-form">
                            <div class="m-portlet__body">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <input type="hidden" name="id" value="{{$customer->id}}" />
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Customer Name</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="text" name="name" value="{{$customer->name}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Customer Email</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="text" name="email" value="{{$customer->email}}">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Mobile</label>
                                        <div class="col-md-8">
                                            <input class="form-control" type="text" name="mobile" value="{{$customer->mobile}}">
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