<div class="m-portlet m-portlet--fit" id="upgrades">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text chartHeader">
                    Latest Updates
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body" style="padding:10px;">
        <div class="m-widget4 m-widget4--chart-bottom">
            @if($showUpgradeBtn)
            <div class="row up-sec-1">
                <div class="col-sm-6">
                    <span class="ug-title">
                        Current Package
                    </span>
                    <span class="ug-value">{{$currentPackageName}}</span>
                </div>
                <div class="col-sm-6">
                    <span class="ug-title">
                        Upgrade Timer
                    </span>
                    <span id="upgradeCountdown" class="ug-value"></span>
                </div>
            </div>
            @else
            <div class="m-widget4__item">
                <div class="m-widget4__info">
                    <span class="m-widget4__text">
                        Current Package
                    </span>
                </div>
                <div class="m-widget4__ext">
                    <span class="m-widget4__number m--font-accent">{{$currentPackageName}}</span>
                </div>
            </div>
            @endif
            @if($showUpgradeBtn)
            <div class="row frmUpgrade">
                @if($showVibeOverdrive)
                    <div class="col-{{$colWidth}} text-center parentDiv">
                        <img src="{{asset('/assets/images/vibe_od_product.png')}}" class="imgUpg"/>
                        <div>
                            <label class="m-radio" style="padding-left: 0px;">
                                <input type="radio" value="{{ App\Product::ID_VIBE_OVERDRIVE_USER }}" name="my_package">
                                <span></span>
                            </label>
                        </div>
                    </div>
                @endif
                @if($showCoachClass)
                <div class="col-{{$colWidth}} text-center parentDiv">
                    <img src="{{asset('/assets/images/coach_class.jpg')}}" class="imgUpg"/>
                    <div>
                        <label class="m-radio" style="padding-left: 0px;">
                            <input type="radio" value="{{App\Product::ID_BASIC_PACK}}" name="my_package">
                            <span></span>
                        </label>
                    </div>
                </div>
                @endif
                @if($showBusinssClass)
                <div class="col-{{$colWidth}} text-center parentDiv">
                    <img src="{{asset('/assets/images/business_class.jpg')}}" class="imgUpg"/>
                    <div>
                        <label class="m-radio" style="padding-left: 0px;">
                            <input type="radio" value="{{App\Product::ID_VISIONARY_PACK}}" name="my_package">
                            <span></span>
                        </label>
                    </div>
                </div>
                @endif
                @if($showFirstClass)
                <div class="col-{{$colWidth}} text-center parentDiv">
                    <img src="{{asset('/assets/images/first_class.png')}}" class="imgUpg"/>
                    <div>
                        <label class="m-radio" style="padding-left: 0px;">
                            <input type="radio" value="{{App\Product::ID_FIRST_CLASS}}" name="my_package">
                            <span></span>
                        </label>
                    </div>
                </div>
                @endif
                @if($showPremiumFC)
                <div class="col-{{$colWidth}} text-center parentDiv">
                    <img src="{{asset('/assets/images/premium_fc.png')}}" class="imgUpg"/>
                    <div>
                        <label class="m-radio" style="padding-left: 0px;">
                            <input type="radio" value="{{App\Product::ID_PREMIUM_FIRST_CLASS}}" name="my_package">
                            <span></span>
                        </label>
                    </div>
                </div>
                @endif
            </div>
            @endif
            @if($showUpgradeBtn)
            <div class="row" style="margin-top:20px;">
                <div class="col-sm-12">
                    <div class="m--align-right">
                        <button type="button" id="btnUpgradeNow" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--hover-info btn-block">Upgrade Now !</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
