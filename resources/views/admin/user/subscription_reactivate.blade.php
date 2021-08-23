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
                                    Subscription Reactivate
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-md-8 offset-md-4">
                                <p>Toggle the Reactivate button on or off for the selected Distribuitor</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- This form post to itself and picks up the route via GET method -->
                                <form id="frmSubscriptionReactivate">
                                    <div class="m-form m-form__section--first m-form--label-align-right">
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">TSA</label>
                                            <div class="col-md-8">
                                                <select class="form-control m-select2" id="select3_sponsor" name="distid">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Mode</label>
                                            <div class="col-md-8">
                                                <select class="form-control" id="reactivateMode" name="mode">
                                                    <option value=""></option>
                                                    <option value="on">On</option>
                                                    <option value="off">Off</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-8">
                                                <button type="button"
                                                   class="btn btn-info btn-sm m-btn--air" id="btnAdminSubscriptionReactivateSubmitButton">Submit</button>
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
