<!DOCTYPE html>
<html>

<head>
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
  <style>
     /* body
    {
        background-image:url('https://media.glassdoor.com/sqll/561082/arvin-international-marketing-squarelogo-1637307639526.png');
        background-repeat:repeat-y;
        background-position: center;
        background-attachment:fixed;
        background-size:100%;
    } */
    /* Styles go here */

    .page-header, .page-header-space {
    height: 0px;
    }

    .page-footer, .page-footer-space {
    height: 0px;

    }

    .page-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    /* border-top: 1px solid black; for demo */
    /* background: yellow; for demo */
    }

    .page-header {
    position: fixed;
    top: 0mm;
    width: 100%;
    /* border-bottom: 2px solid black;  */
    /* for demo */
    /* background: yellow; for demo */
    }

    /* .page {
    page-break-after: always;
    } */

    @page {
        margin: 5mm 5mm
    }

    @page {
        size: landscape;
    }


    @media print {
        thead {display: table-header-group;} 
        tfoot {display: table-footer-group;}
        
        button {display: none;}
        
        body {margin: 0 0;}

        .adjust tr td th {
        border: 1px solid #999 !important;
        }
    }

    .adjust tr td {
        border: 1px solid #999 !important;
    }

    .adjust tr th, tr td{
        padding: 2px !important;
        margin: 0 !important;
        text-align: center; 
        vertical-align: middle;
    }
  </style>
</head>

<body onload="window.()">

    <div class="page-header" style="text-align: center">
    
    </div>
  
    <div class="page-footer">
    
    </div>
  
    <table style="width: 100%;">
  
      <thead>
        <tr>
          <td>
            <!--place holder for the fixed-position header-->
            <div class="page-header-space"></div>
          </td>
        </tr>
      </thead>
  
      <tbody>
        <tr>
          <td>
            <!--*** CONTENT GOES HERE ***-->
            <div class="row">
                <div class="col">
                    <table class="table table-sm table-bordered text-center adjust" style="font-size: 12px">
                        <tr>
                            <th width="11%" rowspan="2">Date<br>T/T</th>
                            <th rowspan="2">Supplier</th>
                            <th rowspan="2">Qty</th>
                            <th>Price</th>
                            <th colspan="2">Amount</th>
                            <th rowspan="2">Company</th>
                        </tr>
                        <tr>
                            <th>Per (MT)</th>
                            <th>Partial</th>
                            <th>Full</th>
                        </tr>
                        {{--  --}}
                        @foreach ($data as $dd)
                        <tr><td colspan="7"></td></tr>
                          @foreach ($dd->lcdpnego as $item)
                            <tr>
                                <td>{{ $dd->exchangeRateDate }}</td>
                                <td>{{ $item->landedcost_particular->detail->suppliername }}</td>
                                <td>{{ $item->landedcost_particular->detail->blno }}</td>
                                <td>{{ $item->landedcost_particular->detail->vessel }}</td>
                                <td>{{ $dd->percentage }}%</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                         
                       @endforeach
                    </table>
                </div>
                <div class="col">
                    <table class="table table-sm table-bordered text-center adjust" style="font-size: 12px">
                        <tr>
                            <th width="11%">Dollar</th>
                            <th rowspan="2">Invoice No.</th>
                            <th rowspan="2">Bill of Lading</th>
                            <th rowspan="2">Vessel Name</th>
                            <th rowspan="2">Entry No.<br></th>
                            <th>Date</th>
                            <th>Dated</th>
                        </tr>
                        <tr>
                            <th>Rate</th>
                            <th>Debited</th>
                            <th>Filed</th>
                        </tr>
                        {{--  --}}
                        @foreach ($data as $dd)
                        <tr><td colspan="7"></td></tr>
                          @foreach ($dd->lcdpnego as $item)
                          <tr>
                              <td>{{ $dd->exchangeRate }}</td>
                              <td>{{ $item->landedcost_particular->detail->invoiceno }}</td>
                              <td>{{ $item->landedcost_particular->detail->blno }}</td>
                              <td>{{ $item->landedcost_particular->detail->vessel }}</td>
                              <td></td>
                              <td></td>
                              <td></td>
                          </tr>
                          @endforeach
                       @endforeach
                    </table>
                </div>
               </div>
            <!--*** CONTENT GOES HERE ***-->
          </td>
        </tr>
      </tbody>
  
      <tfoot>
        <tr>
          <td>
            <!--place holder for the fixed-position footer-->
            <div class="page-footer-space"></div>
          </td>
        </tr>
      </tfoot>
  
    </table>
  
  </body>


</html>