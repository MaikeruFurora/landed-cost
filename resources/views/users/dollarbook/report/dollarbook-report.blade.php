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

<body onload="window.print()">

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
                    <table class="table table-sm table-bordered text-center adjust" style="font-size: 10px">
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
                        @for ($i = 0; $i < 20; $i++)
                            <tr>
                                <td>-</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    </table>
                </div>
                <div class="col">
                    <table class="table table-sm table-bordered text-center adjust" style="font-size: 10px">
                        <tr>
                            <th width="11%">Dollar<br>Rate</th>
                            <th>Invoice No.</th>
                            <th>Bill of Lading</th>
                            <th>Vessel Name</th>
                            <th>Entry No.<br></th>
                            <th>Date<br>Debited</th>
                            <th>Dated<br>Filed</th>
                        </tr>
                        {{--  --}}
                        @for ($i = 0; $i < 20; $i++)
                            <tr>
                                <td>-</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
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