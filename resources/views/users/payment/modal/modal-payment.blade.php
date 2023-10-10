<x-component-b4.modal
identify='modalPaymentDetail'
title='Initial Payment Details'
size='lg'
save='0'
close='1'
>
<div class="row mb-3">
    <div class="col">
        <dl class="row mx-2 mb-0">
            <dt class="col-6">Supplier</dt>
            <dd class="col-6">: <span class="suppliername"></span></dd>
            <dt class="col-6">Total Metric Ton</dt>
            <dd class="col-6">: <span class="totalMetricTon"></span> (Price MT: <span class="priceMetricTon"></span>)</dd>
            <dt class="col-6">Initial Payment</dt>
            <dd class="col-6">: <span class="initialContractPayment"></span>&nbsp;(<span class="initialContractPercent"></span> %)</dd>
        </dl>
    </div>
    <div class="col">
        <dl class="row mx-2 mb-0">
            <dt class="col-5">Reference</dt>
            <dd class="col-7">: <span class="reference"></span></dd>
            <dt class="col-5">Total US Dollar</dt>
            <dd class="col-7">: <span class="totalAmountUSD"></span></dd>

        </dl>
    </div>
</div>
<form id="paymentDetailForm" action="{{ route('authenticate.payment.detail.store') }}" autocomplete="off">@csrf
    <input type="hidden" name="id">
    <table id="paymentDetailTable" 
           class="table adjust table-bordered table-sm dt-responsive nowrap adjust" 
           style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px"
           data-url="{{ route('authenticate.payment.detail.list',['cp']) }}">
        <thead>
            <tr>
                <td>Exhange Date</td>
                <td>Dollar (USD)</td>
                <td>Exhange Rate</td>
                <td>Amount (PHP)</td>
                <td width="8%">Percent (%)</td>
                <td width="8%">Action</td>
            </tr>
            <tr>
                <td><input type="text" name="exchangeDate" class="form-control form-control-sm datepciker" required></td>
                <td><input type="text" name="dollar" class="form-control form-control-sm amount-class" required></td>
                <td><input type="text" name="exchangeRate" class="form-control form-control-sm amount-class" required></td>
                <td><input type="text" name="totalAmountInPHP" class="form-control form-control-sm amount-class" readonly></td>
                <td><input type="text" name="totalPercentPayment" class="form-control form-control-sm" readonly></td>
                <td>
                    <input  type="hidden" name="contract_payment" required>
                    <button type="submit" class="btn btn-sm btn-outline-primary btn-block m-0">Save</button>
                    <button type="button" name="cancelpaymentDetail" class="btn btn-sm btn-outline-warning btn-block m-0 mt-1">Cancel</button>
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
</form>
</x-component-b4.modal>