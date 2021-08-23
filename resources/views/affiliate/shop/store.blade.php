@extends('affiliate.layouts.main')

@section('main_content')

    <div class="m-content">

        <div class="row">
            <div class="col-lg-12">
                <img src="{{asset('/assets/images/tools_banner_os.jpg')}}" width="100%"/>
            </div>
        </div>

        <div class="m-portlet m-portlet--mobile" id="ibuumerang_Shop" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            XCCELERATE 2020 IGO TRAVEL PACK CONVENTION SPECIAL
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body ri-wrapper">

                <div class="row" style="font-size: 14px;">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <strong>Your special bundle includes:</strong><br>
                        &bull; 8 x 12 Customer Brochure<br>
                        &bull; 8 x 12 Business Brochure<br>
                        &bull; 8 x 12 Flyer (2-sided)<br>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <br>
                        &bull; 5 x 5 Flyer (2-sided)<br>
                        &bull; Tri-fold Brochure<br>
                        &bull; Bussiness Card size Flyer<br>
                    </div>
                </div>

                <div class="row" style="font-size: 14px; margin-top:8px;">
                    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
                        This bundle is available at Xccelerate only and will be shipped to you.
                        <br>Please email gear@ibuumerang.com with any questions about your order.
                    </div>
                </div>

                <div class="row" style="padding:20px; padding-bottom:100px;">
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 text-center">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-center">
                                <img style="height: 48%;" src="/assets/images/promo-ticket.png"/>
                                <h3 class="ticket-title">ENGLISH</h3>
                                <button id="btnCheckOutSalesToolsEng" style="margin-top:8px;" class="btn-outline-blue text-center">BUY NOW</button>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-center">
                                <img style="height: 48%;" src="/assets/images/promo-ticket.png"/>
                                <h3 class="ticket-title">SPANISH</h3>
                                <button id="btnCheckOutSalesToolsSpan" style="margin-top:8px;" class="btn-outline-blue text-center">BUY NOW</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            // $.ajax({
            //     url: '/check-ticket-purchased',
            //     type: 'GET',
            //     data: '',
            //     success: function (data) {
            //         if (data['v']) {
            //             $("#dd_ticket_checkout").html(data['v']);
            //             $("#dd_ticket_checkout").modal("show");
            //         }
            //     }
            // });
        })
    </script>
@endsection
