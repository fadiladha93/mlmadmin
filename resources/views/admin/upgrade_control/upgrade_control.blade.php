@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Upgrade Control
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="">
                <div class="col-md-12">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Set Countdown End Date Individually
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form" id="frmIndUpgradeDate">
                            <div class="m-portlet__body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Enter a new ending date for the Ambassador's upgrade timer</p>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-4">
                                        <label>Dist ID:</label>
                                        <select class="form-control m-select2" id="select2_sponsor" name="distid">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="">Countdown End Date:</label>
                                        <input name="coundown_expire_on" class="form-control m-input date_picker2" placeholder="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="">&nbsp;</label>
                                        <div style="width: 100%"><button class="btn btn-success" id="btnIndUpgrade">Save</button></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!$isCsExecOrManager)
                <div class="col-md-12">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Set Countdown End Date For Expired Date
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form" id='frmDistsExpUpgradeDate'>
                            <div class="m-portlet__body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Set a global expiration date for all Ambassador upgrade timers</p>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-4">
                                        <label class="">Countdown End Date:</label>
                                        <input name="coundown_expire_on" class="form-control m-input date_picker2" placeholder="">
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="">&nbsp;</label>
                                        <div style="width: 100%"><button class="btn btn-success" id="btnDistsUpgrade">Save</button></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
