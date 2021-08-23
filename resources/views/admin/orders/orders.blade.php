@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Orders [ Total : {{$total_amount}} | CV : {{$total_cv}}]
                    </h3>
                </div>
            </div>
            <form id="frmOrdersByDateFilter" style="display: flex;">
                <div class="m-portlet__head-tools">
                    <input class="form-control m-input form-control-sm date_picker2" id="d_from" name="d_from" placeholder="From Date" value="{{ $from != null ? $from : "" }}" />&nbsp;
                    <input class="form-control m-input form-control-sm date_picker2" id="d_to" name="d_to" placeholder="To Date" value="{{ $to != null ? $to : "" }}" />&nbsp;
                    <button class="btn btn-info btn-sm m-btn--air" id="ordersByDateFilterBtn">View</button>&nbsp;
                    <button class="btn btn-info btn-sm m-btn--air" id="exp_orders_by_date">Export</button>&nbsp;
                    @if(App\AdminPermission::add_edit_refund_orders_and_order_items())
                        <a class="btn btn-danger btn-sm m-btn--air" href="{{url('add-order')}}">Add Order</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_orders">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Dist ID</th>
                        <th>Order Status</th>
                        <th>Transaction ID</th>
                        <th>Order Subtotal</th>
                        <th>Order Total</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
