<?php
$s = Request::segment(1);
$show_question_list = false;
$t = 88;
?>
<!DOCTYPE html>
<html lang="en">

    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>iBuumerang</title>
        <meta name="_token" content="{{ csrf_token() }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js" integrity="sha384-0bIyOfFEbXDmR9pWVT6PKyzSRIx8gTXuOsrfXQA51wfXn3LRXt+ih6riwq9Zv2yn" crossorigin="anonymous"></script>
        <script>
WebFont.load({
    google: {"families": ["Montserrat:300,400,500,600,700", "Roboto:300,400,500,600,700", "Poppins:300,400,500,600,700"]},
    active: function () {
        sessionStorage.fonts = true;
    }
});
        </script>
        <link href="{{asset('/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/animate.css/animate.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/css/affiliate/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/css/datatables.bundle.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('/assets/plugins/viewer/dist/viewer.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/assets/css/affiliate/mycss.css?'.$t)}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/binary.viewer.css?'.$t)}}" rel="stylesheet" type="text/css" />
    @if (in_array(Route::currentRouteAction(), ['App\Http\Controllers\DashboardController@index']))
    <link href="{{asset("/assets/buumerang/css/style.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("/assets/buumerang/css/intlTelInput.css")}}" rel="stylesheet" type="text/css" />
    @endif

        <link rel="shortcut icon" href="{{asset('/assets/images/ncrease.ico')}}" />

{{--        Freshcaht bot--}}
        <script> (function (d, w, c) { if(!d.getElementById("spd-busns-spt")) {
             var n = d.getElementsByTagName('script')[0],
             s = d.createElement('script'); var loaded = false;
             s.id = "spd-busns-spt"; s.async = "async";
             s.src = "https://cdn.freshbots.ai/assets/share/js/fbotsChat.min.js";
             s.setAttribute("data-prdct-hash", "72739a6abc259fc06bd3aae6d959178cf1ee37c7");
             s.setAttribute("data-region", "us");
             s.setAttribute("data-ext-client-id", "f65923debddc11e997000242ac110002");if (c) { s.onreadystatechange = s.onload = function () { if (!loaded) { c(); } loaded = true; }; } n.parentNode.insertBefore(s, n); } })(document, window);
        </script>
    </head>

    {{-- This will show the steps component for the user if the variable below is set --}}
    @if (isset($infoIsIncomplete) && $infoIsIncomplete)
        <div class="modal-steps">
            <steps></steps>
        </div>
    @endif

    <!-- end::Head -->

    @if($s == "login" || $s == "sign-up" || $s == "verify-email" || $s == "igo4less" || $s == "reset-password" || $s == "binary-viewer" || $s == "entire-organization-report" || $s == "subscription-details")
    <body class="{{ $s }} m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
        @else
        <!-- begin::Body -->
    <body style="background-image: url(<?php echo asset('/assets/images/bg-ncrease.png'); ?>);background-position:bottom;" class="m-page--boxed m-body--fixed m-header--static m-aside--offcanvas-default">
        @endif
        @if($show_question_list)
        <div id="showQuestionList"></div>
        @endif
        {{-- dd check out is an important generic modal for products --}}
        <div class="modal fade" id="dd_check_out" aria-hidden="true"></div>
        <div class="modal fade" id="dd_q_list" aria-hidden="true" data-backdrop="static"></div>
        <div class="modal fade" id="dd_upgrade" aria-hidden="true"></div>
        <div class="modal fade" id="buy_voucher" aria-hidden="true"></div>
        <div class="modal fade" id="dd_s" aria-hidden="true"></div>
        <div class="modal fade" id="dd_l" aria-hidden="true"></div>
        <div class="modal fade" id="dd_subscription_add_card"  data-keyboard="false" data-backdrop="static" aria-hidden="true"></div>
        <div class="modal fade" id="dd_subscription_reactivate"  data-keyboard="false" data-backdrop="static" aria-hidden="true"></div>
        <div class="modal fade" id="dd_suspended_subscription_reactivate"  data-keyboard="false" data-backdrop="static" aria-hidden="true"></div>
        <div class="modal fade" id="dd_suspended_account_reactivate" aria-hidden="true"></div>
        <div class="modal fade" id="dd_subscription_reactivate_suspended_user" aria-hidden="true"></div>
        <div class="modal fade" id="dd_idecide_agreement" aria-hidden="true"></div>
        <div class="modal fade" id="dd_igo_agreement" aria-hidden="true"></div>
        <div class="modal fade" id="dd_ibuum_foundation" aria-hidden="true"></div>
        <div class="modal fade" id="dd_ticket_checkout" aria-hidden="true"></div>
        <div class="modal fade" id="dd_events_ticket_checkout" aria-hidden="true"></div>

        <div id="burl" style="display:none">{{url('/')}}</div>
        @yield('content')

        <script src="{{asset('/js/app.js?'.$t)}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/viewer/dist/viewer.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/moment/min/moment.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/wnumb/wNumb.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/bootstrap-notify.init.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/affiliate/scripts.bundle.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/datatables.bundle.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/chart.js/dist/Chart.bundle.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/jquery-ui/jquery-ui.bundle.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/clipboard/clipboard.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/sweetalert2.init.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/myjs.js')}}" type="text/javascript"></script>
        @if (in_array(Route::currentRouteAction(), ['App\Http\Controllers\DashboardController@index']))
        <script src="{{asset('/assets/buumerang/js/intlTelInput-jquery.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/buumerang/js/utils.js')}}" type="text/javascript"></script>
        <script>
          $("#txtSendSMS").intlTelInput({
                utilsScript: '/assets/buumerang/js/utils.js',
                initialCountry: "auto",
                geoIpLookup: function(success, failure) {
                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    success(countryCode);
                    });
                },
           });
        </script>

       @endif

        @yield('scripts')

        <script>
            jQuery(document).ready(function () {
                js_myjs.init();
            });
        </script>

        <script>
        window.fcWidget.init({
        token: "56edd9af-3a36-4b5b-8fad-3b9ba12d99d4",
        host: "https://wchat.freshchat.com"
        });
        </script>
    </body>
    <!-- end::Body -->
</html>
