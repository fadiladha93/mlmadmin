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
                                Active Override
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-8 offset-md-4">
                            <p>This creates an order for $0, 0 CV, 100 QV, 0 BV and sets the Distribuitor to Active for one month.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form id="frmActiveOverride">
                                <div class="m-form m-form__section--first m-form--label-align-right">

                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">DistID / Username</label>
                                        <div class="col-md-8">
                                            <select class="form-control m-select2" id="select2_sponsor"
                                                name="distid">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label"></label>
                                        <div class="col-md-8">
                                            <a id="btnActiveOverride" class="btn btn-info btn-sm m-btn--air">Submit</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form id="frmActiveOverrideUpload" enctype="multipart/form-data">
                                @csrf
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label">Upload CSV</label>
                                        <div class="col-md-8">
                                            <input type="file" id="tsa_override_csv" name="tsa_override_csv" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-4 col-form-label"></label>
                                        <div class="col-md-8">
                                            <button type="submit" id="btnActiveOverrideUpload" class="btn btn-info btn-sm m-btn--air">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
