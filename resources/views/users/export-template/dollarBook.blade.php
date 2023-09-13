<table>
<tr>
    <td>INVOICE</td>
    <td>REFERENCE</td>
    <td>MT</td>
    <td>REFRENCE ONE</td>
    <td>PERCENTAGE</td>
    <td>AMOUNT</td>
    <td>EXCHANGE RATE</td>
    <td>AMOUNT</td>
    <td>REF</td>
    <td>DATED</td>
</tr>
    @foreach ($data as $item)    
        <tr>
            <td>{{ $item->INVOICE }}</td>
            <td>{{ $item->REFERENCE  }}</td>
            <td>{{ $item->MT }}</td>
            <td>{{ $item->REFRENCE_ONE }}</td>
            <td>{{ $item->PERCENTAGE }}</td>
            <td>{{ $item->AMOUNT_ONE }}</td>
            <td>{{ $item->EXCHANGE_RATE }}</td>
            <td>{{ $item->AMOUNT_TWO }}</td>
            <td>{{ $item->REF }}</td>
            <td>{{ $item->DATED }}</td>
        </tr>
    @endforeach
</table>