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
        height: 30px;
    }

    .page-footer, .page-footer-space {
        height: 75px;

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
        border-bottom: 1px solid black; /* for demo */
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
        margin: 3mm 10mm;
        size: landscape
    }

    @media print {
    thead {display: table-header-group;} 
    tfoot {display: table-footer-group;}
    
    button {display: none;}
    
    body {margin: 0;}

    .adjust tr td, .adjust tr th{
        padding: 2px 5px !important;
        margin: 0 !important;
        text-align: center; 
        vertical-align: middle;
    }
    .bg-avg tr th {
      background: black;
      color: white;
    }
    }

    .ellipses th,
    .ellipses td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
  </style>
</head>

<body onload="window.print()">

  <div class="page-header" style="text-align: center">
    <p class="font-weight-bold">TOTAL LANDED COST SHEET</p>
    <br/>
  </div>

  <div class="page-footer">
    <div class="row justify-content-between">
        <div class="col-3">
            <small>Prepared By:</small>
            <p style="border-bottom:1px solid black;font-size:13px;margin-bottom:2px">{{ strtoupper(auth()->user()->name) }}</p>
            <p style="font-size:13px;">{{ date("m/d/Y") }}</p>
        </div>
        <div class="col-3">
            <small>Checked By:</small>
            <p style="border-bottom:1px solid black;font-size:13px;margin-bottom:1px">&nbsp;</p>
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
          <table class="table table-sm table-bordered mb-0 mt-1" style="font-size: 11px">
            <tr>
              <th width="5%">Invoice</th>
              <td>{{ $detail->invoiceno }}</td>
              <th width="5%">Supplier</th>
              <td>{{ $detail->suppliername }}</td>
              <th width="5%">PO</th>
              <td>{{ $detail->pono }}</td>
              <th rowspan="2" width="8%">FCL</th>
              <td rowspan="2" >{{ $detail->fcl }}</td>
            </tr>
            <tr>
              <th>BL No.</th>
              <td>{{ $detail->blno }}</td>
              <th>Vessel</th>
              <td>{{ $detail->vessel }}</td>
              <th>Broker</th>
              <td>{{ $detail->broker }}</td>
            </tr>
          </table>
          <!--*** CONTENT GOES HERE ***-->
          @php
              $totalPCS = array_sum($detail->item->pluck(['qtypcs'])->toArray());
              $array = array();
              $prices = $detail->landedcost_particulars->where('particular.p_code','NEG')->where('particular.action',true)->load('lcdpnego')->pluck('lcdpnego')[0] ?? array();
              $countRow = $detail->landedcost_particulars->where('amount','>',0)->whereNotIn('particular.p_code',['NEG']);
              $sumTotal=0;
              $superSumTotal=0;
              $totalDollar=0;
          @endphp
          
            <table class="table table-bordered adjust text-center mt-1" style="font-size: 11px">
                <thead class="adjust">
                    <tr>
                        <th rowspan="2" width="">ITEM SACKS</th>
                        <th colspan="{{ (count($countRow)+5) }}">PARTICULARS</th>
                        <th rowspan="2">TOTAL</th>
                        <th rowspan="2">PROJECTED LANDED<br>COST PER ITEM</th>
                    </tr>
                    <tr>
                        <th>QUANTITY</th>
                        <th>DOLLAR<br>PRICE</th>
                        <th>DOLLAR<br>RATE</th>
                        <th>TOTAL<br>(USD)</th>
                        <th>TOTAL<BR>(PHP)</th>
                        @foreach ($detail->landedcost_particulars->where('amount','>',0)->whereNotIn('particular.p_code',['NEG']) as $item)
                        <th>{{ strtoupper($item->particular->p_name) }}</th>
                        @endforeach
                        
                    </tr>
                </thead>
                @if (count($prices)!=0)
                <tbody>
                  @foreach ($detail->item as $i => $item)
                  <tr>
                      <td class="text-left">{{ $item->description }}</td>
                      <td>{{ number_format($item->qtypcs,4) }}</td>
                      <td>{{ $prices[0]->prices[$i] ?? 0 }}</td>
                      <td>
                        @php $sum=0; @endphp
                        @if (count($prices)!=0)
                          @foreach ($prices as $price)
                              @php  $sum+=$price->exchangeRate; @endphp
                          @endforeach
                          {{  $sum/count($prices)}}
                        @endif
                      </td>
                      <td>
                          @php
                          $totalDollar+=$prices[0]->prices[$i]*$item->qtypcs;
                          echo number_format($prices[0]->prices[$i]*$item->qtypcs,2) ?? 0;   
                          @endphp
                      </td>
                      <td>{{ number_format(($prices[0]->prices[$i]*$item->qtypcs*$sum/count($prices)),4) ?? 0 }}</td>
                      @foreach ($detail->landedcost_particulars->where('amount','>',0)->whereNotIn('particular.p_code',['NEG']) as $key => $val)
                          @php  
                              $total = 0; $total = ($val->amount/$totalPCS); $final = $total*$item->qtypcs;
                              $array[]=array($item->itemcode => $final);
                          @endphp
                      <td>{{ number_format($final,4) }}</td>
                      @endforeach
                      <td>
                          @php
                              $sumTotal+=array_sum(array_column($array,$item->itemcode));
                              echo number_format(
                                (($prices[0]->prices[$i]*$item->qtypcs*$sum/count($prices))+array_sum(array_column($array,$item->itemcode))  
                              ),4);
                          @endphp
                      </td>
                      <td>
                          @php
                            $superTotal=0;
                            $superTotal=($prices[0]->prices[$i]*$item->qtypcs*$sum/count($prices))+array_sum(array_column($array,$item->itemcode));
                            $superSumTotal+=($superTotal/$item->qtypcs);
                            echo '&#8369; '.number_format(($superTotal/$item->qtypcs),4);
                          @endphp
                      </td>
                  </tr>
                  @endforeach
                  <tr>
                      <th>TOTAL</th>
                      <td>{{ number_format($totalPCS,4) }}</td>
                      <td colspan="2"></td>
                      <td>${{ number_format($totalDollar,4) }}</td>
                      <td></td>
                      @foreach ($detail->landedcost_particulars->where('amount','>',0)->whereNotIn('particular.p_code',['NEG']) as $item)
                          <td>&#8369;{{ number_format($item->amount,4) }}</td>
                      @endforeach
                      <th></th>
                      <th>&#8369; {{ number_format(($superSumTotal/count($detail->item)),4) }}</th>
                  </tr>
                  <tr>
                    <th class="bg-avg">AVG. PROJECTED LANDEDCOST</th>
                    <td colspan="{{ (count($countRow)+6) }}"></td>
                    <td>{{ number_format(($sumTotal/$totalPCS),4) }}</td>
                  </tr>
              </tbody>
                @endif
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