<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body onload="window.print()">
   {{-- <table class="table table-bordered table-sm" width="100%">
   <thead>
    <tr class="text-center">
        <th>From</th>
        <th>To</th>
        <th>Amount</th>
    </tr> --}}
    {{-- <tr class="text-center">
        @foreach ($companies as $item)
            <td  rowspan="2">{{ $item->companyname }}</td>
        @endforeach
    </tr> --}}
   {{-- </thead>
   <tbody>
    @foreach ($data as $item)
    <tr class="text-center">
        <td>
            <b>{{ $item->account->accountNo }}</b>
            <br>
            {{ $item->account->branch->bank->company->companyname }}
        </td>
        <td>
            <b>{{ $item->toAccountNo }}</b>
            <br>
            {{ !empty($item->toName)? $item->toName : "NOT AVAILABLE" }}
        </td>
        <td>{{ number_format($item->amount,2) }}</td>
        @php
            $total+=$item->amount;
        @endphp --}}
        {{-- @php
             $data = DB::table('companies')->select('accounts.accountNo')
             ->join('banks','banks.company_id','companies.id')
             ->join('branches','branches.bank_id','banks.id')
             ->join('accounts','accounts.branch_id','branches.id')
             ->first();
             foreach ($data as $key => $value) {
                echo "<td>".$item->amount."</td>";
             }
        @endphp --}}
    {{-- </tr>
    @endforeach
   </tbody>
   <tfoot>
        <tr>
            <th colspan="2" class="text-right">Total</th> --}}
            {{-- <th>{{ array_sum(array_column($data,"amount")) }}</th> --}}
        {{-- </tr>
   </tfoot>
   </table> --}}
   <h4 class="mb-3">TRANSFER OF FUND</h4>
   @foreach ($companies as $item)
   <table class="table table-sm mb-2 table-bordered">
    <thead>
        <tr>
            <th colspan="4">{{ $item->companyname }}</th>
        </tr>
        <tr>
            <th width="25%">Date</th>
            <th width="25%">Amount</th>
            <th width="25%">Transfer To</th>
            <th width="25%">Name</th>
        </tr>
    </thead>
    <tbody>
        @php

            $ddata = DB::table('bank_histories')->select('companies.id','companyname','accounts.accountNo','amount','toAccountNo','types','bank_histories.created_at','toName')
                        ->join('accounts','bank_histories.account_id','accounts.id')
                        ->join('branches','accounts.branch_id','branches.id')
                        ->join('banks','branches.bank_id','banks.id')
                        ->join('companies','banks.company_id','companies.id')
                        ->where('types','TOF')
                        ->where('companies.id',$item->id)
                        ->groupBy('companies.id','companyname','accounts.accountNo','amount','toAccountNo','types','bank_histories.created_at','toName')
                        ->get()
        @endphp
        @forelse($ddata as $value)
            <tr>
                <td>{{ date("m-d-y",strtotime($value->created_at)) }}</td>
                <td>{{ $value->amount }}</td>
                <td>{{ $value->toAccountNo }}</td>
                <td>{{ $value->toName }}</td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="4">No data available</td>
            </tr>
        @endforelse
    </tbody>
   </table>
   @endforeach
   
</body>
</html>