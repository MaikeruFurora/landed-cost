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
                <th>DUTIES DATE</th>
                <th>INVOICE NO</th>
                <th>QUANTITY</th>
                <th>QTYKLS</th>
                <th>ACTUAL QTYKLS</th>
                <th>QTYMT</th>
                <th>ACTUAL QTYMT</th>
                <th>FCL</th>
                <th>DESCRIPTION</th>
                <th>DESTINATION</th>
                <th>CREATED AT</th>
                <th>ETA</th> 
                <th>LC OPENING</th>
                <th>LC/DP NEGO</th>
                <th>CUSTOM DUTY</th>
                <th>INSURANCE</th>
                <th>SHIPPING FREIGHT</th>
                <th>VAT</th>
                <th>BROKERTAGE FEE</th>
                <th>ADDTIONAL DUTY</th>
                <th>BANK INTEREST CHARGE</th>
                <th>SHIPPING CHARGE IN DOLLAR</th>
                <th>TRUCKING EXPENSE</th>
                <th>SHIPPING FEE</th>
                <th>OTHER EXPENSE 1</th>
                <th>OTHER EXPENSE 2</th>
                <th>OTHER EXPENSE 3</th>
                <th>PROJECTED COST</th> 
            </tr>
        </thead>
        <tbody>
           @forelse ($data as $item)
                <tr>
                    <td>{{ $item->custom_duty_date }}</td>
                    <td>{{ $item->invoiceno }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->qtykls }}</td>
                    <td>{{ $item->actualQtyKLS }}</td>
                    <td>{{ $item->qtymt }}</td>
                    <td>{{ $item->actualQtyMT }}</td>
                    <td>{{ $item->fcl }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->destination }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->eta }}</td> 
                    <td>{{ $item->{'LC Opening Charge'} }}</td>
                    <td>{{ $item->{'LC/DP NEGO'} }}</td>
                    <td>{{ $item->{'Custom Duty'} }}</td>
                    <td>{{ $item->{'Insurance'} }}</td>
                    <td>{{ $item->{'Shipping Freight'} }}</td>
                    <th>{{ $item->{'VAT'} }} </th> 
                    <td>{{ $item->{'Brokerage Fee'} }}</td>
                    <td>{{ $item->{'Additional Duty'} }}</td>
                    <td>{{ $item->{'Bank Interest Charge'} }}</td>
                    <td>{{ $item->{'Shipping Charge in Dollar'} }}</td>
                    <td>{{ $item->{'Trucking Expenses'} }}</td>
                    <td>{{ $item->{'Shipping Fee'} }}</td>
                    <td>{{ $item->{'Other Expense 1'} }}</td> 
                    <td>{{ $item->{'Other Expense 2'} }}</td>
                    <td>{{ $item->{'Other Expense 3'} }}</td>
                    <td>{{ $item->{'Projected Cost'} }}</td>
                </tr> 
            @empty
                <tr>
                    <th colspan="" style="text-align:center">No data available</th>
                </tr>
           @endforelse
           
        </tbody>
    </table>
</body>
</html>