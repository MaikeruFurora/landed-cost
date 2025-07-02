@extends('../users/dollarbook/partials/template')
@section('content')
<table class="table table-borderless">
    <tr>
        <td width="13%" class="pl-0">RE</td>
        <th>: <span style="border-bottom:.5px solid black">{{ strtoupper($data->subject) }}</span></th>
    </tr>
    <tr>
        <td class="pl-0"></td>
    </tr>
    <tr>
        <td class="pl-0" colspan="2"><p>Sir/ Madame:</p></td>
    </tr>
    <tr>
        <td class="pl-0"></td>
    </tr>
    <tr>
        <td class="pl-0" colspan="2">
            <p>
                This is to authorize BDO to debit <b>{{ $data->account->branch->bank->company->companyname }}</b> 
                PHP Account number <b>{{ collect($dd->accounts)->whereIn('currencyType', 'PHP')->where('accountStatus',1)->first()['accountNo'] ?? '' }}</b>  
                for the amount of <b class="{{ !empty($data->isManual)?'makeUnderLine':'' }}">PHP {{ number_format($data->exchangeRate*$data->amount,2) }}</b> 
                and Credit to <b>{{ $data->account->branch->bank->company->companyname }}</b> 
                USD Account No. <b>{{ collect($dd->accounts)->whereIn('currencyType', 'USD')->where('accountStatus',1)->first()['accountNo'] ?? '' }}</b> 
                as payment for the purchase of the <b>USD {{ number_format($data->amount,2) }}</b> at the exchange rate of <b>{{ number_format($data->exchangeRate,2) }}</b>.
                {{-- {{ $dd->accounts[0]['currencyType']=='PHP' ? $dd->accounts[0]['accountNo'] :'' }} --}}
            </p>
        </td>
    </tr>
</table>
@endsection