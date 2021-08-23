@include('affiliate.layouts.new_modal_style');
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner thankyou-section">
                    @if ($product->id == 15)
                        <h3>Thank you! Your order is complete</h3>
                        <br>
                        <p>Your new büümerangs should be in your inventory
                            and available for sending now! Enjoy your purchase!</p>
                    @elseif ($product->id == 53)
                        <h3>Congratulations! Your order is complete</h3>
                        <br>
                        <p>Thank you for ordering the Xccelerate 2020 Digital Photo Album Special! Your item will be emailed to you."</p>
                    @else
                        <h3>Thank you! Your order is complete</h3>
                        <br>
                        <p>Enjoy your purchase!</p>
                    @endif
                    <div class="return-back">
                        <button id="redirectDashboard" class="yellow-btn">RETURN TO DASHBOARD</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



