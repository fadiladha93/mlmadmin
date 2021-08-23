@include('affiliate.layouts.new_modal_style');
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner thankyou-section">
                    <h3>Thank you! Your order is complete</h3>
                    <br>
                    <br>
                    <div class="return-back">
                        <button id="redirectDashboard" class="yellow-btn">RETURN TO DASHBOARD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



