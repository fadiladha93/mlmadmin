@extends('affiliate.layouts.main')

@section('main_content')
<div class="row">
    <div class="col-md-12">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            {{$promo->side_banner_title}}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">
                {!! $promo->side_banner_long_desc !!}
            </div>
        </div>
    </div>
</div>

@endsection