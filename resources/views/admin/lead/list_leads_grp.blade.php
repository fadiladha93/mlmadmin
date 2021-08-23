@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Group Boomerangs
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a class="btn btn-info btn-sm m-btn--air" id="exp_admin_leads_grp">Export</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_admin_leads_grp">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>Campaign Name</th>
                        <th>Number of uses</th>
                        <th>Available</th>
                        <th>Boomerang Code</th>
                        <th>Date Created</th>
                        <th>Expiration date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection