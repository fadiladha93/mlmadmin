@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Pre Enrollment Selection
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_pre_enrollments_selections">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>Product Name</th>
                        <th>iDecide User</th>
                        <th>Saveon User</th>
                        <th>Processed</th>
                        <th>Process Success</th>
                        <th>Process Message</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection