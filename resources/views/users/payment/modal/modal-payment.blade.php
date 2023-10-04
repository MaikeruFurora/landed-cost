<x-component-b4.modal
identify='modalPaymentDetail'
title='Payment Details'
size='lg'
save='0'
close='1'
>
<dl class="row mx-2 mb-0">
    <dt class="col-sm-3">Supplier</dt>
    <dd class="col-sm-9">: <span class="suppliername"></span></dd>
  
    <dt class="col-sm-3">Total Metric Ton</dt>
    <dd class="col-sm-9">: <span class="totalMetricTon"></span></dd>

    <dt class="col-sm-3">Reference</dt>
    <dd class="col-sm-9">: <span class="reference"></span></dd>
  </dl>
<form id="paymentDetailForm" action="{{ route('authenticate.payment.detail.store') }}" autocomplete="off">@csrf
    <input type="hidden" name="id">
    <table id="paymentDetailTable" 
           class="table adjust table-bordered table-sm dt-responsive nowrap adjust" 
           style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px"
           data-url="{{ route('authenticate.payment.detail.list',['cp']) }}">
        <thead>
            <tr>
                <td>Exhange Date</td>
                <td>Metric Ton</td>
                <td>Exhange Rate</td>
                <td width="8%">Action</td>
            </tr>
            <tr>
                <td><input type="text" name="exchangeDate" class="form-control form-control-sm datepciker" required></td>
                <td><input type="text" name="metricTon" class="form-control form-control-sm amount-class" required></td>
                <td><input type="text" name="exchangeRate" class="form-control form-control-sm amount-class" required></td>
                <td><input type="hidden" name="contract_payment" required><button class="btn btn-sm btn-outline-primary btn-block m-0">Save</button></td>
            </tr>
        </thead>
    </table>
</form>
</x-component-b4.modal>