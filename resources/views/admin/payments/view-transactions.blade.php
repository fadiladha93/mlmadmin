@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                    Transactions for the Token {{ $first }} ###### {{ $last }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Order Status</th>
                        <th>Transaction ID</th>
                        <th>Order Subtotal</th>
                        <th>Order Total</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }} </td>
                            <td>{{ $order->user->firstname }} {{ $order->user->lastname }} </td>
                            <td>{{ $order->status_desc }} </td>
                            <td>{{ $order->trasnactionid }} </td>
                            <td>{{ $order->ordersubtotal }} </td>
                            <td>{{ $order->ordertotal }} </td>
                            <td>{{ $order->created_dt }} </td>
                            <td>
                                <a class="btn btn-danger btn-sm m-btn--air showDlg_s" href="/edit-order/{{ $order->id}}"><i class="la la-edit"></i> Edit Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
