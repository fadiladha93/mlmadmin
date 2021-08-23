<table class="product-tbl" id="ibuumerang_product_table">
    <tbody>
    <tr class="prdct-head">
        <td>PRODUCT</td>
        <td>PRICE</td>
        <td>QUANTITY</td>
    </tr>
    @foreach($products as $item)
    <tr class="prdct-item">
        <td class="prdct-name">
            <p>{{$item->product->productname}}</p>
        </td>
        <td>
            <strong>${{number_format($item->product->price,2)}}</strong>
        </td>
        <td>
            <strong>{{  $item->quantity }}</strong>
        </td>
    </tr>
    @endforeach
    <tr class="total-price" style="font-weight: bold;">
        <td> SUB TOTAL</td>
        <td> <strong>${{number_format($item->product->price,2)}}</strong></td>
    </tr>
    <tr class="total-price" style="font-weight: bold;">
        <td> TOTAL</td>
        <td> ${{number_format($total,2)}}</td>
    </tr>
    </tbody>
</table>
