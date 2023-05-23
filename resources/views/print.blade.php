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
    height: 60px;
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

    table.table-bordered{
        border:1.1px solid black;
        margin-top:20px;
    }
    table.table-bordered > thead > tr > th{
        border:1.1px solid black;
    }
    table.table-bordered > tbody > tr > td{
        border:1.1px solid black;
    }

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
    <h2>TOTAL LANDED COST SHEET</h2>
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
          <div class="page content-box">
            
            <div class="row justify-content-between mt-2">
                <div class="col-6">
                    <table class="table table-sm">
                        <tr>
                            <td><b>Supplier</b></td>
                            <td>{{ $detail->suppliername }}</td>
                        </tr>
                        <tr>
                            <td><b>Vessel</b></td>
                            <td>{{ $detail->vessel }}</td>
                        </tr>
                        <tr>
                            <td><b>PO</b></td>
                            <td>{{ $detail->pono }}</td>
                        </tr>
                       
                        <tr>
                            <td><b>Invoice No</b></td>
                            <td>{{ $detail->invoiceno }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm">
                        <tr>
                            <td><b>ITEM Description</b></td>
                            <td>{{ $detail->description }}</td>
                        </tr>
                        <tr>
                            <td><b>ITEM Qty(KLS)</b></td>
                            <td>{{ number_format($detail->qtykls) }}</td>
                        </tr>
                        <tr>
                            <td><b>Actual Qty(KLS)/(MT)</b></td>
                            <td>{{ number_format($detail->actualQtyKLS) }} / {{ number_format($detail->actualQtyMT) }}</td>
                        </tr>
                        <tr>
                            <td><b>Broker</b></td>
                            <td>{{ $detail->broker }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-2">
                <table class="table table-bordered" style="font-size: 16px;">
                    <thead >
                        <tr>
                            <th>L/N</th>
                            <th>PARTICULAR</th>
                            <th>REFERENCE NO</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>
                    @php 
                    $i=0;
                    $tper=0;
                    $totalPerNego=0;
                    $totalNego=0;
                    $totalLandedCost=0;
                    $negArr = [];
                    @endphp
                    @foreach($detail->landedcost_particulars->sortBy('particular.p_sort', SORT_REGULAR, false) as $landedCostParticular)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <th>{{ $landedCostParticular->particular->p_name }}</th>
                            <td>{{ $landedCostParticular->transaction_date.' '.$landedCostParticular->referenceno }}</td>
                            <th>&#8369;&nbsp;{{ number_format($landedCostParticular->amount,2) }}</th>
                            @php $totalLandedCost+=$landedCostParticular->amount @endphp
                            @if($landedCostParticular->particular->action && $landedCostParticular->particular->p_code=='NEG')
                                @php
                                
                                    $negArr[] = $landedCostParticular;
                                
                                @endphp
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <th colspan="2">
                            <div class="row justify-content-between">
                                <div class="col-8">
                                TOTAL LANDED COST
                                </div>
                                <div class="col-4">
                                &#8369;&nbsp;{{ number_format($totalLandedCost,4) }}
                                </div>
                            </div>
                            </th>
                            <th colspan="2">
                            <div class="row justify-content-between">
                                <div class="col-8">
                                TOTAL PROJECTED COST(PER KILO)
                                </div>
                                <div class="col-4 text-left">
                                &#8369;&nbsp;
                                @if(empty($detail->actualQtyKLS) || $detail->actualQtyKLS==0)
                                    {{ number_format($totalLandedCost/$detail->qtykls,4) }}
                                @else
                                    {{ number_format($totalLandedCost/$detail->actualQtyKLS,4) }}
                                @endif
                                </div>
                            </div>
                            </th>
                        </tr>
                      
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
            <div class="row justify-content-between ">
                <div class="col-5">
                    <table class="table" style="font-size: 15px;">
                        <tr>
                            <td><b>LC OPENING CHARGE</b></td>
                            @if($detail->lcopeningcharges->open_amount->lc_reference ?? false)
                                <td>{{ $detail->lcopeningcharges->open_amount->lc_reference }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                        </tr>
                        <tr>
                            <td><b>Total LC Opening</b></td>
                            @if($detail->lcopeningcharges->open_amount->lc_amount ?? false)
                                <td>{{ number_format($detail->lcopeningcharges->open_amount->lc_amount,4) }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                        </tr>
                        <tr>
                            <td><b>Per Invoice MT</b></td>
                            @if($detail->lcopeningcharges->open_amount->lc_mt ?? false)
                                <td>&#8369;&nbsp;{{ number_format($detail->qtymt,4) }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                        </tr>
                        <tr>
                            <td><b>Total LC MT</b></td>
                            @if($detail->lcopeningcharges->open_amount->lc_mt ?? false)
                                <td>&#8369;&nbsp;{{ number_format($detail->lcopeningcharges->open_amount->lc_mt,4) }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                        </tr>
                        <tr>
                            <td><b>Amount Distribution</b></td>
                            @if($detail->lcopeningcharges->open_amount ?? false)
                                <td>&#8369;&nbsp;{{ number_format((($detail->qtymt/$detail->lcopeningcharges->open_amount->lc_mt ?? 0)*$detail->lcopeningcharges->open_amount->lc_amount?? 0),2) }}</td>
                            @else
                                <td>N/A</td>
                            @endif
                        </tr>
                    </table>
                </div>
                <div class="col-7">
                    <table class="table" style="font-size: 15px;">
                        <tr>
                            <td><b>LC/DP NEGO</b></td>
                            <td class="text-right"><b>Price Per Metric Ton</b></td>
                            <td>
                                {{  $negArr[0]->lcdpnego[0]->priceMetricTon ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>
                    <table class="table text-center table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Percentage</th>
                                <th>Amount</th>
                                <th>ExchangeRate</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        @forelse($negArr[0]->lcdpnego as $value)
                        @php
                        $tper += $value->percentage;
                        $totalPerNego=($value->amount*$value->exchangeRate);
                        $totalNego+=$totalPerNego;
                        @endphp
                        <tr>
                            <td>{{ date("m/d/y",strtotime($value->exchangeRateDate)) }}</td>
                            <td>{{ $value->percentage }}&nbsp;%</td>
                            <td>&#8369;&nbsp;{{ $value->amount }}</td>
                            <td>&#8369;&nbsp;{{ $value->exchangeRate }}</td>
                            <td>&#8369;&nbsp;{{ number_format($totalPerNego,2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No data here</td>
                        </tr>
                        @endforelse
                        <tr>
                            <th></th>
                            <th class="text-center">{{ $tper }}%</th>
                            <th class="text-right"></th>
                            <th colspan="2" class="text-right">&#8369;&nbsp; {{ number_format($totalNego,4) }}</th>
                        </tr>             
                    </table>
                </div>
            </div>
            </div>

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