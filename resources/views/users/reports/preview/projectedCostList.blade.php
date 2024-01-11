<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
   
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
</head>
<body>
   <div class="container-fluid">
    @if ($search!='All')
    <table id="" class="table table-sm table-striped table-bordered" style="font-size: 10px">
        @if ((count($data)==0))
        <tr>
            <th colspan="<?=(count($particulars)+5)?>" style="background:" class="text-center">
                <h6>Filtering based on the date posted ,If not reflected implies that the item has not yet done for costing.</h6>
            </th>
        </tr>
        @endif
        <tr>
            <th>Search:</th>
            <th colspan="<?=(count($particulars)+5)?>">{{ $search }}</th>
        </tr>
        <tr>
            <th>Date Range:</th>
            <th colspan="<?=(count($particulars)+5)?>">{{ $from .' - '. $to }}</th>
        </tr>
        <tr>
            <th>Generate By:</th>
            <th colspan="<?=(count($particulars)+5)?>">{{ auth()->user()->name }}</th>
        </tr>
        <tr class="bg-secondary text-white" style="border:1px solid black">
            <th>Invoice</th>
            <th>Description</th>
            <th>AVG ExRate(PHP)</th>
            <th>Qty(Kls) </th>
            @foreach ($particulars as $item)
                <th>{{ $item->p_name }}</th>
            @endforeach
            <th>Projected Cost</th>
        </tr>
       
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
                        $res = ($projectedCost/$item->qtykls) ?? 0;
                        echo number_format($res,4);
                        $res=0;
                    @endphp
                </th>
            </tr>
      @endforeach
    </table>
    @else
    @php
        $c=0;
        $arr = [];
        $coll = collect($data);
        $res  = $coll->groupBy('description');
        $key  = collect($data)->sortBy('description')->unique(['description'])->pluck('description');
       
        for($i=0; $i <count($key) ; $i++){
            if ($key[$i]!='EMPTY SACK') {
                $arr[]=[
                    'result'=> $res[$key[$i]],
                    'name'  => $key[$i]
                ];
            }
        }
        $tmp = array();
    @endphp
    <div class="accordion" id="accordionExample">
    @foreach ($arr as $key =>  $item)
    @php
        foreach($item['result']->sortBy('description')  as $arg){
            $tmp[$arg->suppliername][] = $arg;
        }
    @endphp

        <div class="card">
          <div class="card-header p-0 bg-dark" id="headingOne-{{ $key }}">
            <h2 class="mb-0">
              <button class="btn btn-block text-left text-white" style="font-size: 11px" type="button" data-toggle="collapse" data-target="#collapseOne-{{ $key }}" @if ($key==0) aria-expanded="true" @endif  aria-controls="collapseOne-{{ $key }}">
                {{ ++$c.'. '.$item['name'] }}
              </button>
            </h2>
          </div>
      
          <div id="collapseOne-{{ $key }}" class="collapse @if ($key==0) show active @endif " aria-labelledby="headingOne-{{ $key }}" data-parent="#accordionExample">
            <div class="card-body">
                @foreach ($tmp as $k=> $dataVal)
                    <table id="" class="table table-sm table-striped table-bordered" style="font-size: 10px">
                        <tr class="bg-secondary text-white" style="border:1px solid black">
                            <th>Suppliername</th>
                            <th>Invoice</th>
                            <th>Description</th>
                            <th>AVG ExRate (PHP)</th>
                            <th>Qty(Kls) </th>
                            @foreach ($particulars as $itempar)
                                <th>{{ $itempar->p_name }}</th>
                            @endforeach
                            <th>Projected Cost</th>
                        </tr>
                        @foreach($dataVal as $val)
                        <tr>
                            <td class="">{{ $val->suppliername }}</td>
                            <td class="">{{ $val->invoiceno }}</td>
                            <td class="">{{ $val->description }}</td>
                            @php
                                $projectedCost=0;
                                $avgRes = DB::select("select  b.amount, d.exchangeRate from details a 
                                                            inner join landedcost_particulars b on b.detail_id = a.id
                                                            inner join lcdpnegos d on d.landedcost_particular_id=b.id
                                                            where a.id={$val->id}");
                                $avgInPHP = (array_sum(array_column($avgRes,'exchangeRate')));
                            @endphp
                            <th>
                                {{ number_format((empty($avgInPHP)?'':($avgInPHP/count($avgRes))),4) }}
                            </th>
                            <th class="">{{ $val->qtykls }}</th>
                            @php
                                $projectedCost=0;
                                $datapart = DB::select("select b.amount from details a 
                                                    inner join landedcost_particulars b on b.detail_id = a.id
                                                    inner join particulars c on b.particular_id = c.id  where a.id={$val->id}");
                                $projectedCost = array_sum(array_column($datapart,'amount'));
                            @endphp
                            @foreach ($datapart as $i => $value)
                                <td>
                                    {{ empty($value->amount)?'': number_format($value->amount,4) }}
                                </td>
                            @endforeach
                            <th>
                                @php
                                    $res = ($projectedCost/$val->qtykls) ?? 0;
                                    echo number_format($res,4);
                                    $res=0;
                                @endphp
                            </th>
                        </tr>
                        @endforeach
                    </table>
                    @php
                        $tmp=[];    
                    @endphp
                @endforeach
               
            </div>
          </div>
        </div>
    @endforeach
      
    </div>
    @endif
   </div>
   <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
   <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>