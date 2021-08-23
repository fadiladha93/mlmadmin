@extends('affiliate.layouts.main')

@section('main_content')
@include('affiliate.user.my_profile_tab')

<div class="row" style="margin-top: 20px">
    <div class="col-md-6 offset-3">
        <!--begin::Portlet-->
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            Binary Placement
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body" style="padding:15px;">

                <!--begin::Section-->
                <div class="m-section" style="margin-bottom: 0">
                    <div class="text-center" style="font-size:16px;">
                        The placement preference you choose below will determine where those distributors who register through your business site will be placed in your Binary Downline.
                    </div>
                    <div class="m-section__content">
                        <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                            <div class="m-demo__preview" style="border:0; padding-bottom: 0;">

                                <!--begin::Form-->
                                <form class="m-form" id="frmPlacements">
                                    <div class="m-form__group form-group">
                                        <div class="m-radio-list" style="width: fit-content;margin: 0 auto;">
                                            <label class="m-radio">
                                                <input type="radio" name="binary_placement" {{ $binary_placement === "left" ? "checked" : "" }} value="left"> Left
                                                <span></span>
                                            </label>
                                            <label class="m-radio">
                                                <input type="radio" name="binary_placement" {{ $binary_placement === "right" ? "checked" : "" }} value="right"> Right
                                                <span></span>
                                            </label>
                                            {{--<label class="m-radio">--}}
                                                {{--<input type="radio" name="binary_placement" {{ $binary_placement === "stronger" ? "checked" : "" }} value="stronger"> Stronger--}}
                                                {{--<span></span>--}}
                                            {{--</label>--}}
                                            {{--<label class="m-radio">--}}
                                                {{--<input type="radio" name="binary_placement" {{ $binary_placement === "lesser" ? "checked" : "" }} value="lesser"> Lesser--}}
                                                {{--<span></span>--}}
                                            {{--</label>--}}
                                            <label class="m-radio">
                                                <input type="radio" name="binary_placement" {{ $binary_placement === "auto" ? "checked" : "" }} value="auto"> Alternating
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="text-center " style="margin-top: 15px;">
                                        <button id="btnSavePlacements" class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">Save Placements</button>
                                    </div>
                                </form>

                                <!--end::Form-->
                            </div>
                        </div>
                    </div>
                </div>

                <!--end::Section-->
            </div>
        </div>

        <!--end::Portlet-->
    </div>
</div>

@endsection
