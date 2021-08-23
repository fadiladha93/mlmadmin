@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <h3><strong>Add new discount <br> coupon</strong></h3>
                    <div class="card-wrap" id="frmAddNewVoucher">
                        <div class="" style="margin-top: 10px">
                            <div class="card-field">
                                <div class="card-field-full">
                                    <label>Code <span class="req">*</span></label>
                                    <input id='code' class="input-box" name="code" value="{{$code}}"
                                           readonly="readonly">
                                </div>
                                <div class="card-field-full" style="margin-top: 10px;">
                                    <label>Discount Amount <span class="req">*</span></label>
                                    {{--<input type="text" name="last_name" class="input-box">--}}
                                    <select class="input-box" name="product_id">
                                        @foreach($prepaid_products as $p)
                                            <option value="{{$p->id}}">{{$p->productname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="submit-cart submit-btn-grp">
                            <input type="hidden" name="type" value="ajaxModal">
                            <button id="btnAddNewDiscount" class="yellow-btn">SAVE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



