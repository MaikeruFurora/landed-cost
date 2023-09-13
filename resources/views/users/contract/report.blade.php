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
          padding: 5px !important;
          margin: 3 !important;
          /* text-align: center;  */
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
                    <table class="table table-bordered " style="font-size: 12px">
                      <tr>
                          <td>Date</td>
                          <td>Supplier</td>
                          <td>Contract No.</td>
                          <td>Total MT</td>
                          <td>Price MT</td>
                          <td>Amount (USD)</td>
                          <td>Paid (USD)</td>
                          <td>Percent</td>
                          <td>Dollar Rate</td>
                          <td>Amount (PHP)</td>
                      </tr>
                        @forelse ($data as $item)
                        <tr>
                              <td>{{ $item->exchangeRateDate }}</td>
                              <td>{{ $item->suppliername }}</td>
                              <td>{{ $item->contract_no }}</td>
                              <td>{{ $item->metricTon }}</td>
                              <td>$ {{ $item->priceMetricTon }}</td>
                              <td>$ {{ $item->amountUSD }}</td>
                              <td>$ {{ $item->paidAmountUSD }}</td>
                              <td>{{ $item->percentage }} %</td>
                              <td>&#8369; {{ $item->exchangeRate }}</td>
                              <td>&#8369; {{ $item->amountPHP }}</td>
                          </tr>
                        @empty
                          <tr class="text-center">
                            <td colspan="20">No Data</td>
                          </tr>
                        @endforelse
                    </table>
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