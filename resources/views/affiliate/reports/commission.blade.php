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
        </div>
        <div class="col-md-6">
            @if(isset($commissions))
            <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Commission Details (Fast Start Bonus)
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="table-responsive">
                        <table class="table m-table m-table--head-separator-info">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Memo</th>
                                    <th>Bonus Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissions as $c)
                                <tr>
                                    <td>{{$c->firstname}} {{$c->lastname}}</td>
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
                                @php $showBinaryCommissionsMessage = false; @endphp
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
                                    @php
                                    if (!$showBinaryCommissionsMessage) {
                                        $expectedAmountEarned = $c->gross_volume * $c->commission_percent;

                                        if ($expectedAmountEarned != $c->amount_earned) {
                                            $showBinaryCommissionsMessage = true;
                                        }
                                    }
                                    @endphp
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($showBinaryCommissionsMessage)
                            <p style="color: red;"><b>60% Max Payout Applied</b></p>
                        @endif
                    </div>

                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
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
                                        <a href="/tsb-commission-details?date={{$item->end_date}}">TSB Commission
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
    </div>
</div>
@endsection
