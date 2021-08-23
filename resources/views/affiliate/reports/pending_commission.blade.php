@extends('affiliate.layouts.main')

@section('main_content')
    <div class="m-content">
        <div class="row">
            <div class="col-md-6">
                <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Weekly Commission
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <form method="POST" action="{{url('commission/weekly')}}">
                            <div class="form-group">
                                <select class="custom-select form-control" name="week_ending">
                                    @foreach($pendingPost as $w)
                                        <option value="{{$w->end_date}}#pedingPost" selected>
                                            PENDING - Commission Period Ending {{substr($w->end_date,0,10)}}</option>
                                    @endforeach

                                    @foreach($weeks as $w)
                                        <option
                                            value="{{$w}}" {{isset($week_ending) && $week_ending == $w ? 'selected':''}}>
                                            Commission Summary for Week
                                            Ending {{substr($w,0,10)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @csrf
                            <div class="form-group">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if(isset($pendingCommission))
                    <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        Pending Post Commission
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="table-responsive">
                                <table class="table m-table m-table--head-separator-info">
                                    <thead>
                                    <tr>
                                        {{--<th>Name</th>--}}
                                        <th>Level</th>
                                        <th>Memo</th>
                                        <th>Bonus Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($pendingCommission as $c)
                                        <tr>
{{--                                            <td>{{(isset($c->firstname)?$c->firstname:'')}} {{isset($c->lastname)?$c->lastname:''}}</td>--}}
                                            <td>{{$c->level}}</td>
                                            <td>{{$c->memo}}</td>
                                            <td>${{number_format($c->amount,2)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif(isset($binaryCommissions))
                    <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        Binary Commission Details
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="table-responsive">
                                <table class="table m-table m-table--head-separator-info">
                                    <thead>
                                    <tr>
                                        <th>Carryover Left</th>
                                        <th>Carryover Right</th>
                                        <th>Total Volume Left</th>
                                        <th>Total Volume Right</th>
                                        <th>Gross Volume Left/Right</th>
                                        <th>Commission Percent</th>
                                        <th>Amount Earned</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($binaryCommissions as $c)
                                        <tr>
                                            <td>{{$c->carryover_left}}</td>
                                            <td>{{$c->carryover_right}}</td>
                                            <td>{{$c->total_volume_left}}</td>
                                            <td>{{$c->total_volume_right}}</td>
                                            <td>{{$c->gross_volume}}</td>
                                            <td>{{$c->commission_percent}}</td>
                                            <td>{{$c->amount_earned}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
