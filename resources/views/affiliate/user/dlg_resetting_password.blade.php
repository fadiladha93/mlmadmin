<div class="modal fade" id="dd_reset_pass" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resetting Password</h5>
            </div>
            <div class="modal-body">
                <form id="frmResetPass" class="m-form m-form__section--first m-form--label-align-right">
                    <input type="hidden" name="token" value="{{$token}}" />
                    <div class="form-group m-form__group row">
                        <label class="col-md-5 col-form-label">New Password</label>
                        <div class="col-md-7">
                            <input type="password" class="form-control" name="pass_1">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-md-5 col-form-label">Re-enter new password</label>
                        <div class="col-md-7">
                            <input type="password" class="form-control" name="pass_2">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-info" id="btnSetNewPass">Save</button>
            </div>
        </div>
    </div>
</div>