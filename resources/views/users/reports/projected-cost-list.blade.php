<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
   
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <style>
          @media print{@page {size: landscape}}
         
    </style>
</head>
<body>
    <button class="btn btn-sm btn-primary" onclick="ExportToExcel('xlsx')">Export table to excel</button>
    <button class="btn btn-sm btn-primary" >Print</button>
    {{--  onclick="printout('#tbl_exporttable_to_xls')" --}}
    <table id="tbl_exporttable_to_xls" class="table table-sm table-bordered table-striped" style="font-size: 10px">
        <tr class="bg-dark text-white ">
            <th>Invoice</th>
            <th>Description</th>
            <th>AVG ExRate(PHP)</th>
            <th>Qty(Kls) </th>
            @foreach ($particulars as $item)
                <th>{{ $item->p_name }}</th>
            @endforeach
            <th>Projected Cost</th>
        </tr>
        @php
            $data = DB::select("select id,invoiceno,qtykls,description from details where posted_at between '2023-01-01' and '2023-07-01'");
        @endphp
        @foreach($data as $item)
            <tr>
                <th class="">{{ $item->invoiceno }}</th>
                <th class="">{{ $item->description }}</th>
                @php
                    $projectedCost=0;
                    $avgRes = DB::select("select  b.amount, d.exchangeRate from details a 
                                                inner join landedcost_particulars b on b.detail_id = a.id
                                                inner join lcdpnegos d on d.landedcost_particular_id=b.id
                                                where a.id={$item->id}");
                    $avgInPHP = (array_sum(array_column($avgRes,'exchangeRate')));
                @endphp
                <th>
                    {{ (empty($avgInPHP)?'':($avgInPHP/count($avgRes))) }}
                </th>
                <th class="">{{ $item->qtykls }}</th>
                @php
                    $projectedCost=0;
                    $datapart = DB::select("select b.amount from details a 
                                        inner join landedcost_particulars b on b.detail_id = a.id
                                        inner join particulars c on b.particular_id = c.id  where a.id={$item->id}");
                     $projectedCost = array_sum(array_column($datapart,'amount'));
                @endphp
                @foreach ($datapart as $i => $value)
                    <td>
                        {{ empty($value->amount)?'': number_format($value->amount,4) }}
                    </td>
                @endforeach
                <th>
                    @php
                        $res = $projectedCost/$item->qtykls;
                        echo number_format($res,4);
                        $res=0;
                    @endphp
                </th>
            </tr>
        @endforeach
    </table>
    <script src="{{ asset('assets/plugins/printout/printout.js') }}"></script>
    <script>
        
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('tbl_exporttable_to_xls');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        }

        //  printout('tbl_exporttable_to_xls', {
        //     pageTitle: window.document.title, // Title of the page
        //     importCSS: true, // Import parent page css
        //     inlineStyle: true, // If true it takes inline style tag 
        //     autoPrint: true, // Print automatically when the page is open
        //     autoPrintDelay: 1000, // Delay in milliseconds before printing
        //     closeAfterPrint: true, // Close the window after printing
        //     header: null, // String or element this will be appended to the top of the printout
        //     footer: null, // String or element this will be appended to the bottom of the printout
        //     noPrintClass: 'no-print' // Class to remove the elements that should not be printed
        // })

    </script>
</body>
</html>