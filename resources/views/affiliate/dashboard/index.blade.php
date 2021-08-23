@extends('affiliate.layouts.main')

@section('main_content')

    <div class="m-content">
        {{--    @if($promo->top_banner_is_active == 1)--}}
        {{--    <div class="row">--}}
        {{--        <div class="col-lg-12">--}}
        {{--            @if(utill::isNullOrEmpty($promo->top_banner_url))--}}
        {{--            <img src="{{asset('/promo/'.$promo->top_banner_img)}}" width="100%" />--}}
        {{--            @else--}}
        {{--            <a href="{{$promo->top_banner_url}}" target="_blank"><img src="{{asset('/promo/'.$promo->top_banner_img)}}" width="100%" /></a>--}}
        {{--            @endif--}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--    @endif--}}

        <div class="row">
            <div class="col-lg-12">
                <div id="btnCheckOutPhotobook2020" style="cursor: pointer"><img src="{{asset('/assets/images/digital_album_os.jpg')}}" width="100%" /></div>
            </div>
        </div>


        <div class="row" style="margin-top:20px;">
            <div class="col-lg-12">
                @include('affiliate.dashboard.business_snapshot_widget')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @include('affiliate.dashboard.boomerang-widget')
            </div>
            <div class="col-lg-4">
                @include('affiliate.dashboard.upgrades')
            </div>
        <!-- 11/14/2019
                <div class="col-lg-8">
                    @include('affiliate.dashboard.boomerang')
            </div>
            <div class="col-lg-4">
                <h3>col lg 4</h3>
        @include('affiliate.dashboard.upgrades')
            </div>
-->
        </div>
        <div class="row">
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
            <div class="col-lg-7">
                <div class="row">
                    <div class="col-lg-6">
                        @if($promo->side_banner_is_active == 1)
                            @include('affiliate.dashboard.side_promo')
                        @endif
                    </div>
                    <div class="col-lg-6">
                        @if($is_tv_users)
                            @include('affiliate.dashboard.tv_idecide')
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{--    <div class="row">--}}
        {{--        <div class="col-lg-12">--}}
        {{--            <div class="m-portlet m-portlet--mobile" id="replicatedPrefs">--}}
        {{--            @include('affiliate.dashboard.replicated_preferences')--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}

        @if ($showVibeAgreementModal)
            @include('affiliate.dashboard.modal.vibe_agreement');
            @include('affiliate.agreement.terms-and-condition');
            @include('affiliate.agreement.privacy-policy');
            @include('affiliate.agreement.policies-and-procedures');
        @endif
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            @if ($showVibeAgreementModal)
            $("#vibeAgreementModal").modal({
                show: true,
                keyboard: false,
                backdrop: 'static'
            });
            @endif

            $.ajax({
                url: '/check-ticket-purchased',
                type: 'GET',
                data: '',
                success: function (data) {
                    if (data['v']) {
                        $("#dd_ticket_checkout").html(data['v']);
                        $("#dd_ticket_checkout").modal("show");
                    }
                }
            });
        })
    </script>
@endsection

@section('modal')
    @include('affiliate.dashboard.modal.steps._user_info_is_incomplete', ['step' => '1'])
@endsection
