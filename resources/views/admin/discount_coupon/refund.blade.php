@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Refund Unused Voucher
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row" id="frmFindVoucher">
                            <div class="col-md-12">
                                <form id="frmRefundVoucher">
                                    <div class="m-form m-form__section--first m-form--label-align-right">
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-6 col-form-label">Voucher Code</label>
                                            <div class="col-md-6">
                                                <input class="form-control col-md-6" id="voucher" name="voucher">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <div class="col-md-12 offset-md-6">
                                                <a class="btn btn-info btn-sm m-btn--air" id="btnFindVoucher">Submit</a>&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="voucherRefundSection" class="m--hide">
                            <div class="col-md-12">
                                <div class="m-form m-form__section--first m--align-center">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-12 col-form-label ">You are about to refund voucher <span id="voucherCode"></span> to the original purchaser</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="m-form m-form__section--first m-form--label-align-right">
                                    <div class="form-group m-form__group row" style="padding-bottom:0!important;">
                                        <label class="col-md-6 col-form-label font-weight-bold m-form--label-align-right">TSA#:</label>
                                        <span class="col-md-6 col-form-label d-inline font-weight-bold m-form--label-align-left"><span id="tsaNumber"></span></span>
                                    </div>
                                </div>
                                <div class="m-form m-form__section m-form--label-align-right">
                                    <div class="form-group m-form__group row" style="padding-bottom:0!important;">
                                        <label class="col-md-6 col-form-label font-weight-bold">Name:</label>
                                        <span class="col-md-6 col-form-label font-weight-bold"><span id="fullName"></span></span>
                                    </div>
                                </div>
                                <div class="m-form m-form__section m-form--label-align-right">
                                    <div class="form-group m-form__group row">
                                        <label class="col-md-6 col-form-label font-weight-bold">Amount:</label>
                                        <span class="col-md-6 col-form-label font-weight-bold"><span id="amount"></span></span>
                                    </div>
                                </div>
                                <div class="m-form m-form__section" style="align: center;">
                                    <div class="form-group m-form__group row text-center">
                                        <div class="col-md-12 text-center">
                                            <form id="refundVoucherSubmitForm">
                                                <input type="hidden" id="orderId" value="">
                                                <a class="btn btn-light border-secondary btn-sm col-md-2" id="btnRefundVoucher">Cancel</a>&nbsp;
                                                <a class="btn btn-info btn-sm col-md-2 m-btn--air" id="btnRefundVoucher">Refund</a>
                                            </form>
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
@endsection
