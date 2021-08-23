@extends('admin.layouts.main')
@section('main_content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Countries
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a class="btn btn-info btn-sm m-btn--air" href="{{url('add-country')}}">Add Country</a>&nbsp;
                </div>
            </div>
            <div class="m-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_countries">
                    <thead>
                    <tr>
                        <th>Payment method</th>
                        <th>Country name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
