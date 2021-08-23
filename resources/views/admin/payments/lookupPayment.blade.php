@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Payment Lookup
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <!-- <a class="btn btn-primary btn-sm m-btn--air" id="exp_paymentLookup">Export</a> -->
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-12">
                    <form class="form-inline" method="post" action="{{ url('payment-lookup') }}">
                        {{ csrf_field() }}
                        <input type="text" class="form-control mb-1 mr-sm-1" placeholder="First 6 CC digits" name="numbers6" value="{{ $query['numbers6'] ?? "" }}" maxlength="6">
                        <input type="text" class="form-control mb-1 mr-sm-1" placeholder="Last 4 CC digits" name="numbers4" value="{{ $query['numbers4'] ?? "" }}" maxlength="4">

                        <button type="submit" class="btn btn-primary mb-1">Search <i class="fa fa-search"></i></button>&nbsp;

                        @if (isset($payments) && $payments->sum('orders_count') > 0)
                            <a class="btn btn-warning mb-1" href="{{ route('payment-lookup-all-transactions', ['first' => $query['numbers6'], 'last' => $query['numbers4'], 'id' => 'null']) }}">
                            View All Transactions <span class="badge badge-info">{{ $payments->sum('orders_count') }}</span></a>
                        @endif
                </form>
                </div>
            </div>
            <div class="table-responsive ">
                <table class="table table-hover table-fixed" id="paymentLookup">
                    <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>User ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Sponsors ID</th>
                            <th>Card Token</th>
                            <th>Expiration Date</th>
                            <th>Created Date</th>
                            <th>is Deleted</th>
                            <th>Deleted At</th>
                            <th>Action</td>
                        </tr>
                    </thead>
                    @if(isset($payments))
                    <tbody>
                        @forelse($payments as $payment)
                        <tr id="pay_{{$payment->id}}">
                            <td>{{$payment->user->distid ?? '--'}}</td>
                            <td>{{$payment->user->id ?? '--'}}</td>
                            <td>{{$payment->first_name}}</td>
                            <td>{{$payment->last_name}}</td>
                            <td>{{$payment->user->sponsorid ?? '--'}}</td>
                            <td>{{$payment->masked_credit_card}}</td>
                            <td>{{$payment->expiration_month}}/{{$payment->expiration_year}}</td>
                            <td>{{$payment->created_at}}</td>
                            @if($payment->is_deleted)
                            <td><span class="m-badge m-badge--danger m-badge--wide">YES</span></td>
                            @else
                            <td><span class="m-badge m-badge--success m-badge--wide">NO</span></td>
                            @endif
                            <td>{{$payment->deleted_at}}</td>
                            <td>
                                @if(!$payment->is_deleted)
                                <button class="btn btn-danger btn-sm btnDelPaymentMethod" data-id="{{$payment->id}}">Mark as Deleted</button>
                                @else
                                <button class="btn btn-secondary btn-sm">Deleted!</button>
                                @endif
                                <a class="btn btn-warning btn-sm btnViewTransactions" href="{{ route('payment-lookup-all-transactions', ['first' => $query['numbers6'], 'last' => $query['numbers4'], 'id' => $payment->id]) }}">
                                    View Transactions <span class="badge badge-info">{{ $payment->orders->count() }}</span></a>
                            </td>
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#paymentLookup').DataTable({})

        $('body').on('click', '.btnDelPaymentMethod', function() {
            var btn = $(this);
            btn.html("Please Wait...");
            var id = $(this).data('id');

            $.getJSON(`/payment-method/${id}/delete`, (response) => {
                $('#pay_' + response.id).find("td").eq(8).html('<span class="m-badge m-badge--danger m-badge--wide">YES</span>');
                $('#pay_' + response.id).find("td").eq(9).html(response.updated_at);
                btn.html("Deleted!")
                btn.parent()
            })
        })
    })
</script>

@endpush
