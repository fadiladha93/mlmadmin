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
                                @if(!empty($pendingPost))
                                    @foreach($pendingPost as $w)
                                        <option value="{{$w->end_date}}#pedingPost">
                                            Commission Period Ending {{substr($w->end_date,0,10)}}</option>
                                    @endforeach
                                @endif

                                @foreach($weeks as $w)
                                    <option value="{{$w}}" {{isset($week_ending) && $week_ending == $w ? 'selected':''}}>
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
                    @if(isset($week_commission_detail))
                        <span class="m-section__sub"></span>
                        <table class="table m-table m-table--head-separator-info">
                            <thead>
                            <tr>
                                <th>Commission Type</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($week_commission_detail->count()>0)
                                @foreach($week_commission_detail as $wcd)
                                    <tr>
                                        <td>
                                            <form method="POST"
                                                  action="{{url('commission/weekly/details')}}">
                                                @csrf
                                                <input type="hidden" name="week_ending"
                                                       value="{{$week_ending}}"/>
                                                <a href="#" style="text-decoration: none;"
                                                   onclick="$(this).closest('form').submit(); return false;">Fast Start Bonus</a>
                                            </form>
                                        </td>
                                        <td>${{number_format($wcd->total,2)}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @if ($binaryCommission)
                                <tr>
                                    <td>
                                        <form method="POST"
                                              action="{{url('commission/weekly/details')}}">
                                            @csrf
                                            <input type="hidden" name="binary_week_ending"
                                                   value="{{$week_ending}}"/>
                                            <a href="#" style="text-decoration: none;"
                                               onclick="$(this).closest('form').submit(); return false;">Binary Commission</a>
                                        </form>
                                    </td>
                                    <td>${{number_format($binaryCommission, 2)}}</td>
                                </tr>
                            @endif
                            @if (isset($adjustment5_12) && !empty($adjustment5_12))
                                <tr>
                                    <td>Adjustment</td>
                                    <td>${{ number_format($adjustment5_12, 2) }}</td>
                                </tr>
                            @endif
                            @if (isset($adjustment5_19) && !empty($adjustment5_19))
                                <tr>
                                    <td>Adjustment</td>
                                    <td>${{ number_format($adjustment5_19, 2) }}</td>
                                </tr>
                            @endif
                            @if (isset($adjustment5_26) && !empty($adjustment5_26))
                                <tr>
                                    <td>Adjustment</td>
                                    <td>${{ number_format($adjustment5_26, 2) }}</td>
                                </tr>
                            @endif
                            @if (isset($adjustment6_02) && !empty($adjustment6_02))
                                <tr>
                                    <td>Adjustment</td>
                                    <td>${{ number_format($adjustment6_02, 2) }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Monthly Commission
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form method="POST" action="{{url('commission/weekly')}}">
                        <div class="form-group">
                            <select class="custom-select form-control" name="unilevel_date">
                                @foreach($monthCommissionDates as $item)
                                    <option value="{{ $item }}" {{isset($selected) && $selected ==  $item ? 'selected' : ''}}>
                                        Commission Summary for Month Ending {{ substr($item, 0, 10) }}
                                        @if($item == '2019-05-31 23:59:59')
                                            Adjusted
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @csrf
                        <div class="form-group">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>

                    @if(isset($selected))
                        <table class="table table-bordered table-hover">
                            <tbody>
                            @foreach($unilevelCommissions as $item)
                                <tr>
                                    <td>
                                        <a href="/unilevel-commission-details?date={{$item->end_date}}" target="_blank">Unilevel Commission
                                            @if($item->end_date == '2019-05-31 23:59:59')
                                                Adjusted
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        ${{ number_format($item->sum,2) }}
                                    </td>
                                </tr>
                                @if($item->end_date == '2019-05-31 23:59:59' && isset($adjustment_5_31) && !empty($adjustment_5_31))
                                    <tr>
                                        <td>Adjustment</td>
                                        <td>{{ $adjustment_5_31 }}</td>
                                    </tr>
                                @endif

                            @endforeach
                            @foreach($leadershipCommissions as $item)
                                @if($selected !== '2019-05-31 23:59:59')
                                    <tr>
                                        <td>
                                            <a href="/leadership-commission-details?date={{$item->end_date}}" target="_blank">Leadership Commission</a>
                                        </td>
                                        <td>
                                            ${{ number_format($item->sum,2) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @foreach($tsbCommissions as $item)
                                <tr>
                                    <td>
                                        <a href="/tsb-commission-details?date={{$item->end_date}}" target="_blank">TSB Commission
                                            @if($item->end_date == '2019-05-31 23:59:59')
                                                Adjusted
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        ${{ number_format($item->sum,2) }}
                                    </td>
                                </tr>
                                @if($item->end_date == '2019-05-31 23:59:59' && isset($adjustment_5_31) && !empty($adjustment_5_31))
                                    <tr>
                                        <td>Adjustment</td>
                                        <td>{{ $adjustment_5_31 }}</td>
                                    </tr>
                                @endif

                            @endforeach
                            </tbody>
                        </table>

                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            TSB Commission: ${{ $sum }}
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
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($commissions as $c)
                        <tr>
                            @if ($c->name)
                                <td>{{ $c->name }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                            <td>TSB Commission</td>
                            <td>${{$c->amount}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $commissions->appends(['date' => Request::get('date')])->links() }}
            </div>
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
