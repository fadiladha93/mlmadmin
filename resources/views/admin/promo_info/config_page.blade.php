@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Promo Info
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a id="btnSavePromo" class="btn btn-primary btn-sm m-btn--air">Save</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="frmPromo" enctype="multipart/form-data" method="post" action="{{url('/save-promo')}}">
                {{ csrf_field() }}
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    Top Banner
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-form">
                        <div class="m-portlet__body">
                            <div class="m-form m-form__section--first m-form--label-align-right">
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Image</label>
                                    <div class="col-md-3">
                                        <input type="file" name="top_banner_img" />
                                    </div>
                                    @if(!empty($rec->top_banner_img))
                                        <img src="{{ \Storage::url($rec->top_banner_img) }}" style="max-width: 100%">
                                        <br>
                                        <div class="promo_url">
                                            {{ \Storage::url($rec->top_banner_img) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">URL</label>
                                    <div class="col-md-9">
                                        <input class="form-control" name="top_banner_url" value="{{$rec->top_banner_url}}">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Active</label>
                                    <div class="col-md-9">
                                        <label class="m-checkbox">
                                            <input @if($rec->top_banner_is_active == 1) checked="checked" @endif type="checkbox" name="top_banner_is_active">
                                                    <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--
                removed on 7/29/2020, card trello https://trello.com/c/L5rUZCoM/540-admin-allow-admin-to-upload-promo-image
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    Widget Box
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-form">
                        <div class="m-portlet__body">
                            <div class="m-form m-form__section--first m-form--label-align-right">
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Image</label>
                                    <div class="col-md-3">
                                        <input type="file" name="side_banner_img" />
                                    </div>
                                    @if(!utill::isNullOrEmpty($rec->side_banner_img))
                                    <div class="col-md-6">
                                        <img src="{{asset('/promo/'.$rec->side_banner_img)}}" width="200px;" />
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Title</label>
                                    <div class="col-md-9">
                                        <input class="form-control" name="side_banner_title" value="{{$rec->side_banner_title}}">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Short Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="side_banner_short_desc" rows="3">{{$rec->side_banner_short_desc}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="side_banner_long_desc" class="summernote">{{$rec->side_banner_long_desc}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-3 col-form-label">Active</label>
                                    <div class="col-md-9">
                                        <label class="m-checkbox">
                                            <input @if($rec->side_banner_is_active == 1) checked="checked" @endif type="checkbox" name="side_banner_is_active">
                                                    <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </form>
        </div>
    </div>
</div>
@endsection