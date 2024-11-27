<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <thead>
        <tr>
            <th colspan="6" style="background: "></th>
        </tr>
        <tr>
            <th>Company:</th>
            <th colspan="5">{{ $data[0]->companyname ?? '' }}</th>
        </tr>
        <tr>
            <th>Date Range:</th>
            <th colspan="5">{{ $from .' - '. $to }}</th>
        </tr>
        <tr>
            <th>Generate By:</th>
            <th colspan="5">{{ auth()->user()->name }}</th>
        </tr>
        <tr>
            <th colspan="6" style="background: "></th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Reference No.</th>
            <th>Invoice No.</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Particular</th>
        </tr>
    </thead>
    <tbody>
       @forelse ($data as $item)
            <tr>
                <th>{{ $item->transaction_date  }}</th>
                <th>{{ $item->referenceno  }}</th>
                <th>{{ $item->invoiceno  }}</th>
                <th>{{ number_format($item->amount,4) }}</th>
                <th>{{ $item->description  }}</th>
                <th>{{ $item->p_name  }}</th>
            </tr>
        @empty
            <tr>
                <th colspan="6" style="text-align:center">No data available</th>
            </tr>
       @endforelse
    </tbody>
</body>
</html>