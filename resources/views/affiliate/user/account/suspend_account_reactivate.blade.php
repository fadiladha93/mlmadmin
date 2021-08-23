@include('affiliate.layouts.new_modal_style');
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <br>
                    <h3>Your account is currently suspended</h3>
                    <p>Would you like to reactivate your account?</p>
                    <br>
                    {{--<div class="submit-cart submit-btn-grp">--}}
                        {{--<button class="blue-btn" data-dismiss="modal">CLOSE--}}
                        {{--</button>--}}
                        {{--<button id="btnSubscriptionReactivateSubmitButton" class="yellow-btn">SUBMIT</button>--}}
                    {{--</div>--}}
                    <div class="return-back submit-cart submit-btn-grp">
                        <button class="blue-btn" data-dismiss="modal">Cancel</button>
                        <button id="reactivate-subscription-suspended-user" class="yellow-btn">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



