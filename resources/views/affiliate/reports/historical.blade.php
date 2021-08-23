@extends('affiliate.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head our_head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Historical Volume Report
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body text-center">
                    <h3 class="report-title">Weekly Volumes</h3>

                    <table class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                            <tr>
                                <th>Week Ending</th>
                                <th>Carryover Volume Left</th>
                                <th>Carryover Volume Right</th>
                                {{--<th>Pay Pd. Volume Left</th>
                                <th>Pay Pd. Volume Right</th>--}}
                                <th>Adjusted Volume Left</th>
                                <th>Adjusted Volume Right</th>
                                <th>Total Volume Left</th>
                                <th>Total Volume Right</th>
                                <th>Paid On Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commissions as $item)
                            <tr>
                                <td>{{substr($item->week_ending, 0, 11)}}</td>
                                <td>{{number_format($item->carryover_left)}}</td>
                                <td>{{number_format($item->carryover_right)}}</td>
                                {{--<td>{{$item->total_volume_left - $item->carryover_left}}</td>
                                <td>{{$item->total_volume_right - $item->carryover_right}}</td>--}}
                                <td>-</td>
                                <td>-</td>
                                <td>{{number_format($item->total_volume_left)}}</td>
                                <td>{{number_format($item->total_volume_right)}}</td>
                                <td>{{number_format($item->amount_earned)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="table-separator"></div>

                    <h3 class="report-title">Monthly Volumes</h3>

                    <table class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                        <tr>
                            <th>Pay Period</th>
                            <th>Monthly QV</th>
                            <th>Adjusted Monthly QV</th>
                            <th>Total QV</th>
                            <th>Rank Qualified QV</th>
                            <th>Monthly CV</th>
                            <th>Adjusted CV</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($rankHistory as $item)
                            <tr>
                                <td>{{substr($item->period, 0, 11)}}</td>
                                <td>{{number_format($item->monthly_qv)}}</td>
                                <td>-</td>
                                <td>{{number_format($item->monthly_qv)}}</td>
                                <td>{{number_format($item->qualified_qv)}}</td>
                                <td>{{number_format($item->monthly_cv)}}</td>
                                <td>0</td>
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