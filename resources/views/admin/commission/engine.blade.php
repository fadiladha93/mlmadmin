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
                                Commission Engine
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="frmRunCommission">
                        <div class="col-md-3 offset-md-2">
                            <input class="form-control date_picker2" name="from" placeholder="From Date" value="{{$from}}">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control date_picker2" name="to" placeholder="To Date" value="{{$to}}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info m-btn--air btn-block" id="btnRunCommission">Run</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Unilevel commission
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="uniRunCommission">
                        <div class="col-md-3  offset-md-2">
                            <input class="form-control date_picker2" name="from" placeholder="From Date" value="{{$from}}">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control date_picker2" name="to" placeholder="To Date" value="{{$to}}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info m-btn--air btn-block" id="btnUniRunCommission">Run</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Leadership commission
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="leadershipCommission">
                        <div class="col-md-3  offset-md-2">
                            <input class="form-control date_picker2" name="from" placeholder="From Date" value="{{$from}}">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control date_picker2" name="to" placeholder="To Date" value="{{$to}}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info m-btn--air btn-block" id="btnLeadershipCommission">Run</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                TSB commission
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" id="tsb_commission">
                        <div class="col-md-3  offset-md-2">
                            <input class="form-control date_picker2" name="from" placeholder="From Date" value="{{$from}}">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control date_picker2" name="to" placeholder="To Date" value="{{$to}}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info m-btn--air btn-block" id="btnTSBCommission">Run</button>
                        </div>
                    </div>
                    <hr>
                    <form enctype="multipart/form-data" id="frmTsbCommissionImport">
                     @csrf
                        <div class="row" id="tsb_commission">
                            <div class="col-md-4  offset-md-2">
                                <input type="file" class="form-control" name="tsb_commissions_csv" id="tsb_commissions_csv" required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success m-btn--air btn-block" type="submit" id="frmTsbCommissionImportBtn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
