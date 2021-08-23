@extends('admin.layouts.app')

@section('content')
<div class="m-grid m-grid--hor m-grid--root m-page">

    @include('admin.layouts.header')

    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

        @include('admin.layouts.sidebar')
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            @yield('main_content')
        </div>
    </div>

    @include('admin.layouts.footer')
</div>
@include('admin.layouts.scroll_to_top')
@endsection