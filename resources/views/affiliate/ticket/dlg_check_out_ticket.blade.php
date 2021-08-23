@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/xccelerate.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <iframe width="1" height="1" frameborder="0" scrolling="no"
                        src="<?php echo e(Config::get('api_endpoints.KOUNTIFrameURL')); ?>/logo.htm?m=<?php echo e(Config::get('api_endpoints.kount.KOUNTMerchantID')); ?>&s=<?php echo e($sessionId); ?>">
                </iframe>
                <div class="cm-body-inner">
                    <p style="font-weight: 400;  font-size:20px">
                        <br>
                       Here is your one-time offer for your ticket <br>
                        to Xccelerate for just
                    </p>
                    <br>
                    <p style="font-size: 30px; font-weight: bold">
                        $49.<sup>98</sup>
                    </p>
                   <b> Buy now and save over 80%!</b>
                    <br>
                    <p style="font-weight: 400;  font-size:20px">
                        <br>
                        This is a special  offer for those that have enrolled <br>
                        from 09/01/2019 to present... You won't see it again!
                    </p>
                    <div class="submit-cart submit-btn-grp" style="margin-top:30px;padding-bottom:20px">
                        <button class="blue-btn" id="btnSkipTicketPurchase">SKIP</button>
                        <button id="btnCheckoutTicketPurchase" class="yellow-btn">BUY NOW</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




