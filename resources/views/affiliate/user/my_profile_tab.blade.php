<div class="row" style="margin-top:20px;">
    <div class="col-md-12">
        <ul class="nav nav-tabs m-tabs-line m-tabs-line--info m-tabs-line--2x" role="tablist">
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'basic') active @endif" href="{{url('/my-profile')}}">
                    Basic Information
                </a>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'replicated') active @endif" href="{{url('/my-profile/replicated')}}">
                    Replicated Site Preferences
                </a>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'placement-preference') active @endif" href="{{url('/placement-preference')}}">
                    Binary Placement
                </a>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'address') active @endif" href="{{url('/my-profile/primary-address')}}">
                    Primary address
                </a>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'billing-address') active @endif"
                   href="{{url('/my-profile/billing-address')}}">
                    Billing address
                </a>
            </li>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'idecide') active @endif" href="{{url('/my-profile/idecide')}}">
                    iDecide
                </a>
            </li>
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link @if($tab == 'billing') active @endif" href="{{url('/my-profile/billing')}}">
                    Billing
                </a>
            </li>

        </ul>
    </div>
</div>
