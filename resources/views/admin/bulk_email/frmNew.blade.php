@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                New bulk email
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a class="btn btn-danger btn-sm m-btn--air" id="btnNewBulkMail">Send</a>&nbsp;
                        <a href="{{url('/bulk-email')}}" class="btn btn-info btn-sm m-btn--air">Back</a>
                    </div>
                </div>
                <div class="m-portlet__body" id="frmNewBulkMail">
                    <div class="m-form m-form__section--first m-form--label-align-right">
                        <div class="form-group m-form__group row">
                            <label class="col-md-3 col-form-label">To</label>
                            <div class="col-md-9">
                                <div class="m-checkbox-list">
                                    @foreach($to as $t)
                                    <label class="m-checkbox">
                                        <input type="checkbox" name="to[]" value="{{$t->address}}"> {{$t->name}} - {{$t->no_of_members}}
                                        <span></span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-3 col-form-label">Mail Subject</label>
                            <div class="col-md-9">
                                <input class="form-control" name="subject">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-3 col-form-label">Mail Content</label>
                            <div class="col-md-9">
                                <textarea rows="10" class="form-control" name="content"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection