<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
<div id="m_aside_left" class="m-grid__item m-aside-left  m-aside-left--skin-dark ">

    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <li class="m-menu__item" aria-haspopup="true"><a href="{{url('/')}}" class="m-menu__link "><i class="m-menu__link-icon flaticon-home-1"></i><span class="m-menu__link-title"> <span class="m-menu__link-wrap"> <span class="m-menu__link-text">Dashboard</span>
                            <span class="m-menu__link-badge"></span> </span></span></a></li>
            @if(\App\AdminPermission::sidebar_sales())
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Sales</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/orders')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Orders</span></a></li>

                        @if (\App\AdminPermission::sidebar_discount())
                        <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Vouchers</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                            <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                <ul class="m-menu__subnav">
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/discount-coupons')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Voucher Codes</span></a></li>
                                    <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/refund-voucher')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Refund Voucher</span></a></li>
                                </ul>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/payment-lookup')}}" class="m-menu__link "><i class="m-menu__link-icon flaticon-line-graph"><span></span></i><span class="m-menu__link-text">Credit Card Lookup</span></a></li>

            @if(\App\AdminPermission::sidebar_countries())
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text"> Merchant Control Center</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/countries')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Merchant Rotation</span></a></li>  --}}
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/merchants')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Merchant Limits</span></a></li>  --}}
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/payout-control')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Payout Control</span></a></li>
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/batch-order-refund')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Batch Order Refund</span></a></li>  --}}
                        {{--  <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Chargebacks</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>  --}}
                            {{--  <div class="m-menu__submenu "><span class="m-menu__arrow"></span>  --}}
                                {{--  <ul class="m-menu__subnav">  --}}
                                    {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/chargeback/import')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Import Sheet</span></a></li>  --}}
                                    {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/chargeback/stats')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Refund Voucher</span></a></li>  --}}
                                {{--  </ul>  --}}
                            {{--  </div>  --}}
                        {{--  </li>  --}}
                    </ul>
                </div>
            </li>
            @endif
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-clipboard"></i><span class="m-menu__link-text">Reports</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/personally_enrolled_distributors')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-tepersonally_enrolled_distributorsxt">Personally Enrolled Report</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/dist-by-country')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Distributors By Country</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/erollments-by-date/')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Enrollments By Date</span></a></li>
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/distributor-by-rank')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Lifetime Rank Report</span></a></li>  --}}
                        <!--<li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/highest-achieved-rank')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Highest Achieved Rank</span></a></li>-->
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/rank-advancement-report')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Rank Advancement Report</span></a></li>  --}}
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/sales-by-payment-method')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Sales Report</span></a></li>
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/idecide-and-sor/')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">iDecide / SOR</span></a></li>  --}}
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/subscription-by-payment-method')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Subscription By Payment Method</span></a></li>
                        {{-- <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/all-sapphires-by-country')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">All Sapphires By Country</span></a></li> --}}
                        {{-- <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/all-diamonds-by-country')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">All Diamonds By Country</span></a></li> --}}
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/monthly-income-earnings')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Monthly Income Earnings</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/monthly-top-recruiters')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Monthly Top Recruiters</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/monthly-top-customers')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Monthly Top Customers</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/subscription-report')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Subscription Report</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/subscription-history')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Subscription History</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/sales-by-country')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Sales By Country</span></a></li>
                    </ul>
                </div>
            </li>
            @if(\App\AdminPermission::sidebar_commission())
            {{--  <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Commissions</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission/withdrawals')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Withdrawals</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission/transfered')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Transfered</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission/pending')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Pending Transfer</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/approved-commission')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Approved Commission</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/adjustments')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Adjustments</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-engine')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Commission Engine</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-detail-post')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Commission Post</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission/volume')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Volume</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission/importTsb')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Travel Commissions Import</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission/importVibe')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Vibe Commissions Import</span></a></li>
                    </ul>
                </div>
            </li>  --}}
            @endif
            {{-- <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Boomerangs</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/boomerang/leads_ind')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Individual Boomerangs</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/boomerang/leads_grp')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Group Boomerangs</span></a></li>
                    </ul>
                </div>
            </li> --}}
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-users"></i><span class="m-menu__link-text">Users</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        @if(\App\AdminPermission::sidebar_admin_users())
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/users/admins')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Admin Users</span></a></li>
                        @endif
                        @if(\App\AdminPermission::sidebar_cs_users())
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/users/cs-users')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">CS users</span></a></li>
                        @endif
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/users/ambassadors')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Distributors</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/report/line-of-sponsorship')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Line of Sponsorship</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/dist-customers')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Customers</span></a></li>
                        {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/users/terminated-users')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Terminated Users</span></a></li>  --}}
                        @if (\App\AdminPermission::sidebar_user_transfer())
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/user/transfer')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Transfer User</span></a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @if(\App\AdminPermission::sidebar_marketing())
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-paper-plane"></i><span class="m-menu__link-text">Marketing</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/promo-info')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Promo Information</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/email-templates')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Email Templates</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/media')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Media & Files</span></a></li>
                        {{-- <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/bulk-email')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Bulk Email</span></a></li> --}}
                    </ul>
                </div>
            </li>
            @endif
            @if(\App\AdminPermission::events_manage())
            {{-- <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-event-calendar-symbol"></i><span class="m-menu__link-text">Events</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        @if(\App\AdminPermission::events_manage())
                        <li class="m-menu__item " aria-haspopup="true"><a id="login-to-events-manager-btn" href="javascript:;" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Manage Events</span></a></li>
                        @endif
                        <li class="m-menu__item " aria-haspopup="true"><a id="login-to-events-browse-btn" href="javascript:;" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Browse Events</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a id="login-to-events-purchases-btn" href="javascript:;" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">View/Print Tickets</span></a></li>
                    </ul>
                </div>
            </li>
            <form method="POST" id="login-to-events-manager" name="login-to-events-manager" action="https://events.myibuumerang.com/signin/ibuum-admin" target="_blank" autocomplete="off">
                <input type="hidden" name="username" value="*****" />
                <input type="hidden" name="password" value="*****" />
            </form> --}}
            {{-- <form method="POST" id="login-to-events-browse" name="login-to-events-browse" action="https://events.myibuumerang.com/signin/ibuum-user" target="_blank" autocomplete="off">--}}
            {{-- <input type="hidden" name="token" value="*****" />--}}
            {{-- </form>--}}
            {{-- <form method="POST" id="login-to-events-purchases" name="login-to-events-purchases" action="https://events.myibuumerang.com/signin/ibuum-user-tix" target="_blank" autocomplete="off">--}}
            {{-- <input type="hidden" name="token" value="*****" />--}}
            {{-- </form>--}}
            @endif
            @if(\App\AdminPermission::sidebar_product())
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="{{url('/product/products')}}" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon flaticon-shopping-basket"></i>
                    <span class="m-menu__link-text">Products</span>
                </a>
            </li>
            @endif
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-paper-plane"></i><span class="m-menu__link-text">Activity Logs</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/orders')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Orders</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/products')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Products</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/customers')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Customers</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/order-items')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Orders Items</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/users')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Users</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/addresses')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Addresses</span></a></li>
                        {{-- <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/boomeranginv')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Boomerang INV</span></a></li> --}}
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/update-history/adjustments')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Adjustments</span></a></li>
                    </ul>
                </div>
            </li>
            @if(\App\AdminPermission::sidebar_binary_controller())
            {{-- <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Binary Control Center</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-modification/insert')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Insert</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-modification/move')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Move</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-modification/replace')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Replace</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-modification/terminate')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Terminate</span></a></li> --}}
                        {{--<li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-modification/import')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Import</span></a>
            </li>--}}
        {{--  </ul>
    </div>  --}}
    {{-- <div class="m-menu__submenu "><span class="m-menu__arrow"></span>--}}
    {{-- <ul class="m-menu__subnav">--}}
    {{-- <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-tree-editor')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Binary tree editor</span></a></li>--}}
    {{-- </ul>--}}
    {{-- </div>--}}
    {{--  <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
        <ul class="m-menu__subnav">
            <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/binary-permission')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Binary Permission</span></a></li>
        </ul>
    </div>
    </li>  --}}
    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-line-graph"></i><span class="m-menu__link-text">Commission Control Center</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
        <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
            <ul class="m-menu__subnav">
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-control-center/calculate')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Calculate</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-control-center/adjustment')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Adjustment</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-control-center/audit')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Audit</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-control-center/posting')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Posting</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-control-center/payout')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Payout</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/commission-control-center/progress')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Commission progress</span></a></li>
            </ul>
        </div>
    </li>
    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-paper-plane"></i><span class="m-menu__link-text">API</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
        <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
            <ul class="m-menu__subnav">
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/api-token')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Token</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/api-request-history')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Request History</span></a></li>
            </ul>
        </div>
    </li>
    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-settings"></i><span class="m-menu__link-text">Communications</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
        <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
            <ul class="m-menu__subnav">
                {{-- <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/settings/placement-lounge')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Placement Lounge</span></a></li> --}}
                @if (\App\AdminPermission::sidebar_ranks_settings())
                {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/settings/ranks')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Rank Settings</span></a></li>  --}}
                @endif
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/settings/countries')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Enrollment Verification</span></a></li>
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/settings/credentials')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Credentials</span></a></li>
            </ul>
        </div>
    </li>

    @endif
    @if (\App\AdminPermission::sidebar_misc())
    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-settings"></i><span class="m-menu__link-text">MISC</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
        <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
            <ul class="m-menu__subnav">
                @if (\App\AdminPermission::sidebar_upgrade_control())
                {{--  <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/upgrade-control')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Upgrade Control</span></a></li>  --}}
                @endif
                @if(\App\AdminPermission::sidebar_active_override())
                <li class="m-menu__item " aria-haspopup="true"><a href="{{url('/active-override')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Active Override</span></a></li>
                @endif
                @if(\App\AdminPermission::sidebar_subscription_reactivate())
                {{--  <li class="m-menu__item " aria-haspopup="true">
                    <a href="{{url('/subscription-reactivate')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Subscription Reactivate</span></a>
                </li>  --}}
                @endif
                @if(\App\AdminPermission::sidebar_ambassador_reactivate())
                {{--  <li class="m-menu__item " aria-haspopup="true">
                    <a href="{{url('/ambassador-reactivate')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Distribuitor Reactivate</span></a>
                </li>  --}}
                @endif
            </ul>
        </div>
    </li>
    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="https://bitjarlabshelp.freshdesk.com/support/tickets/new" target="_blank" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-settings"></i><span class="m-menu__link-text">IT Support</span></a>
    </li>
    @endif
    @if(\App\AdminPermission::sidebar_subscription_reactivate() && \App\AdminPermission::sidebar_misc() == false)
    <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover"><a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-settings"></i><span class="m-menu__link-text">MISC</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
        <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
            <ul class="m-menu__subnav">
                <li class="m-menu__item " aria-haspopup="true">
                    <a href="{{url('/subscription-reactivate')}}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Subscription Reactivate</span></a>
                </li>
            </ul>
        </div>
    </li>
    @endif
    <!-- Ticket System -->
    @if(\App\AdminPermission::ticket_system())
    {{--  <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
        <a href="{{url('/tickets')}}" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-comment"></i>
        <span class="m-menu__link-text">Ticket System</span></a>
    </li>  --}}
    @endif
    </ul>
</div>
</div>
