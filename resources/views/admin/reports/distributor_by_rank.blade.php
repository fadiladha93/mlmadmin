@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Lifetime Rank Report
                    </h3>
                </div>
            </div>
            @if (\App\AdminPermission::export_reports())
                <div class="m-portlet__head-tools">
                    <button class="btn btn-info btn-sm m-btn--air" id="exp_distributor_by_rank">Export</button>
                </div>
            @endif
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_distributor_by_rank">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Distributors</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
