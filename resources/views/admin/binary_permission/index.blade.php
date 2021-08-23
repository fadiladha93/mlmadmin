@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Binary Permission
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a class="btn btn-info btn-sm m-btn--air" id="btnSaveBinaryPermission">Save</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="frmBinaryPermission" class="m-form m-form__section--first m-form--label-align-right">
                <div class="form-group m-form__group row">
                    <label class="col-md-2 col-form-label">Mode</label>
                    <div class="col-md-10">
                        <select class="form-control" name="mode">
                            <option @if($rec->mode == "Manual") selected @endif>Manual</option>
                            <option @if($rec->mode == "Automatic") selected @endif>Automatic</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-2 col-form-label">Permitted TSA</label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="12" name="permit_to">{{$rec->permit_to}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection