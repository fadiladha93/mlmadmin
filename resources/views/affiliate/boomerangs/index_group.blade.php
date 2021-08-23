@extends('affiliate.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head our_head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Group Boomerangs
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_boomerangs_group">
                <thead>
                    <tr>
                        <th>Campaign Name</th>
                        <th>Number of uses</th>
                        <th>Available</th>
                        <th>Boomerang Code</th>
                        <th>Date Created</th>
                        <th>Expiration date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection