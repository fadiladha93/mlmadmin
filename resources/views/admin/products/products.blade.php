@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Products
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{url('/new-product')}}" class="btn btn-danger btn-sm m-btn--air">Add new product</a>&nbsp;
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_products">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Enabled</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>SKU</th>
                        <th>Item Code</th>
                        <th>BV</th>
                        <th>CV</th>
                        <th>QV</th>
                        <th>QC</th>
                        <th>AC</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection