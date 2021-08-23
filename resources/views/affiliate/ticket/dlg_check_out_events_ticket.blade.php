@include('affiliate.layouts.new_modal_style')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <iframe width="1" height="1" frameborder="0" scrolling="no"
                src="<?php echo e(Config::get('api_endpoints.KOUNTIFrameURL')); ?>/logo.htm?m=<?php echo e(Config::get('api_endpoints.kount.KOUNTMerchantID')); ?>&s=<?php echo e($sessionId); ?>">
        </iframe>
        <div class="modal-body dlgQuestionList">
            <div style="padding:10px 0px;">
                <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
            </div>
            {{--<div class="content" style="background: #4aafd1">--}}
            <div class="cm-body" style="background: #4aafd1">
                <div class="cm-body-inner">
                    <h3><strong>Enter the number of tickets you wish to purchase</strong></h3>

                    <form id="frmEventsTicketCheckOut" class="m-form">
                        @csrf
                        <div class="container-fluid">
                            <div style="height:1px; background-color: white; margin-bottom: 20px;"></div>
                            <div class="row h-100 justify-content-center">

                                @foreach($products_by_category as $ticketCategory)

                                <div class="col-sm-12 col-md-6 col-ticket-category" style="margin-bottom: 20px;">

                                    <h3><strong>{{ $ticketCategory->category_product }}</strong></h3>
                                    <div style="margin-bottom: 30px;">
                                        <img class="mx-auto d-block img-fluid img-ticket" src="{{asset('/assets/images/' . $ticketCategory->image)}}">
                                    </div>

                                    @foreach($ticketCategory->products as $decoratedProduct)
                                        <div class="row d-flex h-20" style="margin-bottom: 20px; margin-left: 10px;">


                                            <div class="col-6 text-left align-self-center">
                                                <p class="p-ticket-product-name"><strong>{{  $decoratedProduct->product->productname   }}</strong></p>
                                                <p class="p-ticket-product-desc">{{  $decoratedProduct->product->productdesc   }}</p>
                                            </div>

                                            <div class="col-3 justify-content-center align-self-center">
                                                @if ($decoratedProduct->product->original_price > 0)

                                                <p class="p-ticket-product-original-price" style='color:red; text-decoration:line-through'>
                                                    <span style='color:white'><strong>${{number_format($decoratedProduct->product->original_price,2)}}</strong></span>
                                                </p>
                                                @endif
                                                <p class="p-ticket-product-price">
                                                    <strong>${{number_format($decoratedProduct->product->price,2)}}</strong>
                                                </p>
                                            </div>

                                            <div class="col-3 justify-content-center align-self-center">
                                                <p style="font-size: 9px;">QUANTITY</p>
                                                <input type="text" class="qty-box" style="width: 50px; font-size: 12px;" name="quantity[{{ $decoratedProduct->product->id }}]" id="{{ $decoratedProduct->product->id }}"
                                                       @if (isset($decoratedProduct->quantity))
                                                            value="{{ $decoratedProduct->quantity }}"
                                                       @else
                                                            value="0"
                                                        @endif
                                                           >
                                            </div>


                                        </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div id="not-entered-quantities" class="alert alert-warning mx-auto d-block" role="alert" style="width: 95%; margin-bottom: 30px;">
                                    <strong>Please add the quantity for the products you want to buy.</strong>
                                </div>
                            </div>
                        </div>

                        <div class="submit-cart submit-btn-grp" style="margin-top:-15px;padding-bottom:20px">
                            <button class="blue-btn" data-dismiss="modal">CANCEL</button>
                            <button class="yellow-btn" id="btnCheckoutTicketPurchase">BUY NOW</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
</div>
</div>


<script type="text/javascript">

    $(document).ready(function(){

        if ($('.qty-box').val() == '') {
            $('.qty-box').val(0);
        }

        var products = $('#qty-box');

        for (var i = 0; i < products.length; i++)
        {
            if (myHeaders.get(products[i].id)) {
                products[i].value = myHeaders.get(myHeaders.get(products[i].id));
            }
        }

        var oldValue = '0';

        $( ".qty-box" ).focus(function() {
            if ($(this).val() != '' || $(this).val() != '0') {
                oldValue = $(this).val();
            }

            $(this).val('');

            $('#not-entered-quantities').css('visibility','hidden');
        });

        $( ".qty-box" ).blur(function() {
            if ($(this).val() == '' || $(this).val() == '0') {
                $(this).val(0);
            }
        });

        $('#not-entered-quantities').css('visibility','hidden');

        $('#frmEventsTicketCheckOut').submit(function (e) {
           e.preventDefault()  // prevent the form from 'submitting'
        })

    });
</script>

{{--<script type="text/javascript">--}}

{{--    $( document ).ready(function() {--}}
{{--        $('#frmEventsTicketCheckOut').submit(function (e) {--}}
{{--            e.preventDefault()  // prevent the form from 'submitting'--}}
{{--        })--}}
{{--    });--}}


{{--</script>--}}
