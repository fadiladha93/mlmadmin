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
                                Approved commission - Summary [ Transaction Date : {{$trans_date}} | Approved Date : {{$approved_date}} ]
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="{{url('/approved-commission')}}" class="btn btn-success btn-sm m-btn--air">Re-search</a>&nbsp;
                        <a href="{{url('/approved-commission-detail')}}" class="btn btn-info btn-sm m-btn--air">Detail</a>&nbsp;
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-12">
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_approved_commission_summary">
                                <thead>
                                    <tr>
                                        <th>Dist ID</th>
                                        <th>Username</th>
                                        <th>Estimated Earnings</th>
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