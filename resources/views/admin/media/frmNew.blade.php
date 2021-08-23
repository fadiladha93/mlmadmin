@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Add new media
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a class="btn btn-success btn-sm m-btn--air" id="btnNewMedia">Save</a>&nbsp;
                <a href="{{url('/media')}}" class="btn btn-info btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="frmMedia" enctype="multipart/form-data" method="post" action="{{url('/save-media')}}" class="m-form m-form__section--first m-form--label-align-right">
                {{ csrf_field() }}
                <div class="form-group m-form__group row">
                    <label class="col-md-3 col-form-label">Display Name</label>
                    <div class="col-md-9">
                        <input class="form-control" name="display_name">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-3 col-form-label">Category</label>
                    <div class="col-md-9">
                        <select class="form-control" name="category">
                            <option></option>
                            <option>{{\App\Media::TYPE_DOCUMENT}}</option>
                            <option>{{\App\Media::TYPE_VIDEO}}</option>
                            <option>{{\App\Media::TYPE_PRESENTATION}}</option>
                            <option>{{\App\Media::TYPE_IMAGE}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-3 col-form-label">Can download ?</label>
                    <div class="col-md-9">
                        <label class="m-checkbox">
                            <input type="checkbox" name="is_downloadable">
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-3 col-form-label">Active</label>
                    <div class="col-md-9">
                        <label class="m-checkbox">
                            <input type="checkbox" name="is_active">
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-3 col-form-label">From...</label>
                    <div class="col-md-9">
                        <select class="form-control" id="selFrom" name="uploaded_from">
                            <option value="local">From my computer</option>
                            <option value="web">From the web</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row" id="from_local">
                    <label class="col-md-3 col-form-label">Select a file</label>
                    <div class="col-md-9">
                        <input type="file" name="media_file" />
                    </div>
                </div>
                <div class="form-group m-form__group row" id="from_web" style="display:none;">
                    <label class="col-md-3 col-form-label">External URL</label>
                    <div class="col-md-9">
                        <input class="form-control" name="external_url">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection