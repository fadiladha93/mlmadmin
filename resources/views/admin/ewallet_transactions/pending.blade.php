@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Pending Transfer [ Total : ${{number_format($total, 2)}} ]
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button class="btn btn-danger btn-sm m-btn--air" id="btnTransferNow">Transfer Now !</button>
            </div>
        </div>
        <div class="m-portlet__body">
            @if($payap_csv_id > 0)
            <div class="alert alert-success text-center">
                {{$payap_rec_count}} records are transfered successfully. Click <a href="{{url('/download-csv/'.$payap_csv_id)}}">here</a> to download.
            </div>
            @endif
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_ewallet_pending">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Amount</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection