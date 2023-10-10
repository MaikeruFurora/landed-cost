@extends('../_layout/app')
@section('moreCss')
   <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    
@endsection
@section('content')
<!-- Page-Title -->
<x-page-title title="Payment">
    <a class="btn btn-primary btn-sm" href="{{ route('authenticate.details') }}">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</x-page-title>
<!-- end page title end breadcrumb -->
<!-- Alert Start -->
@if (session()->has('msg'))
    <div class="alert alert-{{ session()->get('action') ?? 'success' }} text-dark" role="alert">
        @if(session()->has('icon'))
            {{ session()->get('icon') }}
        @else
            <i class="far fa-check-circle"></i>
        @endif
        {{ session()->get('msg') }}
    </div>
@endif
<!-- Aler End -->
<div class="card">
    <div class="card-body p-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="contract" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Contract</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="freight" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Freight</button>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="contract">
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-8">
                                <table id="contractTable" 
                                data-url="{{ route('authenticate.payment.list') }}"
                                class="table table-bordered table-hover" 
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th width="20%">Supplier</th>
                                        <th width="20%">Item Description</th>
                                        <th>Contract Reference</th>
                                        <th>Total MT Contract</th>
                                        <th>Price MT(USD)</th>
                                        <th class="text-center">Initial Payment (Percentage)</th>
                                        <th class="text-center" width="10%">Action</th>
                                    </tr>
                                </thead>
                            </table>
                            </div>
                            <div class="col-4">
                                <form id="contractForm" action="{{ route('authenticate.payment.store') }}" method="POST" autocomplete="off">@csrf
                                        <input type="hidden" class="form-control" name="id">
                                        <div class="card border mb-2">
                                            <div class="card-body p-3">
                                            <div class="form-row">
                                                 <div class="form-group col-6">
                                                     <small for="">Supplier</small>
                                                     <input type="text" class="form-control form-control-sm" required name="suppliername" maxlength="150" autofocus>
                                                 </div>
                                                 <div class="form-group col-6">
                                                     <small for="">Contract Number</small>
                                                     <input type="text" class="needFormat form-control form-control-sm" required name="reference" maxlength="100">
                                                 </div>
                                            </div>
                                            <div class="form-group">
                                                <small for="">Item Description</small>
                                                <input type="text" class="needFormat form-control form-control-sm" required name="description">
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-6 mb-2">
                                                    <small for="">Total Metric Ton</small>
                                                    <input type="text" class="needFormat form-control form-control-sm amount-class" required name="metricTon" maxlength="15">
                                                </div>
                                                <div class="form-group col-6 mb-2">
                                                    <small for="">Total Price MT (USD)</small>
                                                    <input type="text" class="needFormat form-control form-control-sm amount-class" required name="priceMetricTon" maxlength="15">
                                                </div>
                                            </div>
                                            <div class="input-group input-group-sm mb-0">
                                                <div class="input-group-prepend">
                                                  <span class="input-group-text" id="inputGroup-sizing-sm" style="font-size:10px">Total Amount (USD)</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm amount-class" required name="amountUSD" maxlength="20" readonly>
                                            </div>
                                        </div>
                                       </div>
                                       <div class="card border mb-1">
                                        <div class="card-header p-0 pl-2 font-bold"><small style="font-size:10px">ADVANCE PAYMENT</small></div>
                                        <div class="card-body p-3">
                                            <div class="form-row">
                                                <div class="form-group col-6 mb-1">
                                                    <small for="">Intial Amount (USD)</small>
                                                    <input type="text" class="needFormat form-control form-control-sm amount-class" required name="paidAmountUSD" maxlength="20">
                                                </div>
                                                <div class="form-group col-6 mb-1">
                                                    <small for="">Percent</small>
                                                    <input type="text" class="form-control form-control-sm" required name="contract_percent" maxlength="3">
                                                </div>
                                            </div>
                                        </div>
                                       </div>
                                        <button type="submit" class="btn btn-sm btn-primary btn-block">Submit</button>
                                        <button type="button" class="btn btn-sm btn-warning btn-block">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="freight">
                    <div class="mt-4">
                        <button class="btn btn-outline-primary btn-sm float-right mr-3" name="freightBtn"><i class="fas fa-plus-circle"></i> Create</button>
                        <table class="table table-sm table-bordered adjust" id="freightTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sample</th>
                                    <th>Sample</th>
                                    <th>Sample</th>
                                    <th>Sample</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
          </div>
    </div>
</div>
@include('users.payment.modal.modal-payment')
@include('users.payment.modal.modal-invoice')
@include('users.payment.modal.modal-freight')
@endsection
@section('moreJs')
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
    <script src="{{ asset('assets/js/payment/contract.js') }}"></script>
    <script src="{{ asset('assets/js/payment/tagging-invoice.js') }}"></script>
    <script src="{{ asset('assets/js/payment/invoice-payment-header.js') }}"></script>
@endsection