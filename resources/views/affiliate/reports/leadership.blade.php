@extends('affiliate.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="row">
        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Leadership Commission: ${{ $sum }}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                </div>
            </div>
            <div class="m-portlet__body">
                <table class="table m-table m-table--head-separator-info">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Paid Rank</th>
                        <th>Amount</th>
                        {{--<th></th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($commissions as $c)
                        <tr>
                            <td>{{ $c->order->order->user->firstname }} {{ $c->order->order->user->lastname }}</td>
                            <td>{{$c->percent * 100}}% Leadership Commission (Level {{ $c->level }}) for product (sku: {{ $c->order->product->sku }}) purchased with order ORD{{ $c->order->order->id }} on {{ $c->order->order->created_date }}</td>
                            <td>Level {{$c->level}}</td>
                            <td>${{$c->amount}}</td>
                            {{--<td>
                                <a href="#" class="leadership-details" data-toggle="modal" data-target="#orderDetails" data-id="{{ $c->order_id }}" data-date="{{ $c->end_date }}">
                                    <span><i class="la la-search"></i></span>
                                </a>
                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $commissions->appends(['date' => Request::get('date')])->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetails" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
@endsection
