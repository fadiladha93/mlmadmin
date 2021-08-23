@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Update Country {{$country->country}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-success btn-sm m-btn--air" id="btnSaveSettingsCountry">Save</button>&nbsp;
                    &nbsp;
                    <a class="btn btn-info btn-sm m-btn--air" href="{{url('/settings/countries')}}"
                       id="btnCancelCountryPaymentMethod">Back</a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row" id="frmEditCountry">
                    @csrf
                    <div class="col-md-8">
                        <div class="m-form m-form__section">
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Country</label>
                                <div class="col-md-8">
                                    <input class="form-control" disabled value="{{$country->country}}">
                                    <input value="{{$country->id}}" name="country_id" type="hidden">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Tier 3</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="is_tier3">
                                        <option value="1" {{($country->is_tier3 ? 'selected' : '')}}>
                                            Yes
                                        </option>
                                        <option value="0" {{(!$country->is_tier3 ? 'selected' : '')}}>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Open</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="is_open">
                                        <option value="1" {{($country->is_open ? 'selected' : '')}}>
                                            Yes
                                        </option>
                                        <option value="0" {{(!$country->is_open ? 'selected' : '')}}>
                                            No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-2 col-form-label">Enable 2FA</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="enable_2fa">
                                        <option value="1" {{($country->enable_2fa ? 'selected' : '')}}>
                                            Yes
                                        </option>
                                        <option value="0" {{(!$country->enable_2fa ? 'selected' : '')}}>
                                            No
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
