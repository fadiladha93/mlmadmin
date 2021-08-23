@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
@if(session('processed'))
    <div>
        <div class="alert alert-custom alert-primary fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div> 
            <div class="alert-text">
                Processed {{ session('processed') }} refunds
                <ul>
                    <li>{{ session('skipped') }} Skipped(Duplicated)</li>
                    <li>{{ session('failed') }} Failed</li>
                    <li>{{ session('successfully') }} Imported successfully</li>
                </ul>
            </div> 
            <div class="alert-close"><button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"><i class="ki ki-close"></i></span></button></div>
        </div>
    </div>
@endif    
<form enctype="multipart/form-data" method="post" action="{{url('/batch-order-refund')}}" class="m-form m-form__section--first m-form--label-align-right">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Import CSV file
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <input  type="submit" class="btn btn-success btn-sm m-btn--air" value="Save" >&nbsp;
                <a href="{{back()}}" class="btn btn-info btn-sm m-btn--air">Back</a>
            </div>
        </div>
        <div class="m-portlet__body">
            
                {{ csrf_field() }}
                <div class="form-group m-form__group row">
                    <label class="col-md-3 col-form-label">Select a file</label>
                    <div class="col-md-9">
                        <input type="file" name="media_file" />
                    </div>
                </div>
                
        </div>
    </div>
</form>

</div>
@endsection