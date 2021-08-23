@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Add Country
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-success btn-sm m-btn--air" id="btnAddCountry">Save country</button>&nbsp;
                    &nbsp;
                    <a class="btn btn-danger btn-sm m-btn--air" href="{{url('/countries')}}"
                       id="btnCancelCountryPaymentMethod">Cancel</a></div>
            </div>
            <div class="m-portlet__body">
                <div class="row" id="frmAddCountry">
                    <div class="col-md-8">
                        <div class="m-form m-form__section">
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Country</label>
                                <div class="col-md-8">
                                    <input class="form-control" name="country"  id="country">
                                    <input type ="hidden" name="country_id"  id="country_id">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Payment Method Type</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="payment_method_type">
                                        <option value=""></option>
                                        <option value="t1" >NMI - T1</option>
                                        <option
                                            value="trust_my_travel">
                                            Trust my travel
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    window.onload = function () {
        src = "{{ route('searchajax') }}";
        $("#country").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        country : $("#country").val()
                    },
                    success: function(data) {
                        response(data);
                    },
                });
            },
            select: function (event, ui) {
                $('#country_id').val(ui.item.id);
            },
            minLength: 1,
        });

    }
</script>
