@extends('affiliate.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-video-o"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Videos
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text" class="form-control m-input m-input--pill" id="media_vid_search" style="border-radius:50px;" placeholder="Search">
                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-search"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" id="media_vid" style="padding-top:15px;">
                    <div class="text-center">
                        <img src="{{asset('/assets/images/loading.gif')}}" width="32px;" />
                    </div>
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-photo-o"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Images
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text" class="form-control m-input m-input--pill" id="media_img_search" style="border-radius:50px;" placeholder="Search">
                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-search"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" id="media_img" style="padding-top:15px;">
                    <div class="text-center">
                        <img src="{{asset('/assets/images/loading.gif')}}" width="32px;" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-text"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Documents
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text" class="form-control m-input m-input--pill" id="media_doc_search" style="border-radius:50px;" placeholder="Search">
                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-search"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" id="media_doc" style="padding-top:15px;">
                    <div class="text-center">
                        <img src="{{asset('/assets/images/loading.gif')}}" width="32px;" />
                    </div>
                </div>
            </div>
            <div class="m-portlet m-portlet--mobile m-portlet--info m-portlet--head-solid-bg">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-file-video-o"></i>
                            </span>
                            <h3 class="m-portlet__head-text">
                                Presentations
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="m-input-icon m-input-icon--right">
                            <input type="text" class="form-control m-input m-input--pill" id="media_pres_search" style="border-radius:50px;" placeholder="Search">
                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-search"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" id="media_pres" style="padding-top:15px;">
                    <div class="text-center">
                        <img src="{{asset('/assets/images/loading.gif')}}" width="32px;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection