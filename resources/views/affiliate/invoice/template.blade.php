<style>
    * {
        margin: 0;
        padding: 0;
        font-family: Arial;
        font-size: 14pt;
        color: #2d2d2d;
    }

    body {
        width: 100%;
        font-family: Arial;
        font-size: 14pt;
        margin: 0;
        padding: 0px;
    }
</style>

<div style='width: 87.2%;height: 20px;margin-bottom: 20px;'>
    <table autosize="1" width="87.2%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>ibuumerang, LLC</div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>11807 Westheimer Rd 550-427</div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>Houston, TX 77077</div>
            </td>
        </tr>
    </table>
</div>

<div style='margin-bottom: 5px;'>
    <table autosize="1" width="87.2%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>Email: finance@ibuumerang.com</div>
            </td>
        </tr>
    </table>
</div>

<div style="margin-bottom: 25px;"><img src='{{asset('assets/images/invoice/inv_line.png')}}'/></div>

<div style='width: 110%;height: 20px;margin-bottom: 30px;'>
    <table autosize="1" width="110%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>Bill to :</div>
            </td>
            <td style="width: 15%;height:5%;text-align: right;color: gray;">
                <div>INVOICE :</div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%;height:5%;text-align: left;">
                <div>{{$user->firstname}} {{$user->lastname}}</div>
            </td>
            <td style="width: 15%;height:5%;text-align: right;color: #0cb5ea;">
                <div>Invoice No: {{$order->id}}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>{{$address? $address->address1:''}}</div>
            </td>
            <td style="width: 15%;height:5%;text-align: right;color: gray;">
                <div>{{ date('F d ,Y',strtotime($order->created_date)) }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>{{$address? $address->address2:''}}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>{{$user->email }}</div>
            </td>
        </tr>
    </table>
</div>

<div style='width: 110%;height: 20px;'>
    <table autosize="1" width="110%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr style="background-color: #0cb5ea;">
            <th style="border-left: 1px solid #0cb5ea;width: 15%;height:5%;vertical-align:middle;text-align: center;padding: 8px;color: #ffffff;">
                <div>INVOICE {{$order->id}}</div>
            </th>
        </tr>
    </table>
</div>

<div style="margin-bottom: 20px;"><img src='{{asset('assets/images/invoice/inv_hed_line.png')}}'/></div>

<div style='width: 110%;height: 20px;margin-bottom: 130px;'>
    <table autosize="1" width="110%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr>
            <th style="width: 15%;height:5%;text-align: left;font-size:9pt;color: gray;">
                <div>QUANTITY</div>
            </th>
            <th style="width: 15%;height:5%;text-align: left;font-size:9pt;color: gray;">
                <div>DESCRIPTION</div>
            </th>
            <th style="width: 15%;height:5%;text-align: right;font-size:9pt;color: gray;">
                <div>UNIT PRICE</div>
            </th>
            <th style="width: 15%;height:5%;text-align: right;font-size:9pt;color: gray;">
                <div>PRICE</div>
            </th>
        </tr>
        @foreach($order_items as $item)
            <tr>
                <td style="width: 15%;height:5%;text-align: left;padding-top: 8px;font-size:10pt;color:#0cb5ea;">
                    <div>{{$item->quantity}}</div>
                </td>
                <td style="width: 15%;height:5%;text-align: left;padding-top: 8px;font-size:10pt;color: gray;">
                    <div>{{\App\Product::getProductNameForInvoice($item)}}</div>
                </td>
                <td style="width: 15%;height:5%;text-align: right;padding-top: 8px;font-size:10pt;color: gray;">
                    <div>$ {{number_format($item->itemprice,2)}}</div>
                </td>
                <td style="width: 15%;height:5%;text-align: right;padding-top: 8px;font-size:10pt;color: gray;">
                    <div>$ {{number_format($item->quantity*$item->itemprice,2)}}</div>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div style="margin-bottom: 25px; height: 2px;"><img src='{{asset('assets/images/invoice/inv_tbl_line.png')}}'/></div>

<div style='width: 110%;height: 20px;margin-bottom: 25px;'>
    <table autosize="1" width="110%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr>
            <th style="width: 15%;height:5%;text-align: left;font-size:10pt;color: gray;">
                <div>SUBTOTAL</div>
            </th>
            <th style="width: 15%;height:5%;text-align: right;font-size:10pt;color: gray;">
                <div>$ {{number_format($order->ordersubtotal,2)}}</div>
            </th>
        </tr>
    </table>
</div>

<div style="margin-bottom: 25px; height: 2px;"><img src='{{asset('assets/images/invoice/inv_tbl_line.png')}}'/></div>

<div style='width: 110%;height: 20px;margin-bottom: 25px;'>
    <table autosize="1" width="110%" style="border-spacing:0;border-collapse: collapse;font-size:11pt;color: #2d2d2d;">
        <tr>
            <th style="width: 15%;height:5%;text-align: left;color: gray;">
                <div>TOTAL</div>
            </th>
            <th style="width: 15%;height:5%;text-align: right;color: gray;">
                <div>$ {{number_format($order->ordertotal,2)}}</div>
            </th>
        </tr>
    </table>
</div>

<div style="margin-bottom: 70px;"><img src='{{asset('assets/images/invoice/inv_hed_line.png')}}'/></div>