<div>
    Please confirm your Promotional Package
</div>
<div class="row" style="margin:15px 0px;">
    <div class="col-2 offset-3 text-center parentDiv">
        <img src="{{asset('/assets/images/q_coach_class.png')}}" class="imgUpg"/>
        <div>
            <label class="m-radio" style="padding-left: 0px;">
                <input class="cbxMyPackage" @if($my_package == 2) checked @endif type="radio" value="2" name="my_package">
                <span></span>
            </label>
        </div>
    </div>
    <div class="col-2 text-center parentDiv">
        <img src="{{asset('/assets/images/q_business_class.png')}}" class="imgUpg"/>
        <div>
            <label class="m-radio" style="padding-left: 0px;">
                <input class="cbxMyPackage" @if($my_package == 3) checked @endif type="radio" value="3" name="my_package">
                <span></span>
            </label>
        </div>
    </div>
    <div class="col-2 text-center parentDiv">
        <img src="{{asset('/assets/images/q_first_class.png')}}" class="imgUpg"/>
        <div>
            <label class="m-radio" style="padding-left: 0px;">
                <input class="cbxMyPackage" @if($my_package == 4) checked @endif type="radio" value="4" name="my_package">
                <span></span>
            </label>
        </div>
    </div>
</div>
<div style="margin-top:15px;">
    <div class="pull-left">
        <a class="btn m-btn m-btn--air btn-warning btnStartAgain">Start Over</a>
    </div>
    <div class="pull-right">
        <a currentStep="4" id="btnStep4" class="btn m-btn m-btn--air btn-warning btnNextStep">{{$button_name}}</a>
    </div>
    <div class="clearfix"></div> 
</div>