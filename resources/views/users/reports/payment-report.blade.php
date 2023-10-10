<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    @php
        $data = DB::select("select 
                    a.id,
                    a.invoiceno,
                    a.description,
                    p_name,b.amount,
                    CONVERT(date,transaction_date) tranDate
                from details a
                    left join landedcost_particulars b on b.detail_id=a.id
                    left join particulars c on b.particular_id=c.id
                    left join lcdpnegos d on d.landedcost_particular_id=b.id
                where 
                        p_code in ('CD','FR')
                    and
                        transaction_date between '2023-09-1' and '2023-09-30'");
    @endphp
    <table class="table table-sm table-bordered" style="font-size: 10px">
        <thead>
            <tr>
                <th>INVOICE</th>
                <th>FREIGHT</th>
                <th>DUTIES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->invoiceno }}</td>
                    @php
                        $projectedCost=0;
                        $datapart = DB::select("select b.amount from details a 
                                            inner join landedcost_particulars b on b.detail_id = a.id
                                            inner join particulars c on b.particular_id = c.id  where p_code in ('CD','FR') and a.id={$item->id}");
                        $projectedCost = array_sum(array_column($datapart,'amount'));
                    @endphp
                    @foreach ($datapart as $i => $value)
                        <td>
                            {{ empty($value->amount)?'': number_format($value->amount,4) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>