@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/xccelerate.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner" >
                    <br>
                    <h3>Are you sure you want to pass on this one time offer?</h3>
                    <div class="submit-cart submit-btn-grp" style="margin-top:30px;padding-bottom:20px">
                        <button class="blue-btn" id="btnTicketPurchase">NO</button>
                        <button id="btnTicketPurchaseSkip" class="yellow-btn">YES</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

