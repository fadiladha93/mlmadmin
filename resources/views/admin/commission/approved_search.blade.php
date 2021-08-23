@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Search for approved commission
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="frmSearchCommission">
                        <div class="col-md-3 offset-md-2">
                            <input class="form-control date_picker2" name="trans_date" placeholder="Transaction Date" value="{{$trans_date}}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control m-select2" id="approved_commission_date" name="approve_date" >
                                <option value="{{$approve_date}}">{{$approve_date}}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info m-btn--air btn-block" id="btnSearchCommission">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection