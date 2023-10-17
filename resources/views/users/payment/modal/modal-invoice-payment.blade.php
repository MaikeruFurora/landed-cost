<x-component-b4.modal
    identify='modalInvoicePayment'
    title='Payment Details'
    center=1
    size='xl'
    save='0'
    close='1'
>
    {{-- <x-payment.search-invoice
        action="{{ route('authenticate.payment.invoice.search') }}"
        url="{{ route('authenticate.payment.invoice.save') }}"
        id='seachInvoiceUnderPaymentForm'
    /> --}}
    <div class="card m-0 mb-2 border">
        <div class="card-header">Payment</div>
        <div class="card-body px-0">
            <form action="{{ route('authenticate.payment.invoice.store') }}" id="shipmentPaymentForm" autocomplete="off">@csrf
                <div class="table-responsive">
                 <table
                     data-url="{{ route('authenticate.payment.invoice.list',['cp']) }}"
                     class="table adjust table-bordered table-sm dt-responsive nowrap adjust"
                     style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px"
                     id="shipContractTable"
                     data-invoice="{{ route('authenticate.payment.invoice.save') }}"
                     data-remove="{{ route('authenticate.payment.invoice.remove',['ip']) }}"
                     data-cost="{{ route('authenticate.details.landedcost',['invoice']) }}">
                     <thead>
                         <tr>
                             {{-- <th>Reference</th> --}}
                             <th>Metric Ton</th>
                             <th>Price MT</th>
                             <th width="8%">Amount (USD)</th>
                             <th>Percent</th>
                             <td width="10%">Action</td>
                         </tr>
                         <tr>
                             {{-- <th><input type="text" tabindex="1" class="form-control form-control-sm" name="reference" required></th> --}}
                             <th><input type="text" tabindex="1" class="form-control form-control-sm amount-class" name="metricTon" required></th>
                             <th><input type="text" tabindex="1" class="form-control form-control-sm amount-class" name="priceMetricTon" required></th>
                             <th colspan="2"><input type="text" class="form-control form-control-sm amount-class" name="amountUSD" required readonly></th>
                             <th>
                                 <input  type="hidden" name="contract_payment" required>
                                 <button type="submit" class="btn btn-sm btn-primary btn-block">Save</button>
                             </th>
                         </tr>
                     </thead>
                     <tfoot class="bg-secondary text-white">
                         {{-- <td></td> --}}
                         <td></td><td></td><td></td><td></td><td></td>
                     </tfoot>
                 </table>
                </div>
             </form>
        </div>
   </div>
    <div class="m-0 mt-2 applyPayment">
        <div class="card m-0 border">
            <div class="card-header pb-1">
                <div class="row justify-content-between">
                    <div class="col-8">
                        <span>View Payment Details (Split payment) - <span class="selectedInvoicePaymentReference"></span></span>
                    </div>
                    <div class="col-1">
                        <i style="font-size: 15px" class="float-right fas fas fa-times-circle text-danger applyPaymentAction"></i>
                    </div>
                  </div>
            </div>
            <div class="card-body px-0">
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
                                 <td></td>
                             </tr>
                             <tr>
                                 <td><input type="text" tabindex="1" name="exchangeDate" class="form-control form-control-sm datepciker" required></td>
                                 <td><input type="text" tabindex="1" name="dollar" class="form-control form-control-sm amount-class" required></td>
                                 <td><input type="text" tabindex="1" name="exchangeRate" class="form-control form-control-sm amount-class" required></td>
                                 <td><input type="text" tabindex="1" name="totalAmountInPHP" class="form-control form-control-sm amount-class" readonly></td>
                                 <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text"  tabindex="1" name="totalPercentPayment" class="form-control form-control-sm" readonly>
                                        <div class="input-group-prepend">
                                          <div class="input-group-text">
                                            <input type="checkbox" name="partial">
                                          </div>
                                        </div>
                                      </div>
                                </td>
                                 <td>
                                     <input  type="hidden" name="invoice_amountUSD" required>
                                     <input  type="hidden" name="invoice_payment" required>
                                     <input  type="hidden" name="id">
                                     <button type="submit" class="btn btn-sm btn-outline-primary btn-block m-0">Save</button>
                                     <button type="button" name="cancelButton" class="btn btn-sm btn-outline-warning btn-block m-0 mt-1">Cancel</button>
                                 </td>
                                 <td></td>
                             </tr>
                         </thead>
                         <tfoot class="bg-secondary text-white">
                             <td></td>
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
        </div>
    </div>

</x-component-b4.modal>