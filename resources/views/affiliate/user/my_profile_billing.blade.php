@extends('affiliate.layouts.main')

@section('main_content')
    @include('affiliate.user.my_profile_tab')
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="m-portlet" style="margin-top:20px;">
                <div class="m-portlet__head our_head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Add new card
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:15px;">
                    <form id="frmAddNewCard" class="m-form m--align-right">

                        <label class="m-checkbox m-checkbox--focus">
                            <input type="checkbox" name="make_primary_card"> Make Primary Card&nbsp;
                            <span></span>
                        </label>

                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">First Name</label>
                            <div class="col-md-8">
                                <input class="form-control" name="first_name">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Last Name</label>
                            <div class="col-md-8">
                                <input class="form-control" name="last_name">
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Credit card number</label>
                            <div class="col-md-8">
                                <input class="form-control" name="number">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">CVV</label>
                            <div class="col-md-8">
                                <input class="form-control" name="cvv">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Expiration Date</label>
                            <div class="col-md-8">
                                <input class="form-control" name="expiry_date" placeholder="mm/yyyy">
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Billing Address</label>
                            <div class="col-md-8">
                                <select class="form-control" id="billingAddressSelect" name="address_id" style="font-size: 10px;">
                                    <option>Please select billing address</option>
                                    @php $first = true; @endphp
                                    @foreach ($addresses as $address)
                                        <option value="{{ $address->id }}" @php if($first) { echo "selected"; $first = false; } @endphp>{{ \App\Address::getSummary($address) }}</option>
                                    @endforeach
                                    <option value="-1">Add new billing address</option>
                                </select>
                            </div>
                        </div>

                        <div class="m-form m--align-right" id="addressForm" style="display: none; overflow: hidden;">
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Address 1</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="address1">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Address 2</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="address2">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Apt / Suite</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="apt">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">City</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="city">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">State / Province</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="stateprov">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Postal Code</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="postalcode">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-4 col-form-label">Country Code</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="countrycode">
                                        <option></option>
                                        @foreach($countries as $c)
                                            <option value="{{$c->countrycode}}">{{$c->country}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-top:10px;">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <button id="btnAddCard"
                                            class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Add card
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="m-portlet" style="margin-top:20px;">
                <div class="m-portlet__head our_head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Billing
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding:15px;">
                    <table class="table table-striped- table-bordered table-hover table-checkable">
                        <thead>
                        <tr>
                            <th>Card</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cards as $c)
                            @if(!empty($c->token))
                                <tr>
                                    <td>{{\App\PaymentMethod::getFormatedCardNo($c->token)}}</td>
                                    <td>
                                        @if($c->primary)
                                            Primary Card
                                        @elseif($c->is_subscription)
                                            Subscription Card
                                        @else
                                            Secondary Card
                                        @endif
                                    </td>
                                    <td>
                                        <form id="delete-payment-method">
                                            <input type="hidden" name="payment_method_id" value="{{$c->id}}"/>
                                            <button class="btn btn-danger btn-sm deletePaymentMethodButton">Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($vibeUserWithoutBillingInfo)
        <script type="text/javascript">
            (function() {
                alert('Thank you and welcome to ibüümerang!  Please add a card for your subscription.');
            })();
        </script>
    @endif
@endsection
