{{--  <div class="m-portlet" style="background-color:#000ff;">
    <div class="m-portlet__body" style="padding: 15px;">
        <div class="row" style="background-color:#ff0000;">
            <div class="col" style="border-right:1px solid #ebedf2;">
                <div class="col-md-3">
                    <img src="{{asset('/assets/images/logo.png')}}" width="150px" />
                </div>
                <div class="col-md-3">
                    Boomerangs type
                </div>
                <div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" style="border-right:1px solid #ebedf2;">
                <div>
                    <img src="{{asset('/assets/images/logo.png')}}" width="150px" />
                </div>
                <div class="m-widget4 m-widget4--chart-bottom">
                    <div class="m-widget4__item">
                        <div class="m-widget4__info">
                            <span class="m-widget4__text">
                                Available Boomerangs
                                <div style="font-size:smaller;">Excluding active</div>
                            </span>
                        </div>
                        <div class="m-widget4__ext">
                            <span id="boomCount_avail" class="m-widget4__number m--font-accent">{{$boom_available}}</span>
                        </div>
                    </div>
                    <div class="m-widget4__item">
                        <div class="m-widget4__info">
                            <span class="m-widget4__text">
                                Pending Boomerangs
                                <div style="font-size:smaller;">Active links</div>
                            </span>
                        </div>
                        <div class="m-widget4__ext">
                            <span class="m-widget4__stats m--font-info">
                                <span id="boomCount_pending" class="m-widget4__number m--font-accent">{{$boom_pending}}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    @if($boom_available<200)
                    <button id="btnCheckOut" class="btn m-btn m-btn--air btn-info btn-block">Buy more Boomerangs</button>
                    @endif
                </div>

            </div>
            <div class="col-md-5" style="border-right:1px solid #ebedf2;">
                <div style="margin-left:25%;">
                    <ul class="nav nav-pills nav-pills--info m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                        <li class="nav-item m-tabs__item">
                            <a style="border: 1px solid #36a3f7" class="nav-link m-tabs__link active" id="btnBoom_inv" data-toggle="tab" role="tab">
                                Individual
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a style="border: 1px solid #36a3f7" class="nav-link m-tabs__link" id="btnBoom_group" data-toggle="tab" role="tab">
                                Group
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <div id="divBoomInd" class="m-form__section m-form__section--first">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control m-input">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control m-input">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label>Expiration Date</label>
                                    <select name="exp_date" class="form-control m-input">
                                        <option></option>
                                        <option value="1">1 Day</option>
                                        <option value="3">3 Days</option>
                                        <option value="7">7 Days</option>
                                        <option value="15">15 Days</option>
                                        <option value="30">30 Days</option>
                                        <option value="45">45 Days</option>
                                        <option value="90">90 Days</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:20px;">
                            <div class="col-6 text-center">
                                <a id="btnGenBoom_Ind" class="btn m-btn m-btn--air btn-warning btn-block" style="background-color:#ff6400">Generate Code</a>
                                <div style="margin-top:10px;"><a tag="{{url('/boomerang-instructions')}}" class="showDlg_s" style="cursor:pointer;">Instructions</a></div>
                            </div>
                            <div class="col-6 text-center">
                                <input id="boomCode_Ind" readonly="readonly" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div id="divBoomGroup" class="m-form__section m-form__section--first" style="display:none;">
                        <div class="form-group m-form__group">
                            <label>Campaign Name</label>
                            <input type="text" name="campaign_name" class="form-control m-input">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label>Expiration Date</label>
                                    <select name="exp_date" class="form-control m-input">
                                        <option></option>
                                        <option value="1">1 Day</option>
                                        <option value="3">3 Days</option>
                                        <option value="7">7 Days</option>
                                        <option value="15">15 Days</option>
                                        <option value="30">30 Days</option>
                                        <option value="45">45 Days</option>
                                        <option value="90">90 Days</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group m-form__group">
                                    <label>Number of uses</label>
                                    <input type="text" name="no_of_uses" class="form-control m-input">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:20px;">
                            <div class="col-6 text-center">
                                <a id="btnGenBoom_Group" class="btn m-btn m-btn--air btn-warning btn-block" style="background-color:#ff6400">Generate Code</a>
                                <div style="margin-top:10px;"><a tag="{{url('/boomerang-instructions')}}" class="showDlg_s" style="cursor:pointer;">Instructions</a></div>
                            </div>
                            <div class="col-6 text-center">
                                <input id="boomCode_group" readonly="readonly" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <span>
                    <div style="font-size: 18px;font-weight:400;">Send Boomerangs</div>
                    <div>Choose a method to send</div>
                </span>
                <div style="margin-top:10px;">
                    <button id="btnSendSMS" class="btn m-btn m-btn--air btn-info btn-block">Send as SMS</button>
                    <input type="text" id="txtSendSMS" class="form-control" placeholder="Mobile Number" />
                </div>
                <div style="margin-top:10px;">
                    <button id="btnSendEmail" class="btn m-btn m-btn--air btn-info btn-block">Send as Email</button>
                    <input type="text" id="txtSendEmail" class="form-control" placeholder="name@email.com" />
                </div>
                <div style="margin-top:10px;">
                    <button data-clipboard-target="#boomCode_Ind" class="btn m-btn m-btn--air btn-info btn-block" id="btnCopyInd">Copy to clipboard</button>
                    <button data-clipboard-target="#boomCode_group" class="btn m-btn m-btn--air btn-info btn-block" id="btnCopyGroup" style="display:none;">Copy to clipboard</button>
                </div>
            </div>
        </div>
    </div>
</div>  --}}
