@extends('admin.layouts.main')

@section('main_content')

<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Edit Order # {{$rec->id}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="#" class="btn btn-info btn-sm m-btn--air showDlgHistory"  tag2="update-history" tag="{{url("/dlg-update-history/ORDER/".$rec->id)}}">Update history</a>
                &nbsp;
                <a class="btn btn-danger btn-sm m-btn--air" id="btnUpdateOrder">Save order changes</a>&nbsp;
                <a class="btn btn-danger btn-sm m-btn--air refund-order" order-id="{{$rec->id}}">Refund order</a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" id="frmUpdateOrder">
                <input type="hidden" name="order_id" value="{{$rec->id}}" />
                <div class="col-md-6">
                    <div class="m-form m-form__section--first m-form--label-align-right">
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Dist ID</label>
                            <div class="col-md-8">
                                <input class="form-control" disabled="disabled" value="{{$user->distid}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Distributor Name</label>
                            <div class="col-md-8">
                                <input class="form-control" disabled="disabled" value="{{$user->firstname." ".$user->lastname}} ">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Distributor Username</label>
                            <div class="col-md-8">
                                <input class="form-control" disabled="disabled" value="{{$user->username}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Date</label>
                            <div class="col-md-8">
                                <input class="form-control date_picker2" name="created_date" value="{{$rec->created_date}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="m-form m-form__section--first m-form--label-align-right">
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order total</label>
                            <div class="col-md-8">
                                <input class="form-control" name="ordertotal" value="{{$rec->ordertotal}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order subtotal</label>
                            <div class="col-md-8">
                                <input class="form-control" name="ordersubtotal" value="{{$rec->ordersubtotal}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order BV</label>
                            <div class="col-md-8">
                                <input class="form-control" name="orderbv" value="{{$rec->orderbv}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order QV</label>
                            <div class="col-md-8">
                                <input class="form-control" name="orderqv" value="{{$rec->orderqv}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order CV</label>
                            <div class="col-md-8">
                                <input class="form-control" name="ordercv" value="{{$rec->ordercv}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order QC</label>
                            <div class="col-md-8">
                                <input class="form-control" name="orderqc" value="{{$rec->orderqc}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-md-4 col-form-label">Order AC</label>
                            <div class="col-md-8">
                                <input class="form-control" name="orderac" value="{{$rec->orderac}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Order items
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if($permit_to_edit)
                <a tag="{{url('/add-new-order-item/'.$rec->id)}}" class="btn btn-danger btn-sm m-btn--air showDlg_s">Add new order item</a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>BV</th>
                        <th>QV</th>
                        <th>CV</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{$item->productname}}</td>
                        <td>{{$item->itemprice}}</td>
                        <td>{{$item->bv}}</td>
                        <td>{{$item->qv}}</td>
                        <td>{{$item->cv}}</td>
                        <td class="refund-field">
                            <a tag="{{url('/update-order-item/' . $item->item_id)}}" class="btn btn-danger btn-sm m-btn--air showDlg_s">Edit</a>
                            <button class="btn btn-danger btn-sm m-btn--air button-refund-order-item" {{$item->is_refunded ? 'disabled' : ''}} order-item-id="{{$item->item_id}}">Refund</button>
                            <a href="#" class="btn btn-info btn-sm m-btn--air showDlgHistory"  tag2="update-history" tag="{{url("/dlg-update-history/ORDER_ITEM/".$item->item_id)}}">Update history</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
