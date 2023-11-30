@extends('../_layout/app')
@section('moreCss')
   <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-popover/jquery.webui-popover.min.css') }}" rel="stylesheet" />
    <style>
        .highlight-yellow td {
            background-color: #ffeed9 !important;
        }
        span.select2 {
            display         : table;
            table-layout    : fixed;
            width           : 100% !important;
        }
    </style>
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
<form id="contractForm" action="{{ route('authenticate.payment.store') }}" method="POST" autocomplete="off">@csrf
    <input type="hidden" class="form-control" name="id">
    <div class="card border mb-2">
        <div class="card-body py-2 ">
        <div class="row">
            <div class="col-4">
                <div class="form-group mb-1">
                    <small for="">Supplier</small>
                    <select id="{{ route("authenticate.report.filter.supplier") }}" name="suppliername" class="form-control"></select>
                </div>
                <div class="form-group mb-1">
                    <small for="">Contract Number</small>
                    <input type="text" class="needFormat form-control form-control-sm" required name="reference" maxlength="100">
                </div>
                <div class="form-group mb-1">
                    <small for="">Item Description</small>
                    <select id="{{ route("authenticate.report.filter.description") }}" class="form-control form-control-sm" required name="description"></select>
                </div>
            </div>
            <div class="col-4">
                    <div class="form-group mb-1">
                        <small for="">Total Metric Ton</small>
                        <input type="text" class="needFormat form-control form-control-sm amount-class" required name="metricTon" maxlength="15">
                    </div>
                    <div class="form-group mb-0">
                        <small for="">Total Price MT (USD)</small>
                        <input type="text" class="needFormat form-control form-control-sm amount-class" name="priceMetricTon" maxlength="15">
                    </div>
                    <div class="form-group mb-1">
                        <small for="">&nbsp;</small>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroup-sizing-sm" style="font-size:10px">Total Amount (USD)</span>
                            </div>
                            <input type="text" class="form-control form-control-sm amount-class" required name="amountUSD" maxlength="20" readonly>
                        </div>
                    </div>
            </div>
            <div class="col-4">
                <div class="card border mb-1 mt-2">
                    <div class="card-header p-0 pl-2 font-bold"><small style="font-size:10px">ADVANCE PAYMENT</small></div>
                    <div class="card-body p-2 pt-0">
                        <div class="form-row pt-0">
                            <div class="form-group col-12 mt-0 mb-1">
                                <small for="">Intial Amount (USD)</small>
                                <input type="text" class="needFormat form-control form-control-sm amount-class" required name="paidAmountUSD" maxlength="20">
                            </div>
                            <div class="form-group col-12 mb-1">
                                <small for="">Percent</small>
                                <input type="text" class="form-control form-control-sm" required name="contract_percent" maxlength="3">
                            </div>
                        </div>
                    </div>
                   </div>
            </div>
        </div>
    </div>
    <div class="card-footer p-1 ">
        <span class="float-right">
            <button type="submit" class="btn btn-sm btn-primary ">Submit</button>
            <button type="button" class="btn btn-sm btn-warning " onclick="location.reload();">Cancel</button>
        </span>
    </div>
   </div>
</form>
<div class="card">
    <div class="card-body p-3">
        <div class="mt-1">
            <div class="row">
                <div class="col-12">
                <div class="table-responsive">
                    <table id="contractTable" 
                    data-url="{{ route('authenticate.payment.list') }}"
                    data-remove="{{ route('authenticate.payment.delete',['cp']) }}"
                    class="table adjust table-bordered table-sm dt-responsive nowrap adjust" 
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
                    <thead>
                        <tr>
                            <th width="3%"></th>
                            <th class="text-center" width="5%">Action</th>
                            <th width="20%">Supplier</th>
                            <th width="20%">Item Description</th>
                            <th>Contract Ref.</th>
                            <th>Total MT Contract</th>
                            <th>Price (USD)</th>
                            <th>Adv.Payment</th>
                            <th class="text-center">Initial Payment<br><small>(Percentage)</small></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
                </div>
                </div>
               
            </div>
        </div>
    </div>
</div>

@include('users.payment.modal.modal-payment')
@include('users.payment.modal.modal-invoice-payment',['particular'=>$particular])
@include('users.payment.modal.modal-invoice')
@endsection
@section('moreJs')
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-popover/jquery.webui-popover.min.js') }}"></script>
    <script src="{{ asset('assets/js/payment/contract.js') }}"></script>
    <script src="{{ asset('assets/js/payment/payment.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/payment/freight.js') }}"></script> --}}
    <script src="{{ asset('assets/js/payment/other.js') }}"></script>
@endsection