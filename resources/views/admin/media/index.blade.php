@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Media & Files
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{url('/new-media')}}" class="btn btn-danger btn-sm m-btn--air">Add new media</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_media">
                <thead>
                    <tr>
                        <th>Display Name</th>
                        <th>File Name</th>
                        <th>Category</th>
                        <th>External URL</th>
                        <th>Can Download</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection