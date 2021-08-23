@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        @if($rec == null)
                        Enrollments of {{$distid}}
                        @else
                        Enrollments of {{$rec->distid}} ({{$rec->username}})
                        @endif
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{url('/users/ambassadors')}}" class="btn btn-info btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="distid" style="display:none;">{{$distid}}</div>
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_enrollements">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Sponsor ID</th>
                        <th>Enrollment Pack</th>
                        <th>Account Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection