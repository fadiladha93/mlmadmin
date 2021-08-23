@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Subscription History
                    </h3>
                </div>
            </div>
            @if (App\AdminPermission::export_reports())
                <div class="m-portlet__head-tools">
                    <button class="btn btn-info btn-sm m-btn--air" id="exp_subscription_history">Export</button>
                </div>
            @endif
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_subscription_history">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>Status</th>
                        <th>Response</th>
                        <th>Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
