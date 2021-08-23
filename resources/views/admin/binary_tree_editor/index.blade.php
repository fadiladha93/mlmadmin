@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Binary Tree Editor - Replace
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmBinaryTreeReplace">
                <div class="col-md-4">
                    <select class="form-control m-select2 select2_tree_from" name="from_id">
                        <option></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control m-select2" id="select2_tree_to" name="to_id">
                        <option></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-info m-btn--air btn-block" id="binaryTreeReplace">Replace</button>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Binary Tree Editor - Search / Delete
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmBinaryTreeSearch">
                <div class="col-md-4">
                    <select class="form-control m-select2 select2_tree_from" name="user_id">
                        <option></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-info m-btn--air btn-block" id="binaryTreeSearch">Search</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-danger m-btn--air btn-block" id="binaryTreeDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    -->
</div>
@endsection