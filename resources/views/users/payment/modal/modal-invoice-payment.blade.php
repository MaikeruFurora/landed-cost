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
    <div class="row mb-3">
        <div class="col">
            <dl class="row mx-2 mb-0">
                <dt class="col-6">Supplier</dt>
                <dd class="col-6">: <span class="suppliername"></span></dd>
                <dt class="col-6">Total Metric Ton</dt>
                <dd class="col-6">: <span class="totalMetricTon"></span></dd>
            </dl>
        </div>
        <div class="col">
            <dl class="row mx-2 mb-0">
                <dt class="col-5">Reference</dt>
                <dd class="col-7">: <span class="reference"></span></dd>
            </dl>
        </div>
    </div>
   
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
                             <th>Reference</th>
                             <th>Metric Ton</th>
                             <th>Price MT</th>
                             <th width="8%">Amount (USD)</th>
                             <th>Percent</th>
                             <th width="10%">Action</th>
                         </tr>
                         <tr>
                             <th><input type="text" tabindex="1" class="form-control form-control-sm" name="reference" required></th>
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
                         <td></td><td></td><td></td><td></td><td></td><td></td>
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
                <ul class="nav nav-tabs ml-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="nego-detail-tab" data-toggle="tab" data-target="#nego-detail" type="button" role="tab" aria-controls="nego-detail" aria-selected="true"><b><i class="fas fa-info-circle"></i> Nego Payment Detail</b></button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="other-detail-tab" data-toggle="tab" data-target="#other-detail" type="button" role="tab" aria-controls="other-detail" aria-selected="false"><b><i class="fas fa-info-circle"></i> Other Payment Detail</b></button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="nego-detail" role="tabpanel" aria-labelledby="nego-detail-tab">
                        <form id="invoicePaymentDetailForm" action="{{ route('authenticate.payment.invoice.detail.store') }}" autocomplete="off">@csrf
                            <div class="table-responsive">
                                <table id="invoicePaymentDetailTable" 
                                        class="table adjust table-bordered table-sm dt-responsive nowrap adjust table-hover" 
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px"
                                        data-url="{{ route('authenticate.payment.invoice.detail.list',['ip']) }}">
                                    <thead>
                                        <tr>
                                            <td>Exhange Date</td>
                                            <td>Dollar (USD)</td>
                                            <td>Exhange Rate</td>
                                            <td>Amount (PHP)</td>
                                            <td width="15%">Percent (%) <small class="text-danger"><b>* CHECK as PARTIAL</b></small></td>
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
                                                     <div class="input-group-text py-0 px-2">
                                                       <input type="checkbox" class="form-control form-control-sm" style="margin-bottom: -2px" name="partial">
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
                    <div class="tab-pane fade" id="other-detail" role="tabpanel" aria-labelledby="other-detail-tab">
                        <form id="otherPaymentForm" action="{{ route('authenticate.payment.invoice.other.store') }}" autocomplete="off">@csrf
                            <div class="table-responsive">
                                <table id="otherPaymentTable" 
                                        class="table adjust table-bordered table-sm dt-responsive nowrap adjust table-hover" 
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px"
                                        data-url="{{ route('authenticate.payment.invoice.other.list',['ip']) }}">
                                    <thead>
                                        <tr>
                                            <td width="15%">Category</td>
                                            <td width="15%">Date</td>
                                            <td>Dollar (USD)</td>
                                            <td>Exhange Rate</td>
                                            <td>FCL/MT</td>
                                            <td>Amount (PHP)</td>
                                            <td width="8%">Action</td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <select name="particular" id="" class="form-control form-control-sm" required>
                                                    <option value="">Select Type</option>
                                                    @foreach ($particular as $item)
                                                        <option value="{{ $item->p_code }}">{{ $item->p_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" tabindex="1" name="exchangeDate" class="form-control form-control-sm datepciker" required></td>
                                            <td><input type="text" tabindex="1" name="dollar" class="form-control form-control-sm amount-class" required></td>
                                            <td><input type="text" tabindex="1" name="exchangeRate" class="form-control form-control-sm amount-class" required></td>
                                            <td><input type="text" tabindex="1" name="quantity" class="form-control form-control-sm amount-class" required></td>
                                            <td><input type="text" tabindex="1" name="totalAmountInPHP" class="form-control form-control-sm amount-class" readonly></td>
                                                <td>
                                                    <input  type="hidden" name="id">
                                                    <input  type="hidden" name="invoice_payment" required>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary btn-block m-0">Save</button>
                                                    <button type="button" name="cancelButton" class="btn btn-sm btn-outline-warning btn-block m-0 mt-1">Cancel</button>
                                                </td>
                                        </tr>
                                    </thead>
                                    
                                </table>
                            </div>
                           </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-component-b4.modal>