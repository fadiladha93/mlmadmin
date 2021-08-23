@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Distributors
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <label class="col-4 col-form-label" style="text-align: right;">Enrollment Packs</label>
                <select id="filterByEnrollmentpack" class="form-control m-input form-control-sm" name="filterByEnrollmentpack">
                    <option value="">All</option>
                    @foreach ($enrollment_packs as $name => $id)
                    <option value="{{$id}}">{{$name}}</option>
                    @endforeach
                </select>&nbsp;
                @if(\App\AdminPermission::fn_add_new_ambassador())
                <a href="{{url('/new-ambassador')}}" class="btn btn-danger btn-sm m-btn--air">Add new ambassador</a>&nbsp;
                @endif
                <a class="btn btn-info btn-sm m-btn--air" id="exp_intern">Export</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_interns">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Rank</th>
                        <th>Country</th>
                        <th>Enrollment Pack</th>
                        <th>Account Status</th>
                        <th>Phone</th>
                        <th>Enrollment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection