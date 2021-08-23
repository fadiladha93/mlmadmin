@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Commission - Detail ( {{$from}} - {{$to}} ) [ Total : ${{number_format($total, 2)}} ]
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{url('/commission-engine')}}" class="btn btn-success btn-sm m-btn--air">Re-run</a>&nbsp;
                        <a href="{{url('/commission-summary')}}" class="btn btn-info btn-sm m-btn--air">Summary</a>&nbsp;
                        <a class="btn btn-info btn-sm m-btn--air" id="exp_commission_detail">Export</a>&nbsp;
                        <button id="btnPostCommission" class="btn btn-danger btn-sm m-btn--air">Post</button>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <input type="hidden" id="from" value="{{$from}}" />
                    <input type="hidden" id="to" value="{{$to}}" />
                    <input type="hidden" id="total" value="{{$total}}" />
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_commission_detail">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Dist ID</th>
                                        <th>Username</th>
                                        <th>Amount</th>
                                        <th>Level</th>
                                        <th>Memo</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection