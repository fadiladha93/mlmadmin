@extends('affiliate.layouts.main')

@section('main_content')
    <div class="m-content">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head our_head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Invoice
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body text-center">
                        <table class="table table-striped- table-bordered table-hover table-checkable">
                            <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Order Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->created_date}}</td>
                                    <td>{{$order->ordertotal}}</td>
                                    <td>
                                        <a href="{{url('/invoice/view/'.$order->id)}}" target="_blank"
                                           style="text-decoration: none;"><i class="la la-eye" title="View Invoice"></i>
                                        </a>
                                        <a href="{{url('/invoice/download/'.$order->id)}}" target="_blank"
                                           style="text-decoration: none;">
                                            <i class="la la-download" title="Download Invoice"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection