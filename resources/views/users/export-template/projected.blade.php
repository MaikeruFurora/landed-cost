<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table id="" class="" style="font-size: 10px">
            <tr>
                <th colspan="<?=(count($particulars)+5)?>" style="background: "></th>
            </tr>
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
            <tr>
                <th colspan="<?=(count($particulars)+5)?>" style="background: "></th>
            </tr>
            <tr class="">
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
                            $res = $projectedCost/$item->qtykls;
                            echo number_format($res,4);
                            $res=0;
                        @endphp
                    </th>
                </tr>
          @endforeach
    </table>
</body>
</html>