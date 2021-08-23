@extends('admin.layouts.main')
@section('main_content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Merchants
                        </h3>
                    </div>
                </div>
                {{--  ADD MERCHANT CAN GO HERE IN THE FUTURE  --}}
                {{--                <div class="m-portlet__head-tools">--}}
{{--                    <a class="btn btn-info btn-sm m-btn--air" href="{{url('add-country')}}">Add Country</a>&nbsp;--}}
{{--                </div>--}}
            </div>
            <div class="m-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_merchants">
                    <thead>
                    <tr>
                        <th>Payment method</th>
                        <th>Type</th>
                        <th>Coach Limit</th>
                        <th>Business Limit</th>
                        <th>First Class Limit</th>
{{--                        <th>All Limit</th> --}}
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
