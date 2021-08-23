@extends('admin.layouts.main')

@section('main_content')
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Dashboard</h3>
        </div>
    </div>
</div>
<div class="m-content">
    {{--  <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            Standby
                        </h5>
                        <div class="c m--font-brand">
                            {{number_format($standby_count)}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            Coach Class
                        </h5>
                        <div class="c m--font-info">
                            {{number_format($coach_count)}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            Business Class
                        </h5>
                        <div class="c m--font-success">
                            {{number_format($business_count)}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            Grandfathering
                        </h5>
                        <div class="c m--font-warning">
                            {{number_format($grandfathering_count)}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            First Class
                        </h5>
                        <div class="c m--font-danger">
                            {{number_format($first_count)}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            VIP First Class
                        </h5>
                        <div class="c m--font-accent">
                            {{number_format($vip_count)}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl">
                    <div class="wid_enroll">
                        <h5>
                            Premium F.C.
                        </h5>
                        <div class="c m--font-accent">
                            {{number_format($premium_first_count)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  --}}
    @if($show_graph)
    <div class="m-portlet m-portlet--tab amCharts">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="m-portlet__head-text chartTitle">
                        Daily Sales
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <select class="form-control m-input form-control-sm" name="chartType" id="chartType">
                    <option value="sales" selected>Sales</option>
                    <option value="enrollments">Enrollments</option>
                </select>&nbsp;
                <select class="form-control m-input form-control-sm" name="chartYear" id="chartYear">
                    @foreach ($order_years as $year)
                    <option value="{{$year->year}}" {{ $year->year == date("Y") ? "selected" : "" }}>{{$year->year}}</option>
                    @endforeach
                </select>&nbsp;
                <select class="form-control m-input form-control-sm" name="chartMonth" id="chartMonth">
                    @foreach ($order_months as $m => $month)
                    <option value="{{$m}}" {{ $m == date("m") ? "selected" : "" }}>{{$month}}</option>
                    @endforeach
                </select>&nbsp;
                <button class="btn btn-info btn-sm m-btn--air" id="amchartFilterBtn">View</button>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="dbChart" class="dbChart" style="height: 300px;"></div>
        </div>
    </div>
    @endif
</div>
@endsection
