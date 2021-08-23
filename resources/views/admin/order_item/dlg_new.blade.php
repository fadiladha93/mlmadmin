<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add new order item</h5>
        </div>
        <div class="modal-body">
            <div class="m-form m-form__section--first m-form--label-align-right" id="frmNewOrderItem">
                <input type="hidden" name="order_id" value="{{$order_id}}" />
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">Product</label>
                    <div class="col-md-8">
                        <select class="form-control" name="productid">
                            <option></option>
                            @foreach($prods as $prod)
                            <option value="{{$prod->id}}">{{$prod->productname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">Price</label>
                    <div class="col-md-8">
                        <input class="form-control" name="itemprice">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">BV</label>
                    <div class="col-md-8">
                        <input class="form-control" name="bv">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">QV</label>
                    <div class="col-md-8">
                        <input class="form-control" name="qv">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">CV</label>
                    <div class="col-md-8">
                        <input class="form-control" name="cv">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">QC</label>
                    <div class="col-md-8">
                        <input class="form-control" name="qc">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">AC</label>
                    <div class="col-md-8">
                        <input class="form-control" name="ac">
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label"></label>
                    <div class="col-md-8">
                        <button class="btn btn-danger btn-sm m-btn--air" id="btnAddOrderItem">Add New</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
