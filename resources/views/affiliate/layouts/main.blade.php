@extends('affiliate.layouts.app')

@section('content')

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">

    @include('affiliate.layouts.header')

    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid m-grid--hor m-container m-container--responsive m-container--xxl">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body" style="height:100%">

            @include('affiliate.layouts.horizontal_menu')

            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-body__content">
                <div class="m-grid__item m-grid__item--fluid m-wrapper">
                    @yield('main_content')
                </div>
            </div>
        </div>
    </div>
    <!-- begin::Body -->

    <!-- begin::Footer -->
    @include('affiliate.layouts.footer')
</div>

<!-- end:: Page -->

@include('affiliate.layouts.scroll_top')

@endsection
