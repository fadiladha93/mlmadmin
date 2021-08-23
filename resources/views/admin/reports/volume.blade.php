@extends('admin.layouts.main')

@section('main_content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Commission Controller Center
                        </h3>
                    </div>
                </div>
            </div>
            <br>
            <div class="kt-container">
                <div class="col-lg-12">
                    <div class="kt-portlet kt-portlet--tabs">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-toolbar">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger nav-tabs-line-2x nav-tabs-line-right"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab"
                                           href="#kt_portlet_base_demo_2_3_tab_content" role="tab">
                                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>Audit
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="kt_portlet_base_demo_2_3_tab_content"
                                         role="tabpanel">
                                        <form id="frmCalculateVolume" style="display: flex;">
                                            <div class="col-md-2">
                                                <input class="form-control m-input form-control-sm" id="order_number" name="order_number"
                                                       placeholder="Order Number"
                                                       value="">
                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control m-input form-control-sm" id="tsa" name="tsa"
                                                       placeholder="TSA# / Username"
                                                       value="TSA0515101">
                                            </div>
                                            <div class="col-md-4">

                                                <select class="form-control m-input form-control-sm" id="volume_type"
                                                        name="volume_type">
                                                    <option value="" disabled selected>Type of Volume</option>
                                                    <option value="enrollments" selected>Enrollment/Upgrades</option>
                                                    <option value="subscriptions">Subscriptions/Residuals</option>
                                                    <option value="all">All</option>
                                                </select>

                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control date_picker2 m-input form-control-sm"
                                                       id="d_from" name="d_from"
                                                       placeholder="From Date" value="2017-02-01">
                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control date_picker2 m-input form-control-sm"
                                                       id="d_to" name="d_to"
                                                       placeholder="To Date" value="2019-08-01">
                                                <br>
                                                <button class="btn btn-info m-btn--air  btn-sm pull-right"
                                                        id="btnCalculateVolume">Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                {{--<br>--}}
                                {{--<div class="form-group">--}}
                                    {{--<div class="kt-radio-list">--}}
                                        {{--<label class="kt-radio kt-radio--bold">--}}
                                            {{--<input type="radio" name="radio6"> FSB--}}
                                            {{--<span></span>--}}
                                        {{--</label>--}}
                                        {{--&nbsp;--}}
                                        {{--<label class="kt-radio kt-radio--bold">--}}
                                            {{--<input type="radio" name="radio6"> Dual-Team--}}
                                            {{--<span></span>--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                <div id="kt_quick_panel" class="kt-quick-panel">
                                    <a href="#" class="kt-quick-panel__close" id="kt_quick_panel_close_btn"><i
                                            class="flaticon2-delete"></i></a>

                                    <div class="kt-quick-panel__nav">
                                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand  kt-notification-item-padding-x"
                                            role="tablist">
                                            <li class="nav-item active">
                                                <a class="nav-link active" data-toggle="tab"
                                                   href="#kt_quick_panel_tab_notifications" role="tab"> FSB </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_tab_logs"
                                                   role="tab">Dual-Team</a>
                                            </li>

                                        </ul>
                                    </div>
                                    <div class="kt-quick-panel__content">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show kt-scroll active"
                                                 id="kt_quick_panel_tab_notifications" role="tabpanel">
                                                <div class="kt-notification">
                                                    <div class="m-portlet__body">
                                                        <table class="table table-striped- table-bordered table-hover table-checkable"
                                                               id="dt_fsb_commission">
                                                            <thead>
                                                            <tr>
                                                                <th>Percentage</th>
                                                                <th>Date</th>
                                                                <th>Name</th>
                                                                <th>Username</th>
                                                                <th>Enrollment Pack</th>
                                                                <th>Volume</th>
                                                                <th>Memo</th>
                                                                <th>Total</th>
                                                            </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade kt-scroll" id="kt_quick_panel_tab_logs"
                                                 role="tabpanel">
                                                <div class="kt-notification-v2">
                                                    <div class="m-portlet__body">
                                                        <table class="table table-striped- table-bordered table-hover table-checkable"
                                                               id="dt_fsb_commission">
                                                            <thead>
                                                            <tr>
                                                                <th>Percentage</th>
                                                                <th>Date</th>
                                                                <th>Name</th>
                                                                <th>Username</th>
                                                                <th>Enrollment Pack</th>
                                                                <th>Volume</th>
                                                                <th>Total</th>
                                                            </tr>
                                                            </thead>
                                                        </table>
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
            </div>
        </div>
    </div>




@endsection
