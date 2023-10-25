<x-component-b4.modal
    identify='modalPayment'
    title='Invoice Search'
    center=1
    size='md'
    save='0'
    close='0'
>
    {{-- <x-payment.search-invoice
        action="{{ route('authenticate.payment.invoice.search') }}"
        url="{{ route('authenticate.payment.invoice.save') }}"
        id='seachInvoiceUnderPaymentForm'
    /> --}}

    <div class="card m-0 mb-2 border">
        <div class="card-header p-1">
            <form id="searchInvoiceForm" action="{{ route('authenticate.payment.invoice.search') }}" autocomplete="off">@csrf
                <div class="input-group input-group-sm">
                    <input type="hidden" name="control">
                    <input type="search" name="search" class="form-control" placeholder="Search Invoice No." required>
                    <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body px-0 mt-0">
            <table
                id="searchInvoiceTable" 
                {{-- data-url="{{ route('authenticate.payment.invoice.save') }}" --}}
                class="table adjust table-bordered table-sm dt-responsive nowrap adjust table-striped" 
                style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:11px">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>MT</th>
                        <th>Description</th>
                        <th width="5%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="alert alert-warning text-dark">
        <p><i class="fas fa-exclamation-circle"></i> Once the invoice is tagged, you can't add a new payment to it. Please make sure all payment transactions are done.</p>
    </div>
</x-component-b4.modal>