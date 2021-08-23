<?php
$s = Request::segment(1);
$t = 85;
?>
<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>ncrease | ncrease your impact</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js" integrity="sha384-0bIyOfFEbXDmR9pWVT6PKyzSRIx8gTXuOsrfXQA51wfXn3LRXt+ih6riwq9Zv2yn" crossorigin="anonymous"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function() {
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
    <link href="{{asset('/assets/plugins/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/plugins/summernote/dist/summernote.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('/assets/css/admin/style.bundle.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/datatables.bundle.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/admin/mycss.css?'.$t)}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/admin/bootstrap-toggle.min.css?'.$t)}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/binary.modification.css?'.$t)}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/css/binary.viewer.css?'.$t)}}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="//www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css" />
    @stack('styles')
    <link rel="shortcut icon" href="{{asset('/assets/images/ncrease.ico')}}" />
</head>

@if($s == "admin-login")

<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    @else

    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
        @endif

        <div id="burl" style="display:none">{{url('/')}}</div>
        <div class="modal fade" id="dd_s" aria-hidden="true"></div>
        <div class="modal fade" id="dd_l" aria-hidden="true"></div>
        <div class="modal fade" id="dd_payout_control" aria-hidden="true"></div>
        @yield('content')

        @yield('scripts')
        <script src="{{asset('/assets/plugins/jquery/dist/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/moment/min/moment.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>


        <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

        <script src="https://canvasjs.com/jquery-charts/column-line-area-chart"></script>

        <script src="{{asset('/assets/plugins/bootstrap-notify/bootstrap-notify.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/bootstrap-notify.init.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/jquery-ui/jquery-ui.bundle.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/select2/dist/js/select2.full.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/summernote/dist/summernote.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/plugins/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>

        <script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
        <script src="{{asset('/assets/js/admin/scripts.bundle.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/datatables.bundle.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/sweetalert2.init.js')}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/myjs.js?'.$t)}}" type="text/javascript"></script>
        <script src="{{asset('/assets/js/admin/bootstrap-toggle.min.js')}}" type="text/javascript"></script>

        <script>
            jQuery(document).ready(function() {
                js_myjs.init();

                setTimeout(function() {
                    $("div.alert").remove();
                }, 10000); // 10 secs
            });
        </script>

        @yield('last_scripts')
        @stack('scripts')
    </body>

</html>
