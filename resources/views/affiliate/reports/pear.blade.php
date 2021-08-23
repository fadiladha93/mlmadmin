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
                                    PEAR Report
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="report-info">
                            <div class="row">
                                <div class="col-md-3">Name</div>
                                <div class="col-md-3">Total Qualified Volume</div>
                                <div class="col-md-3">Rank Qualified Volume</div>
                                <div class="col-md-3">Current Month Rank</div>
                            </div>
                            <div class="row data-row">
                                <div class="col-md-3">{{ $user->firstname }} {{ $user->lastname }}</div>
                                <div class="col-md-3">{{ number_format($user->current_month_qv) }}</div>
                                <div class="col-md-3">{{ number_format(\App\UserRankHistory::getQV($user->distid, $user->current_month_rank)) }}</div>
                                <div class="col-md-3">{{ $user->rank()->rankdesc }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <h3>Current View: {{ $user->firstname }} {{ $user->lastname }}</h3>
                            </div>
                            <div class="col-md-2">
                                <a href="/report/pear" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Back to top</a>
                            </div>
                        </div>

                        <table class="table table-striped- table-bordered table-hover table-checkable" id="pearData">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total Qualified Volume</th>
                                <th>Rank Qualified Volume</th>
                                <th>Personal Volume</th>
                                <th>Highest Rank</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection