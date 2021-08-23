@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Highest Achieved Rank
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <input class="form-control m-input form-control-sm date_picker2" id="d_from" placeholder="From Date" value="{{ $from != null ? $from : "" }}" />&nbsp;
                <input class="form-control m-input form-control-sm date_picker2" id="d_to" placeholder="To Date" value="{{ $to != null ? $to : "" }}" />&nbsp;
                <button class="btn btn-info btn-sm m-btn--air" id="highestAchievedRankFilterBtn">View</button>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_highest_achieved_rank">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Achived Rank</th>
                        <th>Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection