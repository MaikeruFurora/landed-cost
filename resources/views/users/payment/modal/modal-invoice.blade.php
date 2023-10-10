<x-component-b4.modal
    identify='modalInvoice'
    title='Invoice Search'
    size='xl'
    save='0'
    close='1'
>
<div class="mt-4">
    {{-- <x-payment.search-invoice
        action="{{ route('authenticate.payment.invoice.search') }}"
        url="{{ route('authenticate.payment.invoice.save') }}"
        id='seachInvoiceUnderPaymentForm'
    /> --}}

    <form action="{{ route('authenticate.payment.invoice.store') }}" id="shipmentPaymentForm" autocomplete="off">@csrf
       <div class="table-responsive">
        <table
            data-url="{{ route('authenticate.payment.invoice.list',['cp']) }}"
            class="table adjust table-bordered table-sm dt-responsive nowrap adjust"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px" id="shipmentContractMT">
            <thead>
                <tr>
                    <th>Reference (Invoice)</th>
                    <th>Metric Ton</th>
                    <th>Price MT</th>
                    <th>Amount (USD)</th>
                    <td width="8%">Action</td>
                </tr>
                <tr>
                    <th><input type="text" class="form-control form-control-sm" name="reference" required></th>
                    <th><input type="text" class="form-control form-control-sm amount-class" name="metricTon" required></th>
                    <th><input type="text" class="form-control form-control-sm amount-class" name="priceMetricTon" required></th>
                    <th><input type="text" class="form-control form-control-sm amount-class" name="amountUSD" required readonly></th>
                    <th>
                        <input  type="hidden" name="contract_payment" required>
                        <button type="submit" class="btn btn-sm btn-primary btn-block">Save</button>
                    </th>
                </tr>
            </thead>
            <tfoot class="bg-secondary text-white">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tfoot>
        </table>
       </div>
    </form>

    <hr>
    <h6 class="ml-3">View Payment Details (Split payment) - <span class="selectedInvoicePaymentReference"></span></h6>
   <form id="invoicePaymentDetailForm" action="{{ route('authenticate.payment.invoice.detail.store') }}" autocomplete="off">@csrf
    <div class="table-responsive">
        <table id="invoicePaymentDetailTable" 
                class="table adjust table-bordered table-sm dt-responsive nowrap adjust" 
                style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px"
                data-url="{{ route('authenticate.payment.invoice.detail.list',['ip']) }}">
            <thead>
                <tr>
                    <td>Exhange Date</td>
                    <td>Dollar (USD)</td>
                    <td>Exhange Rate</td>
                    <td>Amount (PHP)</td>
                    <td width="15%">Percent (%)</td>
                    <td width="8%">Action</td>
                </tr>
                <tr>
                    <td><input type="text" name="exchangeDate" class="form-control form-control-sm datepciker" required></td>
                    <td><input type="text" name="dollar" class="form-control form-control-sm amount-class" required></td>
                    <td><input type="text" name="exchangeRate" class="form-control form-control-sm amount-class" required></td>
                    <td><input type="text" name="totalAmountInPHP" class="form-control form-control-sm amount-class" readonly></td>
                    <td><input type="text" name="totalPercentPayment" class="form-control form-control-sm" readonly></td>
                    <td>
                        <input  type="hidden" name="invoice_amountUSD" required>
                        <input  type="hidden" name="invoice_payment" required>
                        <button type="submit" class="btn btn-sm btn-outline-primary btn-block m-0">Save</button>
                    </td>
                </tr>
            </thead>
            <tfoot class="bg-secondary text-white">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tfoot>
        </table>
    </div>
   </form>
</div>
</x-component-b4.modal>