@extends('affiliate.layouts.main')
@section('main_content')
    <div class="m-content">
        <div class="row">
            <div class="col-md-12">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head our_head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Weekly Binary Report
                                </h3>
                            </div>
                        </div>
                        <form id="frmBinaryViewDateRange" style="display: flex;">
                            <div class="m-portlet__head-tools">
                                <input class="form-control m-input form-control-sm date_picker2" id="d_from" name="d_from" placeholder="From Date" value="{{ $from != null ? $from : "" }}" />&nbsp;
                                <input class="form-control m-input form-control-sm date_picker2" id="d_to" name="d_to" placeholder="To Date" value="{{ $to != null ? $to : "" }}" />&nbsp;
                                <button class="btn btn-primary btn-sm m-btn--air" id="binaryViewDateRangeReportFiterBtn">View</button>&nbsp;
                            </div>
                        </form>
                    </div>
                    <div class="m-portlet__body">
                        <table id="dt_weekly_binary_view" class="table table-striped- table-bordered table-hover table-checkable">
                            <thead>
                            <tr>
                                <th>Dist ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>QV</th>
                                <th>Created Date</th>
                                <th>Binary Leg</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
