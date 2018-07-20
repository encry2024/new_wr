<html>
<head>
   <meta charset="UTF-8">
   <title>Document</title>
</head>
<body>
   <tr>
      <td>PURCHASE ORDER NUMBER</td>
      <td style="text-align: left;">{{ $indented_proposal->purchase_order != '' ? $indented_proposal->purchase_order : '' }}</td>
   </tr>

   <tr>
      <td>TO:</td>
      <td>{{ $indented_proposal->customer->name }}</td>
   </tr>
   <tr>
      <td></td>
      <td>{{ $indented_proposal->customer->address }}</td>
   </tr>jlj

   <tr>
      <td>INVOICE TO:</td>
      <td>{{ $indented_proposal->invoice_to != '' ? $indented_proposal->invoice_to : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td>{{ $indented_proposal->invoice_to_address != '' ? $indented_proposal->invoice_to_address : '' }}</td>
   </tr>

   <tr>
      <td>SHIP TO:</td>
      <td>{{ $indented_proposal->ship_to != '' ? $indented_proposal->ship_to : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td>{{ $indented_proposal->ship_to_address != '' ? $indented_proposal->ship_to_address : '' }}</td>
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

         <th><b>Delivery</b></th>
      </tr>
      @foreach($selectedItems as $selectedItem)
      <tr >
         <td>{{ ++$ctr }}</td>
         <td>{{ $selectedItem->project_mn != "" ? $selectedItem->project_mn : ($selectedItem->after_market_mn != '' ? $selectedItem->after_market_mn : $selectedItem->seal_material_number) }}</td>
         <td  style="text-align: left;">
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
         <td>{{ $selectedItem->project_price != '' ? number_format($selectedItem->project_price, 2) : ($selectedItem->after_market_price != '' ? number_format($selectedItem->after_market_price, 2) : number_format($selectedItem->seal_price, 2)) }}</td>
         <td>
            {{ $selectedItem->delivery != "" ? $selectedItem->delivery : $selectedItem->delivery }}
         </td>
      </tr>
      @endforeach
   </table>

   <tr></tr>
   <tr></tr>
   <tr></tr>

   <tr>
      <td><b><i>SPECIAL INSTRUCTION:</i></b></td>
      <td>{{ $indented_proposal->special_instructions != '' ? $indented_proposal->special_instructions : '' }}</td>
   </tr>

   <tr>
      <td><b>SHIP VIA:</b></td>
      <td>{{ $indented_proposal->ship_via != '' ? $indented_proposal->ship_via : '' }}</td>
   </tr>

   <tr>
      <td><b>AMOUNT:</b></td>
      <td>{{ $indented_proposal->amount != '' ? $indented_proposal->amount : '' }}</td>
   </tr>

   <tr>
      <td><b>PACKING:</b></td>
      <td>{{ $indented_proposal->packing != '' ? $indented_proposal->packing : '' }}</td>
   </tr>

   <tr>
      <td><b>DOCUMENTS</b></td>
      <td>{{ $indented_proposal->documents != '' ? $indented_proposal->documents : '' }}</td>
   </tr>

   <tr>
      <td><b>INSURANCE:</b></td>
      <td>{{ $indented_proposal->insurance != '' ? $indented_proposal->insurance : '' }}</td>
   </tr>

   <tr>
      <td><b>TERMS OF PAYMENT:</b></td>
      <td>{{ $indented_proposal->terms_of_payment_1 != '' ? $indented_proposal->terms_of_payment_1 : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td>{{ $indented_proposal->terms_of_payment_address != '' ? $indented_proposal->terms_of_payment_address : '' }}</td>
   </tr>

   <tr>
      <td><b>BANK DETAILS:</b></td>
      <td>{{ $indented_proposal->bank_detail_owner != '' ? $indented_proposal->bank_detail_owner : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td>{{ $indented_proposal->bank_detail_address != '' ? $indented_proposal->bank_detail_address : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td style="text-align: left;">{{ $indented_proposal->bank_detail_account_no != '' ? $indented_proposal->bank_detail_account_no : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td style="text-align: left;">{{ $indented_proposal->bank_detail_swift_code != '' ? $indented_proposal->bank_detail_swift_code : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td style="text-align: left;">{{ $indented_proposal->bank_detail_account_name != '' ? $indented_proposal->bank_detail_account_name : '' }}</td>
   </tr>

   <tr>
      <td><b>COMMISSION:</b></td>
      <td>{{ $indented_proposal->commission_note != '' ? $indented_proposal->commission_note : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td style="text-align: left;">{{ $indented_proposal->commission_address != '' ? $indented_proposal->commission_address : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td style="text-align: left;">{{ $indented_proposal->commission_account_number != '' ? $indented_proposal->commission_account_number : '' }}</td>
   </tr>
   <tr>
      <td></td>
      <td style="text-align: left;">{{ $indented_proposal->commission_swift_code != '' ? $indented_proposal->commission_swift_code : '' }}</td>
   </tr>

</body>
</html>
