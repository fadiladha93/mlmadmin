@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Transfer of Ownership
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="m-portlet">
                            <div class="m-form">
                                <div class="m-portlet__body">
                                    <div class="m-form m-form--label-align-right"  id="frmTransferUser">
                                        <input type="hidden" id="is_confirmed" name="is_confirmed" value="0">
                                        <div class="form-group m-form__group row mb-5">
                                            <label class="col-md-4 col-form-label"><b>ISBO Transferring</b></label>
                                            <div class="col-md-8" id="tsaTransfer">
                                                <select class="form-control m-select2" id="select4_sponsor" name="distid">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <p class="mb-4" style="margin-left: 10vw;"><b>Recipient Info</b></p>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">First Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="firstname">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Last Name</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="lastname">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Address</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="address">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">City</label>
                                            <div class="col-md-8">
                                                <div class="form-inline">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="city" id="city" size="15">
                                                    </div>

                                                    <div class="form-group pl-2">
                                                        <label class="col-form-label pl-3 pr-3">State</label>
                                                        <input type="text" class="form-control" name="stateprov" id="state" size="15">
                                                    </div>

                                                    <div class="form-group pl-2">
                                                        <label class="col-form-label pl-3 pr-3">Postal Code</label>
                                                        <input type="text" class="form-control" name="postalcode" id="postalcode" size="8">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Country</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="country_code">
                                                    <option></option>
                                                    @foreach($countries as $c)
                                                        <option value="{{$c->countrycode}}">{{$c->country}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Phone</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="phonenumber">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Email</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="email">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Username</label>
                                            <div class="col-md-8">
                                                <input class="form-control" name="username">
                                            </div>
                                        </div>
                                        <div class="form-group row" style="margin-left: 30vw; margin-top: 5px;">
                                            <button type="button" class="btn" style="background-color: #52b3e6; color: white;" id="btnTransferUserOwnership">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="transferConfirmationDialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #52b3e6;">
                    <h5 class="modal-title" style="color: white;">Confirm Action</h5>
                </div>
                <div class="modal-body">
                    <p>You are about to make a change to transfer the ownership of this account, please review before submitting.</p>
                    <div style="text-align: center; margin-top: 20px;">
                        <h5 id="tsaNumber">TSA #:</h5>
                        <h5 id="name">Recipient:</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn" id="submitBtn" style="background-color: #52b3e6; color: white;">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="2FactorDialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <div class="modal-header" style="background-color: #52b3e6;">
                    <h5 class="modal-title" style="color: white;">2 Factor Authentication</h5>
                </div>
                <div class="modal-body">
                    <div id="frm2FA">
                        <div class="col-md-12">A 7 digit confirmation code has been sent via SMS / text. Please enter it here.</div>
                        <div class="col-md-6 offset-md-3 mt-3">
                            <input type="text" class="form-control" id="verificationCode" maxlength="7"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-md-12 mt-4">
                            <button type="button" id="btnSubmit2FA" class="btn mb-2" style="background-color: #52b3e6; color: white;">Submit</button>
                            <button type="button" id="btnResend2FA" class="btn mb-2" style="background-color: dimgray; color: white;">Resend Code</button>
                            <button type="button" data-dismiss="modal" class="btn mb-2">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
