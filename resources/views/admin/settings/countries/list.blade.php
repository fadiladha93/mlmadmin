@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Countries
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_country">
                <thead>
                    <tr>
                        {{-- <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th> --}}
                        <th>Counry</th>
                        <th>Code</th>
                        <th>Enable 2FA</th>
                        <th>Tier 3</th>
                        <th>Open</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{asset('/assets/js/countries.js')}}" type="text/javascript"></script>
@endpush