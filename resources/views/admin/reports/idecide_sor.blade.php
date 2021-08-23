@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        iDecide/SOR
                    </h3>
                </div>
            </div>
            <form id="frmIDecideAndSorFilter" style="display: flex;">
                <div class="m-portlet__head-tools">
                    <input class="form-control m-input form-control-sm date_picker2" name="d_from" id="d_from" placeholder="From Date" value="{{ $from != null ? $from : "" }}" />&nbsp;
                    <input class="form-control m-input form-control-sm date_picker2" name="d_to" id="d_to" placeholder="To Date" value="{{ $to != null ? $to : "" }}" />&nbsp;
                    <button class="btn btn-info btn-sm m-btn--air" id="idecideSorFilterBtn">View</button>&nbsp;
                    @if (\App\AdminPermission::export_reports())
                        <button class="btn btn-info btn-sm m-btn--air" id="exp_idecide_and_sor">Export</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable">
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>iDecide</td>
                        <td>{{ number_format($idecide_total) }}</td>
                    </tr>
                    <tr>
                        <td>SOR</td>
                        <td>{{ number_format($sor_total) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
