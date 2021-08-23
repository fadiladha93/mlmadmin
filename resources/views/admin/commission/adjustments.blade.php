@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Adjustments
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a class="btn btn-success btn-sm m-btn--air" id="btnAdjustments">Adjust</a>&nbsp;
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="frmAdjustments">
                        <div class="col-md-12">
                            <div class="m-form m-form__section--first m-form--label-align-right">
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Dist ID</label>
                                    <div class="col-md-8">
                                        <select class="form-control m-select2" id="select2_sponsor"
                                                name="sponsorid">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Adjustment amount</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id='amount' name="amount">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Note</label>
                                    <div class="col-md-8">
                                       <textarea class="form-control" name="note"></textarea>
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