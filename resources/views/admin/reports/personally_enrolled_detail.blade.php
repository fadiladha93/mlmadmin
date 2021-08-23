@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Personally Enrolled Report of #{{$distid}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <input type="hidden" name="distid" value="{{$distid}}" id="distid">
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_enrolled_interns_admin">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Enrollment Pack</th>
                        <th>Account Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection