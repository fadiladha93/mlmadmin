<table class="product-tbl" id="ibuumerang_product_table">
    <tbody>
    <tr class="prdct-head">
        <td>PRODUCT</td>
        <td>PRICE</td>
        <td>QUANTITY</td>
    </tr>
    <tr class="prdct-item">
        <td class="prdct-name">
            <p>{{$product->productdesc}}</p>
        </td>
        <td>
            <strong>${{number_format($product->price,2)}}</strong>
        </td>
        <td>
            <strong>{{$quantity}}</strong>
        </td>
    </tr>
    <tr class="total-price" style="font-weight: bold;">
        <td> SUB TOTAL</td>
        <td> ${{number_format($sub_total,2)}}</td>
    </tr>
    <tr class="total-price" style="font-weight: bold;">
        <td> TOTAL</td>
        <td> ${{number_format($total,2)}}</td>
    </tr>
    </tbody>
</table>
