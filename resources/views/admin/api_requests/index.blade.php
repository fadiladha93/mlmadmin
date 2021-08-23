@extends('admin.layouts.main')
@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        API Requests
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_api_requests">
                <thead>
                    <tr>
                        <th>Request On</th>
                        <th>Request</th>
                        <th>Status</th>
                        <th>Token</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
