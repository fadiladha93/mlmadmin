<header id="m_header" class="m-grid__item	m-grid m-grid--desktop m-grid--hor-desktop  m-header ">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--hor-desktop m-container m-container--responsive m-container--xxl">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-header__wrapper">

            <div class="m-grid__item m-brand">
                <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="{{url('/')}}" class="m-brand__logo-wrapper">
                            <img alt="" src="{{asset('/assets/images/logo.png')}}"  />
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">

                        <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>

                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="m-grid__item m-grid__item--fluid m-header-head" id="m_header_nav">
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            @if (!\utill::isNullOrEmpty(session('login_from_admin')))
                            <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light">
                                <a href="{{url('/go-to-admin')}}" class="m-nav__link m-dropdown__toggle" data-toggle="m-tooltip" title="Return to admin dashboard" data-placement="left">
                                    <span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                    <span class="m-nav__link-icon">
                                        <span class="m-nav__link-icon-wrapper">
                                            <i class="flaticon-user"></i>
                                        </span>
                                    </span>
                                </a>
                            </li>
                            @endif
                            @if(!empty(Auth::user()) &&  Auth::user()->account_status != \App\User::ACC_STATUS_SUSPENDED)

                                    <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light">
                                        <a id="btnIbuumFoundation" style="cursor:pointer;" class="m-nav__link m-dropdown__toggle"
                                           data-toggle="m-tooltip" title="büüm foundation" data-placement="left">
                                            <span
                                                class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                            <span class="m-nav__link-icon">
                                        <span class="m-nav__link-icon-wrapper">
                                            <img src="{{asset('/assets/images/ibuum-donation.png')}}" width="35px" />
                                        </span>
                                    </span>
                                        </a>
                                        @if (Auth::user()->current_product_id != \App\Product::ID_VIBE_OVERDRIVE_USER)
                                    </li>
				   <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light">
                                    <a id="btnIdecide" style="cursor:pointer;" class="m-nav__link m-dropdown__toggle"
                                       data-toggle="m-tooltip" title="Go to iDecide" data-placement="left">
                                        <span
                                            class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                        <span class="m-nav__link-icon">
                                        <span class="m-nav__link-icon-wrapper" style="width:150px;">
                                            <img src="{{asset('/assets/images/iDECIDE_SSO.png')}}" width="120px" />
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light">
                                    <a id="btnIgo" style="cursor:pointer;" class="m-nav__link m-dropdown__toggle"
                                       data-toggle="m-tooltip" title="Go to iGo" data-placement="left">
                                            <span
                                                class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                        <span class="m-nav__link-icon">
                                        <span class="m-nav__link-icon-wrapper">
                                            <img src="{{asset('/assets/images/igo-logo.png')}}" width="35px" />
                                        </span>
                                    </span>
                                    </a>
                                </li>
                                    @endif
                                <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light">
                                    <a href="{{url('/logout')}}" class="m-nav__link m-dropdown__toggle"
                                       data-toggle="m-tooltip" title="Logout" data-placement="left">
                                            <span
                                                class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                        <span class="m-nav__link-icon">
                                        <span class="m-nav__link-icon-wrapper">
                                            <i class="flaticon-logout"></i>
                                        </span>
                                    </span>
                                    </a>
                                </li>
                            @endif

                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
                                m-dropdown-toggle="click">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-topbar__username m--hidden-tablet m--hidden-mobile m--padding-right-15"><span class="m-link">{{App\User::getLoginUserName()}}<br/>{{App\User::getLoginUserTSA()}}</span></span>
                                    <span class="m-topbar__userpic">
                                        <img src="{{asset('/assets/images/user.png')}}" class="m--img-rounded m--marginless m--img-centered" alt="" />
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center" style="background: url(<?php echo asset('/assets/images/user_profile_bg.jpg'); ?>); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">
                                                    <img src="{{asset('/assets/images/user.png')}}" class="m--img-rounded m--marginless" alt="" />
                                                </div>
                                                <div class="m-card-user__details">
                                                    <span class="m-card-user__name m--font-weight-500" style="color:#ffffff;">{{App\User::getLoginUserName()}}<br/>{{App\User::getLoginUserTSA()}}</span>
                                                    <a href="" class="m-card-user__email m--font-weight-300 m-link" style="color:#ffffff;"><?php echo App\User::getLoginUserEmail(); ?></a>
                                                    <a id="current-user-events-token" style="display: none;" href="" ><?php echo App\User::getRememberTokenForEvents(); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__section m--hide">
                                                        <span class="m-nav__section-text">Section</span>
                                                    </li>
                                                    @if(!empty(Auth::user()) && Auth::user()->account_status != \App\User::ACC_STATUS_SUSPENDED)
                                                        <li class="m-nav__item">
                                                            <a href="{{url('/my-profile')}}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                <span class="m-nav__link-title">
                                                                <span class="m-nav__link-wrap">
                                                                    <span class="m-nav__link-text" style="color:#313131 !important;">My Profile</span>
                                                                </span>
                                                            </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="{{url('/change-password')}}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                                <span class="m-nav__link-text"
                                                                      style="color:#313131 !important;">Change password</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li class="m-nav__separator m-nav__separator--fit">
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="{{url('/logout')}}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">Logout</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
