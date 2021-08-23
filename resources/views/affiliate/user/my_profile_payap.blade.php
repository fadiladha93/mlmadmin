@extends('affiliate.layouts.main')

@section('main_content')
@include('affiliate.user.my_profile_tab')
<div class="row">
    <div class="col-md-6 offset-3">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Payap
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                <form id="frmMyPayap" class="m-form m--align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-4 col-form-label">Payap Mobile</label>
                        <div class="col-md-8">
                            <input class="form-control" name="payap_mobile" value="{{$rec->payap_mobile}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row" style="margin-top:10px;">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <button id="btnSavePayap" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save Payap mobile</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection