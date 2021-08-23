@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        All Sapphires By Country
                    </h3>
                </div>
            </div>
            <form id="frmSapphireFilter" style="display: flex;">
                <div class="m-portlet__head-tools">
                    <label class="col-5 col-form-label" style="text-align: right;">Country</label>
                    <select id="filterByCountry" class="form-control m-input form-control-sm" name="country_code">
                        <option value="">All</option>
                        @foreach ($countryList as $country)
                        <option value="{{$country->countrycode}}">{{$country->country}}</option>
                        @endforeach
                    </select>&nbsp;
                    <a class="btn btn-info btn-sm m-btn--air" id="btnFilterSapphire">View</a>&nbsp;
                    @if (\App\AdminPermission::export_reports())
                        <button class="btn btn-info btn-sm m-btn--air" id="exp_sapphires">Export</button>
                    @endif
                </div>
            </form>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_sapphire">
                <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Country</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
