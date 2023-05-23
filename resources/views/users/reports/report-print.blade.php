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
    height: 80px;
    }

    .page-footer, .page-footer-space {
    height: 100px;

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
    border-bottom: 2px solid black; /* for demo */
    /* background: yellow; for demo */
    }

    /* .page {
    page-break-after: always;
    } */

    @page {
    margin: 15mm
    }

    @media print {
    thead {display: table-header-group;} 
    tfoot {display: table-footer-group;}
    
    button {display: none;}
    
    body {margin: 0;}
    }
  </style>
</head>

<body onload="window.print()">

  <div class="page-header" style="text-align: center">
    <h2>LANDED COST REPORT</h2>
    <h6>MONTHLY SUMMARY</h6>
    <br/>
  </div>

  <div class="page-footer">
    <div class="row justify-content-between">
        <div class="col-4">
            <h5>Prepared By:</h5>
            <p style="border-bottom:1px solid black;font-size:20px;margin-bottom:3px">{{ strtoupper(auth()->user()->name) }}</p>
            <p style="font-size:20px;">{{ date("m/d/Y") }}</p>
        </div>
        <div class="col-4">
            <h5>Checked By:</h5>
            <p style="border-bottom:1px solid black;font-size:20px;margin-bottom:1px">&nbsp;</p>
        </div>
    </div>
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
          <div class="row justify-content-between mt-2">
                <div class="col-5">
                    <table class="table table-borderless">
                        <tr style="font-size: 13px;">
                            <th><h6>ITEM:</h6></th>
                            <th><h6>{{ $item }}</h6></th>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-borderless">
                        <tr style="font-size: 13px;">
                            <th class="text-right"><h6>FROM:</h6></th>
                            <th class="text-left"><h6>{{ date('m/d/y',strtotime($start)) }}</h6></th>
                            <th class="text-right"><h6>TO:</h6></th>
                            <th class="text-left"><h6>{{ date('m/d/y',strtotime($end)) }}</h6></th>
                        </tr>
                    </table>
                </div>
               
            </div>

            <div class="mt-2">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PO DATE</th>
                            <th>INVOICE</th>
                            <th>QTY METRIC TON</th>
                            <th>PRICE MT</th>
                            <th>AVG EXCHANGE RATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $key => $value)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $value['doc_date'] }}</td>
                            <td>{{ $value['invoiceno'] }}</td>
                            <td>{{ number_format($value['qtymt'],4) }}</td>
                            <td>$ {{ number_format($value['priceMetricTon'],4) }}</td>
                            <td>&#8369; {{ $value['avg'] }}</td>
                            
                        </tr>
                        @empty
                        <tr>
                            <th colspan="6" class="text-center">No data available</th>
                        </tr>
                        @endforelse
                        <tr>
                            <th colspan="4" class="text-right">AVERAGE {{ array_sum(array_column($data,'priceMetricTon')) }}</th>
                            <th>$ {{ number_format(array_sum(array_column($data,'priceMetricTon'))/count($data),4) }}</th>
                            <th>&#8369; {{ number_format(array_sum(array_column($data,'avg'))/count($data),4) }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
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