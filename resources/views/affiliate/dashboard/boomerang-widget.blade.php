{{--  <div class="clearfix"></div>
<div class="row" style="margin: 0em 0em 2em 0em;">
    <div class="bgwhite" style="width:300px;">

        <div class="boomimage" id="img-">
            <img src="{{asset('/assets/images/generic_buumerangimg.png')}}" style="width:100%; height:auto;" />
        </div>
        <div class="boomimage" id="img-igo">
            <img src="{{asset('/assets/images/igo_buumerangimg.png')}}" style="width:100%; height:auto;" />
        </div>
        <div class="boomimage" id="img-vibe-rider">
            <img src="{{asset('/assets/images/viberider_buumerangimg.png')}}" style="width:100%; height:auto;" />
        </div>
        <div class="boomimage" id="img-vibe-driver">
            <img src="{{asset('/assets/images/vibedriver_buumerangimg.png')}}" style="width:100%; height:auto;" />
        </div>
    </div>
    <div class="col bgwhite">
        <div class="status">
            <div class="logo-to">
                <img src="assets/images/icon-sm.png" alt="">
            </div>
            <div class="status-des dashed-b hseven">
                <ul>
                    <li>
                        <h3>Available büümerangs</h3>
                        <p>excluding active</p>
                    </li>
                    <li>
                        <h2>{{$boom_available}}</h2>
                    </li>
                </ul>
            </div>
            <div class="status-des">
                <ul>
                    <li>
                        <h3>Pending büümerangs</h3>
                        <p>active links</p>
                    </li>

                    <li>
                        <h2>{{$boom_pending}}</h2>
                    </li>
                </ul>
            </div>

            @if($show_reload_button) <button id="btnCheckOut" class="tr-btn blue-c">Reload b&#252;&#252;merangs</button>
                @endif
                <button id="btnBoomerangReport" class="tr-btn blue-c">b&#252;&#252;merangs Report</button>
        </div>
    </div>
    <div class="col-md-4 bgwhite">
        <div style="width:100%;">
            <ul class="top-selection">
                <li>
                    <h4>Type of büümerang: </h4>
                </li>
                <li>
                    <div id="selectProduct" class="custom-select2">
                        <select class="my_select_box" id="buumerangProduct">
                            <option value="" selected="selected">...</option>
                            @if (Auth::user()->current_product_id != \App\Product::ID_VIBE_OVERDRIVE_USER)
                            <option value="igo">iGo</option>
                            @endif
                            <option value="vibe-rider">VIBE Rider</option>
                            <option value="vibe-driver">VIBE Driver</option>
                        </select>
                    </div>
                </li>
            </ul>
            <div class="tab-area" id="buumerangTypeSelection" style="display: none;">
                <div class="tab">
                    <button class="tablinks active" id="btnBoom_inv" data-toggle="tab" role="tab"
                        onclick="openNum(event, 'divBoomInd')">Individual</button>
                    <button class="tablinks" id="btnBoom_group" data-toggle="tab" role="tab"
                        onclick="openNum(event, 'divBoomGroup')">Group</button>
                </div>
            </div>

            <!-- individual -->
            <div id="divBoomInd">
                <input id="buumIndProd" class="buumerang-product" type="hidden" name="buumerang_product">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="label-bold" for="firstname">FIRST NAME*</label>
                            <input id="indFname" type="text" name="firstname" class="smaller-input" tabindex="1">
                            <label class="label-bold" for="email">EMAIL*</label>
                            <input id="txtSendEmail" type="email" name="email" class="smaller-input" tabindex="3">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="label-bold" for="lastname">LAST NAME*</label>
                            <input id="indLname" type="text" name="lastname" class="smaller-input" tabindex="2">
                            <label class="label-bold" for="mobile">MOBILE PHONE*</label><br>
                            <input type="tel" id="txtSendSMS" name="mobile" class="smaller-input" tabindex="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="label-bold" for="exp_date">EXPIRATION DATE*</label><br>
                            <select id="indExpDate" name="exp_date" class="selectpad" tabindex="5">
                                <option value="">Select...</option>
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
                        <br>
                        <button id="btnGenBoom_Ind" class="btn-block sub-button" tabindex="6">Generate Code</button>
                        <div style="margin-top:10px;"><a tag="{{url('/boomerang-instructions')}}" class="showDlg_s"
                                style="cursor:pointer;">Instructions</a></div>
                    </div>
                    <div class="col-6 text-center">
                        <label class="label-bold">b&#252;&#252;merang Code *</label>
                        <input id="boomCode_Ind" readonly="readonly" type="text" class="form-control" />
                    </div>
                </div>
            </div>
            <!-- group -->
            <div id="divBoomGroup" style="display:none;">
                <div class="row">
                    <div class="col-12">
                        <input class="buumerang-product" type="hidden" name="buumerang_product">
                        <label class="label-bold label-upper">Campaign Name *</label><br>
                        <input type="text" name="campaign_name" class="smaller-input" tabindex="1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="label-bold label-upper pad-left-80">Expiration Date*</label><br>
                            <select name="exp_date" class="selectpad" tabindex="2">
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
                        <div class="form-group">
                            <label class="label-bold label-upper">Number of uses *</label>
                            <input type="text" name="no_of_uses" class="smaller-input" tabindex="3">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom:20px;">
                    <div class="col-6 text-center">
                        <br>
                        <button id="btnGenBoom_Group" class="sub-button btn-block" tabindex="3">Generate Code!</button>
                        <div style="margin-top:10px;"><a tag="{{url('/boomerang-instructions')}}" class="showDlg_s"
                                style="cursor:pointer;">Instructions</a></div>
                    </div>
                    <div class="col-6 text-center">
                        <label class="label-bold">b&#252;&#252;merang Code *</label>
                        <input id="boomCode_group" readonly="readonly" type="text" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col bgwhite">
        <div class="more-info text-center" style="width:100%;">
            <h4>Send büümerangs!</h4>
            <p>Choose a method to send</p>
            <div class="card-body">
                <button id="btnSendSMS" type="submit" class="tr-btn blue-c">Send as SMS</button>
                <button id="btnSendEmail" class="tr-btn blue-c">Send as Email</button>
                <button data-clipboard-target="#boomCode_Ind" class="tr-btn blue-c" id="btnCopyInd">Copy to
                    clipboard</button>
                <button data-clipboard-target="#boomCode_group" class="tr-btn blue-c" id="btnCopyGroup"
                    style="display:none;">Copy to clipboard</button>
            </div>
        </div>
    </div>
</div><!-- row -->
<div class="clearfix"></div>



<script>
    function openNum(evt, numName) {
        var i, tabcontent, tablinks;

        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
    }
</script>
  --}}
