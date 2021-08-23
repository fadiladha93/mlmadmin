@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Rank Advancement Report
                    </h3>
                </div>
            </div>
            <form id="frmRankAdvancementFilter" style="display: flex;">
                <div class="m-portlet__head-tools">
                    <select class="form-control m-input form-control-sm" name="rank" id="rank">
                        <option value="">All</option>
                        @foreach ($ranks as $rank)
                        <option value="{{$rank->rankval}}">{{$rank->rankdesc}}</option>
                        @endforeach
                    </select>&nbsp;-&nbsp;
                    <select class="form-control m-input form-control-sm" name="year" id="year">
                        <option value="">All</option>
                        @foreach ($years as $year)
                        <option value="{{$year}}" {{ $year == date("Y") ? "selected" : "" }}>{{$year}}</option>
                        @endforeach
                    </select>&nbsp;
                    <select class="form-control m-input form-control-sm" name="month" id="month">
                        <option value="">All</option>
                        @foreach ($months as $m => $month)
                        <option value="{{$m}}" {{ $m == date("m") ? "selected" : "" }}>{{$month}}</option>
                        @endforeach
                    </select>&nbsp;
                    <button class="btn btn-info btn-sm m-btn--air" id="rankAdvancementFilterBtn">View</button>&nbsp;
                    @if (\App\AdminPermission::export_reports())
                        <button class="btn btn-info btn-sm m-btn--air" id="exp_rank_advancement">Export</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_rank_advancement_report">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Achived Rank</th>
                        <th>Country</th>
                        <th>Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
