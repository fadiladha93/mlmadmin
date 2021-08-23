@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Personally Enrolled Report
                    </h3>
                </div>
            </div>
            @if (\App\AdminPermission::export_reports())
                <div class="m-portlet__head-tools">
                    <a class="btn btn-info btn-sm m-btn--air" id="exp_personal_enrollments">Export</a>
                </div>
            @endif
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_personal_enrollments">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Personally Enrolled Distributors</th>
                        <th>Enrollment Pack</th>	
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
