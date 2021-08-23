@extends('admin.layouts.main')
@section('main_content')
<div class="m-content">
    <div class="chargeback-manage">
        <chargeback-manage></chargeback-manage>
    </div>
</div>
@endsection
@section('last_scripts')

@endsection

@section('scripts')
<script src="{{asset('/js/app.js')}}" type="text/javascript"></script>
@endsection