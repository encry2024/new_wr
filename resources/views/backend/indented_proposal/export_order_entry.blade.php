<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<tr>
    <td>FSG Order Number:</td>
    <td style="text-align: left;">{{ $indented_proposal->purchase_order != '' ? $indented_proposal->purchase_order : '' }}</td>
</tr>

<tr>
    <td>Page: 1 of 1</td>
    <td style="text-align: left;">Original</td>
    <td>X Rev No:</td>
    <td></td>
    <td>SOLD TO CUSTOMER</td>

</tr>

<tr>
    <td>Order Date:</td>
    <td></td>
    <td style="text-align: left;">Issued By: </td>
    <td>Raquel Y. Rivera</td>
    <td>Customer Name:</td>
    <td>{{ $indented_proposal->customer->name }}</td>
</tr>

<tr>
    <td>Customer Order Number: </td>
    <td style="text-align: left;"></td>
    <td>Customer Order Date: </td>
    <td></td>
    <td>Customer Address:</td>
    <td>{{ $indented_proposal->customer->address }}</td>
</tr>

<tr>
    <td>Customer Order Date</td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: left;">State/Zip Code/Postal Code:</td>
    <td style="text-align: left;">{{ $indented_proposal->customer->postal_code }}</td>
</tr>

<tr>
    <td>Shipment Information </td>
    <td>Equipment</td>
    <td>Software</td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td>Shipment Basis:</td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td>Standard lead time:</td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td>Released to Manufacturing:</td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td>MFG Location:</td>
    <td></td>
    <td></td>
    <td>RUI Code:</td>
    <td></td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td>Bill To Address</td>
    <td></td>
    <td></td>
    <td></td>
    <td>End User/Destination</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: left;"   >End User Name:</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: left;">End User Address:</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: left;">State/Postal Code:</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: left;">Country:</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: left;">End User Industry Code:</td>
</tr>

<tr>
    <td>Operation Customer Account Number:</td>
    <td></td>
    <td></td>
    <td></td>
    <td>TPI Number:</td>
</tr>

<tr></tr>
<tr></tr>

<tr>
    <td>Ship To Address:</td>
    <td></td>
    <td></td>
    <td></td>
    <td>Terms:</td>
</tr>

<tr>
    <td>{{ $indented_proposal->ship_to != '' ? $indented_proposal->ship_to : '' }}</td>
    <td></td>
    <td></td>
    <td></td>
    <td>Tax Exempt:</td>
</tr>

<tr>
    <td>{{ $indented_proposal->ship_to_address != '' ? $indented_proposal->ship_to_address : '' }}</td>
    <td></td>
    <td></td>
    <td></td>
    <td>Currency:</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Freight:</td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Special Instructions:</td>
</tr>



<tr></tr>
<tr></tr>
<tr></tr>

<table>
    <tr>
        <th><b>Item #</b></th>

        <th><b>Material Code</b></th>

        <th><b>Description</b></th>

        <th><b>Quantity</b></th>

        <th><b>Price</b></th>

        <th><b>Delivery (Weeks)</b></th>
    </tr>
    <?php $total_price = 0; ?>
    @foreach($selectedItems as $selectedItem)
        <tr >
            <td>{{ ++$ctr }}</td>
            <td>{{ $selectedItem->project_mn != "" ? $selectedItem->project_mn : ($selectedItem->after_market_mn != '' ? $selectedItem->after_market_mn : $selectedItem->seal_material_number) }}</td>
            <td style="text-align: left;">
                <b>NAME:&nbsp;</b>
                {{ $selectedItem->project_name != "" ? $selectedItem->project_name : ($selectedItem->after_market_name != '' ? $selectedItem->after_market_name : $selectedItem->seal_name) }}
                <br>
                <b>{{ $selectedItem->project_pn != "" ? "PN" : ($selectedItem->after_market_pn != '' ? "PN" : "BOM#") }} :&nbsp;</b> {{ $selectedItem->project_pn != "" ? $selectedItem->project_pn : ($selectedItem->after_market_pn != '' ? $selectedItem->after_market_pn : $selectedItem->seal_bom_number) }}
                <br>
                <b>MODEL NO.:&nbsp;</b> {{ $selectedItem->project_md != "" ? $selectedItem->project_md : ($selectedItem->after_market_md != '' ? $selectedItem->after_market_md : $selectedItem->seal_model) }}
                <br>
                {{-- <b>DWG NO.:&nbsp;</b> {{ $selectedItem->project_dn != "" ? $selectedItem->project_dn : ($selectedItem->after_market_dn != '' ? $selectedItem->after_market_dn : $selectedItem->seal_drawing_number) }} --}}
                <br>
                <b>TAG NO.:&nbsp;</b> {{ $selectedItem->project_tn != "" ? $selectedItem->project_tn : ($selectedItem->after_market_tn != '' ? $selectedItem->after_market_tn : $selectedItem->seal_tag_number) }}
            </td>
            <td>{{ $selectedItem->quantity != "" ? $selectedItem->quantity : $selectedItem->quantity }}</td>
            <td style="text-align: right;">{{ number_format($selectedItem->indented_proposal_item_price, 2)  }}</td>
            <td>
                {{ $selectedItem->delivery != "" ? $selectedItem->delivery / 7 : $selectedItem->delivery / 7 }}
            </td>
        </tr>
        <?php $total_price += $selectedItem->indented_proposal_item_price; ?>
    @endforeach
</table>

<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align: right;">{{ number_format($total_price,2) }}</td>
</tr>

<tr>
    <td>MEMORANDA</td>
    <td colspan="5"></td>
</tr>

<tr></tr>
<tr></tr>
<tr>
    <td>Agent Name</td>
    <td>Agt Code</td>
    <td>Agt %</td>
    <td>Branch Name</td>
    <td>Branch Code</td>
    <td>Sales Engineer</td>
    <td>Sales Eng. Code</td>
    <td>Sales Credit %</td>
</tr>


</body>
</html>