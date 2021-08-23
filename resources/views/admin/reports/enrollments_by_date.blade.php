@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Enrollments by Date
                    </h3>
                </div>
            </div>
            <form id="frmEnrollmentByDateFilter" style="display: flex;">
                <div class="m-portlet__head-tools">
                    <select class="form-control m-input form-control-sm" name="type">
                        <option selected value="" disabled=""></option>
                        <option value="customers">Customers</option>
                        <option value="enrollments">Enrollments</option>
                    </select>
                    &nbsp;
                    <input class="form-control m-input form-control-sm date_picker2" id="d_from" name="d_from" placeholder="From Date" value="{{ $from != null ? $from : "" }}" />&nbsp;
                    <input class="form-control m-input form-control-sm date_picker2" id="d_to" name="d_to" placeholder="To Date" value="{{ $to != null ? $to : "" }}" />&nbsp;
                    <button class="btn btn-info btn-sm m-btn--air" id="enrollmentsByDateFilterBtn">View</button>&nbsp;
                    @if (\App\AdminPermission::export_reports())
                        <button class="btn btn-info btn-sm m-btn--air" id="exp_enrollments_by_date">Export</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_enrollments_by_date">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Created Date</th>
                        <th>Country Code</th>
                        <th>Sponsor ID</th>
                        <th>Enrollment Pack</th>
                        <th>Account Status</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>State/Province</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
