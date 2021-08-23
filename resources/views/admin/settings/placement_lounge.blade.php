@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Site Settings
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            @if(!empty($message))
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success">{{ $message }}</div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">
                                        Placement Lounge
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-form">
                            <form action="{{url('settings/placement-lounge')}}" method="post">
                                {{ csrf_field() }}
                                <div class="m-portlet__body">
                                    <div class="m-form m-form__section--first m-form--label-align-right">
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label">Show for users</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="enable_holding_tank">
                                                    <option value="1" {{ $isEnable ? 'selected' : '' }}>Enabled</option>
                                                    <option value="0" {{ !$isEnable ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-8">
                                                <button class="btn btn-success btn-sm m-btn--air" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection