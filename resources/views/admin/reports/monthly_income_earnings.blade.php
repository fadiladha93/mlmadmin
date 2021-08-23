@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Monthly Income earnings from the date of enrollment
                    </h3>
                </div>
            </div>
            <form id="frmMonthlyIncomeFilter" style="display: flex;">
                <div class="m-portlet__head-tools">
                    <select class="form-control m-input form-control-sm" name="year" id="year">
                        @foreach ($order_years as $year)
                        <option value="{{$year->year}}" {{ $year->year == date("Y") ? "selected" : "" }}>{{$year->year}}</option>
                        @endforeach
                    </select>&nbsp;
                    <select class="form-control m-input form-control-sm" name="month" id="month">
                        @foreach ($order_months as $m => $month)
                        <option value="{{$m}}" {{ $m == date("m") ? "selected" : "" }}>{{$month}}</option>
                        @endforeach
                    </select>&nbsp;
                    <button class="btn btn-info btn-sm m-btn--air" id="btnFilterMonthlyEarnings">View</button>&nbsp;
                    @if (\App\AdminPermission::export_reports())
                        <button class="btn btn-info btn-sm m-btn--air" id="exp_monthly_income_earnings">Export</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_monthly_income_earnings">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Monthly Amount</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
