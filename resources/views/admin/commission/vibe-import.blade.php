@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Vibe Commissions Import
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <form id="importVibeForm" method="post" enctype="multipart/form-data">
                            @csrf

                            @if (isset($success))
                                @php
                                    $alertClass = 'alert-success';

                                    if($success === false) {
                                        $alertClass = $numSuccessful === 0 ? 'alert-danger' : 'alert-warning';
                                    }

                                    $successRatio = "$numSuccessful / $numTotal";
                                @endphp

                                <div class="col-md-8 offset-md-3 alert {{ $alertClass }} text-center">
                                    <h5>{{ $successRatio }} imported & orders created.</h5>
                                </div>
                            @endif

                            @if (isset($errors) && is_array($errors))
                                <br>
                                <h4 class="col-md-4 offset-md-5">Issues</h4>
                                <div class="form-group m-form__group row col-md-6 offset-md-4">
                                    <div class="form-group m-form__group row">
                                        @foreach ($errors as $error)
                                            <small class="form-text col-md-12 text-muted">{{ $error }}</small>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="m-form m-form__section--first m-form--label-align-right">
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">Date</label>
                                    <div class="col-md-4">
                                        <input type="text" id="date" name="date" class="form-control date_picker2" value="{{ old('date') }}" required>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-md-4 col-form-label">CSV File</label>
                                    <div class="col-md-4" style="margin-top: 5px">
                                        <input type="file" accept=".csv" id="csvFile" name="csvFile" class="form-control-file" required>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-md-4 offset-5">
                                        <button type="submit" id="btnImportVibe" class="btn btn-info btn-sm m-btn--air">Import</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
