@extends('affiliate.layouts.main')

@section('main_content')

<div class="m-content">
    @if($promo->top_banner_is_active == 1)
    <div class="row">
        <div class="col-lg-12">
            @if(utill::isNullOrEmpty($promo->top_banner_url)) 
            <img src="{{asset('/promo/'.$promo->top_banner_img)}}" width="100%" />
            @else
            <a href="{{$promo->top_banner_url}}" target="_blank"><img src="{{asset('/promo/'.$promo->top_banner_img)}}" width="100%" /></a>
            @endif
        </div>
    </div>
    @endif
    <div class="row" style="margin-top:20px;">
        <div class="col-lg-12">
            @include('affiliate.dashboard.ranking')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('affiliate.dashboard.enrollment_progress')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            @include('affiliate.dashboard.upgrades')
            @if($is_tv_users)
            @include('affiliate.dashboard.tv_idecide')
            @endif
            @if($promo->side_banner_is_active == 1)
            @include('affiliate.dashboard.side_promo')
            @endif
        </div>
        <div class="col-lg-9">
            @include('affiliate.dashboard.boomerang')
        </div>
    </div>
</div>

@endsection