<?php
$s = Request::segment(1);

if (!Auth::check())
    return "";

?>
<div class="m-grid__item m-body__nav">
    <div class="m-stack m-stack--ver m-stack--desktop">

        <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
            <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light" id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
            <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
                <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                    <li class="m-menu__item @if($s == "")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Dashboard</span></a></li>
                @if (Auth::user()->account_status == \App\User::ACC_STATUS_SUSPENDED)
                    <!-- NO MENU - ACCOUNT IS SUSPENDED-->
                @elseif (Auth::user()->country_code == 'US' && (int)Auth::user()->is_tax_confirmed == 0)
                    <!-- NO MENU - US costumer with no tax info -->
                @elseif (Auth::user()->country_code == 'UM' && (int)Auth::user()->is_tax_confirmed == 0)
                    <!-- NO MENU - US costumer with no tax info -->
                @elseif (Auth::user()->country_code == 'VI' && (int)Auth::user()->is_tax_confirmed == 0)
                    <!-- NO MENU - US costumer with no tax info -->
                    @else
                        <li class="m-menu__item @if($s == "reports") m-menu__item--active @endif m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                <span class="m-menu__item-here"></span>
                                <span class="m-menu__link-text">Reports</span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                <!--
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/report/personally-enrolled')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Personally Enrolled Report</span></a></li>
                                    -->
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/entire-organization-report')}}" target="_blank" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Entire Organization Report</span></a></li>
                                    <!-- <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/report/distributors_by_level')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Organization</span></a></li> -->
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/customers')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Customers</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/invoice')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Invoice</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/binary-viewer')}}" class="m-menu__link " target="_blank" rel="noreferrer noopener"><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Binary Viewer</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/weekly-binary-report')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Weekly Binary Report</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/weekly-enrollment-report')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Weekly Enrollment Report</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/report/pear/')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">PEAR Report</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/report/historical/')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Historical Volume Report</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="m-menu__item @if($s == "individual-boomerangs" || $s == "group-boomerangs")m-menu__item--active @endif m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle"><span class="m-menu__item-here"></span><span class="m-menu__link-text">Boomerang</span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/individual-boomerangs')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Individual</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/group-boomerangs')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Group</span></a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="m-menu__item @if($s == "commission")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/commission')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Commission</span></a></li>
{{--                        <li class="m-menu__item @if($s == "tools")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/tools')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Tools</span></a></li>--}}
                        <!--<li class="m-menu__item @if($s == "tools")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/tools')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Tools</span></a></li>-->


                        <li class="m-menu__item @if($s == "tools") m-menu__item--active @endif m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                <span class="m-menu__item-here"></span>
                                <span class="m-menu__link-text">Tools</span>
                                <i class="m-menu__hor-arrow la la-angle-down"></i>
                                <i class="m-menu__ver-arrow la la-angle-right"></i>
                            </a>
                            <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/tools/training')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Training</span></a></li>
                                    <li class="m-menu__item " m-menu-link-redirect="1" aria-haspopup="true"><a href="{{url('/tools')}}" class="m-menu__link "><i class="m-menu__link-icon fa fa-angle-right"></i><span class="m-menu__link-text">Document Library</span></a></li>
                                </ul>
                            </div>
                        </li>


                        <li class="m-menu__item @if($s == "e-wallet")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/e-wallet')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">E-Wallet</span></a></li>
                        <li class="m-menu__item @if($s == "subscription")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/subscription')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Subscription</span></a></li>
                        <li class="m-menu__item @if($s == "buy-voucher")m-menu__item--active @endif" aria-haspopup="true"><a href="#" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text " id="buy-voucher">Buy Voucher</span></a></li>
                        @php
                            $placementLoungeSetting = App\Models\SiteSettings::where('key', 'is_holding_tank_active')->first();
                            $isEnabledPlacementLounge = !empty($placementLoungeSetting) ? intval($placementLoungeSetting->value) === 1 : false;
                        @endphp
                        @if($isEnabledPlacementLounge)
                            @if (\App\BinaryPermission::isPermit(Auth::user()->distid))
                                {{-- <li class="m-menu__item @if($s == "placement-lounge")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/placement-lounge')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Placement lounge</span></a></li> --}}
                            @endif
                        @endif
                        <li class="m-menu__item " aria-haspopup="true"><a id="login-to-events-browse-btn" href="javascript:;" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text " id="buyTickets">Events</span></a></li>
                        <form method="POST" id="login-to-events-browse" name="login-to-events-browse" action="https://events.myibuumerang.com/signin/ibuum-user" target="_blank" autocomplete="off">
                            <input type="hidden" name="token" value="*****" />
                        </form>
                        <!-- <li class="m-menu__item @if($s == "shop")m-menu__item--active @endif" aria-haspopup="true"><a href="{{url('/shop')}}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text" >Shop</span></a></li> -->
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    // function addEventsTicketModal()
    // {
    //      $.ajax({
    //         url: '/check-events-ticket-purchased',
    //         type: 'GET',
    //         data: '',
    //         success: function (data) {
    //             if(data['view']){
    //                 $("#dd_events_ticket_checkout").html(data['view']);
    //                 $("#dd_events_ticket_checkout").modal("show");
    //
    //                 $('#not-entered-quantities').css('visibility','hidden');
    //             }
    //         }
    //     });
    // }
</script>
