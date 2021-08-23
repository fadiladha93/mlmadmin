@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Voucher Codes
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{url('/new-discount-coupon')}}" class="btn btn-danger btn-sm m-btn--air">Add new voucher code</a>&nbsp;
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_discounts">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th style="width:16%;">Discount amount</th>
                        <th>Is used</th>
                        <th>Is Active</th>
                        <th>Used By</th>
                        <th style="width:14%;">Generated For</th>
                        <th>Created at</th>
                        <th style="width:20%;">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection