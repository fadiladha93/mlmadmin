<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <img src="{{asset('/assets/images/logo.png')}}" width="120px;"  />
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="nav nav-pills nav-pills--info m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link active" id="btnBoom_inv" data-toggle="tab" role="tab">
                        Individual
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link" id="btnBoom_group" data-toggle="tab" role="tab">
                        Group
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-md-4">
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
                <div class="alert m-alert m-alert--default">
                    Generate your code. Copy the code to your clipboard, then send your prospect to 'igobuum.com' and have them redeem this 6 digit code.
                </div>
                <div>
                    {{--<a id="btnCheckOut" class="btn m-btn m-btn--air btn-info btn-block">Buy more Boomerangs</a>--}}
                </div>
            </div>
            <div class="col-md-5">
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
                    <div class="row">
                        <div class="col-6">
                            <a id="btnGenBoom_Ind" class="btn m-btn m-btn--air btn-warning btn-block">Generate Code</a>
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
                    <div class="row">
                        <div class="col-6">
                            <a id="btnGenBoom_Group" class="btn m-btn m-btn--air btn-warning btn-block">Generate Code</a>
                        </div>
                        <div class="col-6 text-center">
                            <input id="boomCode_group" disabled="disabled" type="text" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center" style="margin-top:10px;">
                <span>
                    <div style="font-size: 1rem;font-weight: 500;">Send Boomerangs</div>
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
                    <button data-clipboard-target="#boomCode_Ind" class="btn m-btn m-btn--air btn-warning btn-block btnCopy">Copy to clipboard</button>
                </div>
            </div>
        </div>
    </div>
</div>