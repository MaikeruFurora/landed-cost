<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="6"></th>
            </tr>
            <tr>
                <th>Company:</th>
                <th colspan="3">{{ $data[0]->companyname ?? '' }}</th>
            </tr>
            <tr>
                <th>Date Range:</th>
                <th colspan="3">{{ $from .' - '. $to }}</th>
            </tr>
            <tr>
                <th>Generate By:</th>
                <th colspan="3">{{ strtoupper(auth()->user()->name) }}</th>
            </tr>
            <tr>
                <th colspan="6"></th>
            </tr>
            <tr>
                <th>TRANSACTION</th>
                <th>EXCHANGE DATE</th>
                <th>EXCHANGE RATE</th>
                <th>AMOUNT</th>
            </tr>
        </thead>
        <tbody>
           @forelse ($data as $item)
                <tr>
                    <th>{{ $item->transactionNo  }}</th>
                    <th>{{ $item->exchangeRateDate  }}</th>
                    <th>{{ number_format($item->exchangeRate,4)  }}</th>
                    <th>$ {{ number_format($item->amount,4)  }}</th>
                </tr>
                {{-- @php
                    $totalPeso+=($item->exchangeRate*$item->amount);
                @endphp --}}
            @empty
                <tr>
                    <th colspan="4" style="text-align:center">No data available</th>
                </tr>
           @endforelse
           {{-- <tr>
            <td colspan="3">Total</td>
            <td colspan="">₱ {{ number_format($totalPeso,4) }}</td>
           </tr> --}}
        </tbody>
    </table>
</body>
</html>