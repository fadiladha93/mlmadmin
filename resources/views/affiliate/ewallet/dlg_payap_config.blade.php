<div class="modal fade" id="dd_payap_config" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter your Payap mobile number</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 alert alert-danger text-center">
                        Payap number with country code without + and without spaces
                    </div>
                </div>
                <form id="frmMyPayap" class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-5 col-form-label">Payap mobile number</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="payap_mobile">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-info" id="btnSavePayap">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dd_payap_config_ssn" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tax Information</h5>
            </div>
            <div class="modal-body">
                <form id="frmMyPayapSSN" class="m-form m-form__section--first m-form--label-align-right">
                    <div class="form-group m-form__group row">
                        <label class="col-md-5 col-form-label">SSN/EIN/FID</label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="ssn" value="{{Auth::user()->ssn}}">
                        </div>
                    </div>
                    {{--<div class="form-group m-form__group row">--}}
                        {{--<label class="col-md-5 col-form-label">EIN/FID</label>--}}
                        {{--<div class="col-md-7">--}}
                            {{--<input type="text" class="form-control" name="ein_or_fid" value="{{Auth::user()->fid}}">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-info" id="btnSavePayapSSN">Save</button>
            </div>
        </div>
    </div>
</div>
