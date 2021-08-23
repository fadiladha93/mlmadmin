<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Update payout method</h5>
        </div>
        <div class="modal-body">
            <div class="m-form m-form__section--first m-form--label-align-right" id="frmUpdatePayoutMethod">
                <input type="hidden" name="country_id" value="{{$id}}"/>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">Payout Type</label>
                    <div class="col-md-8">
                        <select class="form-control" name="payout_method">
                            <option value="" {{(empty($pay_type)?'selected':'')}}></option>
                            <option value="iPayout" {{(!empty($pay_type) && $pay_type->type =='iPayout'?'selected':'')}}>
                                iPayout
                            </option>
                            {{--  <option value="Payap" {{(!empty($pay_type) && $pay_type->type =='Payap'?'selected':'')}}>Payap  --}}
                            </option>
                            <option value="payquicker" {{(!empty($pay_type) && $pay_type->type =='payquicker'?'selected':'')}}>PayQuicker
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label"></label>
                    <div class="col-md-8">
                        <button class="btn btn-danger btn-sm m-btn--air" id="btnUpdatePayoutMethod">Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
