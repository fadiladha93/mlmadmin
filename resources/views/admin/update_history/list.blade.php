@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Update History - {{$title}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <input type="hidden" id="his_type" value="{{$type}}" />
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_update_history">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mode</th>
                        <th>Before Update</th>
                        <th>After Update</th>
                        <th>Created at</th>
                        <th>Updated By</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
