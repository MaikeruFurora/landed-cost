@extends('../users/dollarbook/partials/template')
@section('content')
<table class="table table-borderless">
    <tr>
        <td width="13%" class="pl-0">RE</td>
        <th>: <span style="border-bottom:.5px solid black">{{ strtoupper($data->subject) }}</span></th>
    </tr>
    <tr>
        <td class="pl-0" colspan="2"><p>Sir/ Madame:</p></td>
    </tr>
    <tr>
        <td class="pl-0" colspan="2"><p>This is to request your good office to transfer funds as follows:</p></td>
    </tr>
    <tr>
        <td class="pl-0">AMOUNT</td>
        <th>: {{ strtoupper($data->account->currencyType) }} {{ number_format($data->amount,2) }}</th>
    </tr>
    <tr>
        <td class="pl-0"></td>
        <th style="font-size: 23px">{{ ucwords(Helper::numberToWord($data->amount,$data->account->currencyType)) }}</th>
    </tr>
    <tr>
        <td class="pl-0">FROM</td>
        <th>: A/C No. {{ $data->account->accountNo }} ({{ $data->account->branch->bank->company->companyname }})</th>
    </tr>
    <tr>
        <td class="pl-0">TO</td>
        <th>: A/C No. {{ $data->toAccountNo }} ({{ $data->toName }}) {{ $data->toBankName }} {{ empty($data->toBranchName)?'':'- '.$data->toBranchName }}</th>
    </tr>
    <tr>
        <td class="pl-0">PURPOSE</td>
        <td>: <?= html_entity_decode($data->purpose)?></td>
    </tr>
</table>
@endsection