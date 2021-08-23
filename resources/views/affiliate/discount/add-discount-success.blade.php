@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <h3>Thank you! Your order is completed. </h3>
                    <br>
                    <p>Here is your coupon code</p>
                    <center><br><b>{{$code}}</b></center>
                    <div class="return-back">
                        <button id="redirectDashboard" class="yellow-btn">RETURN TO DASHBOARD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



