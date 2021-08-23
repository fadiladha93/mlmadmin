@extends('affiliate.layouts.main')
@section('main_content')
@include('affiliate.ewallet.dlg_payap_config')
<div class="m-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                My E-Wallet
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding-top:15px;">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            @if($error_address == true)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    We don't have your country in the Primary Address section of your profile. Please update your info to proceed.
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6 text-center">
                            <div id="divBalance">
                                ${{number_format($balance, 2)}}
                            </div>
                            <div>
                                @if($payout_type == 'iPayout')
                                        <a href="{{config('api_endpoints.eWalletMerchantURL')}}" target="_blank"
                                           class="btn m-btn--pill btn-info m-btn m-btn--custom m-btn--hover-info">Launch
                                            iPayout Account</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 text-center">
                            @if($error_address != true)
                                <div id="divTransferAm">
                                    <div>Enter Amount to Transfer</div>
                                    <input type="text" id="transferAmt" class="form-control"/>
                                    <div>A ${{number_format($fee, 2)}} service fee will apply<br/>to all transfers</div>
                                </div>
                                <div>
                                    @if($payout_type == 'Payap')
                                        <button id="btnTranferToPayap"
                                                class="btn m-btn--pill btn-info m-btn m-btn--custom m-btn--hover-info">
                                            Transfer
                                        </button>
                                    @elseif($payout_type =='iPayout')
                                        <button id="btnTranferToIPayout"
                                                class="btn m-btn--pill btn-info m-btn m-btn--custom m-btn--hover-info">
                                            Transfer
                                        </button>
                                    @endif
                                </div>
                                @endif

                        </div>
                    </div>
                </div>
            </div>

            @if($found1099)
                <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg" >
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Tax Documents
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body" style="padding-top:15px;">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                @if(isset($error2fa) && $error2fa == true)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row" style="padding-top:40px; padding-bottom: 40px;">

                            <div class="col-sm-4 text-center">
                                <div>
                                    <a href="#" id="pdfButton"><img src="{{asset('/assets/images/pdf_icon.png')}}" alt=""></a>
                                </div>

                            </div>
                            <div class="col-sm-8">
                                <div>
                                    <h5 class="font-weight-bold">2019 1099 Form B</h5>
                                    <div>Your 1099 form is available for download. Click the icon and verify your identity to access your file.</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Transfer History
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <button type="button" class="btn m-btn--pill    btn-secondary btn-sm" tag="{{url('/dlg-transfer-history')}}" tag2="get-transfer-history" id="transferHistoryDetail">Detail</button>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Completed</th>
                                <th style="text-align:center;">In / Out</th>
                                <th style="text-align:right;">Amount</th>
                                <th style="text-align:right;">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trans as $tran)
                                <tr>
                                    <td>{{$tran->created_at}}</td>
                                    <td align="center">
                                        @if($tran->type == App\EwalletTransaction::TYPE_DEPOSIT
                                                || $tran->type == App\EwalletTransaction::TYPE_CODE_REFUND
                                                || $tran->type == App\EwalletTransaction::ADJUSTMENT_ADD
                                                || $tran->type == App\EwalletTransaction::SUBSCRIPTION_REFUND
                                                || $tran->type == App\EwalletTransaction::TYPE_TSB_COMMISSION
                                                || $tran->type == App\EwalletTransaction::TYPE_REFUND)
                                            <span class="m-badge m-badge--success m-badge--wide">In</span>
                                        @else
                                            <span class="m-badge m-badge--danger m-badge--wide">Out</span>
                                        @endif
                                    </td>
                                    <td align="right">{{number_format($tran->amount, 2)}}</td>
                                    <td align="right">{{number_format($tran->closing_balance, 2)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="2FactorDialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-header m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet__head m--align-center">
                <h3 class="m-portlet__head-text">Verification</h3>
            </div>
            <div class="modal-body">
                <div id="test">
                    <div class="col-md-12">A 7 digit confirmation code has been sent via SMS / text to the
                        mobile number you provided. Please enter it here.
                    </div>
                    <div class="col-md-6 offset-md-3 mt-3">
                        <input type="text" class="form-control" id="verificationCode" maxlength="7"
                               oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                    <div class="col-md-12 mt-4">
                        <button type="button" id="btnSubmit2FAEWallet" class="btn mb-2 btn-orange">Submit</button>
                        <button type="button" data-dismiss="modal" class="btn btn-grey mb-2">Cancel</button>
                    </div>
                    <div class="col-md-12 mt-3">
                        <span id="btnResend2FAEWallet" class="font-weight-bold span-link">Resend My Code</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
