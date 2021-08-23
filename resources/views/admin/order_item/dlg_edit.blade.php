<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Update order item</h5>
        </div>
        <div class="modal-body">
            <div class="m-form m-form__section--first m-form--label-align-right" id="frmUpdateOrderItem">
                <input type="hidden" name="item_id" value="{{$item->id}}" />
                <input type="hidden" name="order_id" value="{{$item->orderid}}" />
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">Product</label>
                    <div class="col-md-8">
                        <select class="form-control" name="productid">
                            @foreach($prods as $prod)
                            <option value="{{$prod->id}}" @if($item->productid == $prod->id) selected @endif>{{$prod->productname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">Price</label>
                    <div class="col-md-8">
                        <input class="form-control" name="itemprice" value="{{$item->itemprice}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">BV</label>
                    <div class="col-md-8">
                        <input class="form-control" name="bv" value="{{$item->bv}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">QV</label>
                    <div class="col-md-8">
                        <input class="form-control" name="qv" value="{{$item->qv}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">CV</label>
                    <div class="col-md-8">
                        <input class="form-control" name="cv" value="{{$item->cv}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">QC</label>
                    <div class="col-md-8">
                        <input class="form-control" name="qc" value="{{$item->qc}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">AC</label>
                    <div class="col-md-8">
                        <input class="form-control" name="ac" value="{{$item->ac}}">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label"></label>
                    <div class="col-md-8">
                        <button class="btn btn-danger btn-sm m-btn--air" id="btnUpdateOrderItem">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
