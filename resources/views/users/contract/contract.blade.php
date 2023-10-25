@extends('../_layout/app')
@section('moreCss')
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert -->
    <link href="{{ asset('plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet" type="text/css">
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        label{
            font-size: 11px
        }
    </style>
@endsection
@section('content')
<!-- Page-Title -->
    <x-page-title title="Advance Payment">
        <a class="btn btn-primary btn-sm" href="{{ url()->previous() }}">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </x-page-title>
<!-- end page title end breadcrumb -->
<!-- Alert Start -->
@if (session()->has('msg'))
    <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
        @if(session()->has('icon'))
            {{ session()->get('icon') }}
        @else
            <i class="far fa-check-circle"></i>
        @endif
        {{ session()->get('msg') }}
    </div>
@endif
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-2">
                <table cellpadding="0" cellspacing="0" id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:11px">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th class="text-center" rowspan="2">&nbsp;</th>
                            <th class="text-center" rowspan="2">Option</th>
                            <th class="text-center" rowspan="2">&nbsp;</th>
                            <th class="text-center" rowspan="2">&nbsp;</th>
                            <th colspan="4" style="font-size:9px" class="text-center">CONTRACT DETAILS</th>
                            <th colspan="2" style="font-size:9px" class="text-center">PAYMENT</th>
                            <th class="text-center border" rowspan="2" >Dollar<br>Rate</th>
                            <th class="text-center" rowspan="2">Date</th>
                            <th rowspan="2">Amount<br>(PHP)</th>
                            <th class="text-center" rowspan="2">Supplier Name</th>
                            <th class="text-center" rowspan="2">Item Name</th>
                            <th class="text-center" rowspan="2">Type</th>
                            <th class="text-center" rowspan="2">Invoice</th>
                        </tr>
                        <tr class="text-center">
                            <th>Contract<br>Number</th>
                            <th>Total<br>MT</th>
                            <th>Price<br>MT</th>
                            <th>Amount<br>(USD)</th>
                            <th>Paid<br>(USD)</th>
                            <th>Percent</th>
                           
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-header">
                CONTRACT INFO
            </div>
            <div class="card-body">
                <form method="POST" id="formContract"  autocomplete="off">@csrf
                    <input type="hidden" class="form-control" name="id">
                    <div class="form-group">
                        <label for="">Contract Number</label>
                        <input type="text" class="needFormat form-control form-control-sm" required name="contract_no" maxlength="35">
                    </div>
                    <div class="form-group">
                        <label for="">Item Description</label>
                        <select id="{{ route("authenticate.report.filter.description") }}" class="form-control form-control-sm" required name="description" maxlength="35"></select>
                    </div>
                    <div class="form-group">
                        <label for="">Supplier name</label>
                        <select id="{{ route("authenticate.report.filter.supplier") }}" class="form-control form-control-sm" required name="suppliername" maxlength="35"></select>
                    </div>
                   <div class="form-row">
                        <div class="form-group col-6">
                            <label for="">Type of payment</label>
                           <select class="form-control form-control-sm" name="type">
                                @foreach ($particulars as $item)
                                    <option value="{{ $item->p_code }}">{{ strtoupper($item->p_name)  }}</option>
                                @endforeach
                           </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="">Invoice</label>
                            <input type="text" class="needFormat form-control form-control-sm" name="invoiceno" maxlength="35">
                        </div>
                   </div>
                   <div class="card border mb-2">
                    <div class="card-body p-3">
                        <div class="form-row">
                            <div class="form-group col-6 mb-2">
                                <label for="">Total Metric Ton</label>
                                <input type="text" class="needFormat form-control form-control-sm" required name="metricTon" maxlength="15">
                            </div>
                            <div class="form-group col-6 mb-2">
                                <label for="">Total Price MT (USD)</label>
                                <input type="text" class="needFormat form-control form-control-sm" required name="priceMetricTon" maxlength="15">
                            </div>
                        </div>
                        <div class="input-group input-group-sm mb-0">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroup-sizing-sm" style="font-size:10px">Total Amount (USD)</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" required name="amountUSD" maxlength="20" readonly>
                        </div>
                    </div>
                   </div>
                   <div class="card border mb-1">
                    <div class="card-header p-0 pl-2 font-bold"><small style="font-size:10px">ADVANCE PAYMENT</small></div>
                    <div class="card-body p-3">
                        <div class="form-row">
                            <div class="form-group col-6 mb-1">
                                <label for="">Paid Amount (USD)</label>
                                <input type="text" class="needFormat form-control form-control-sm" required name="paidAmountUSD" maxlength="20">
                            </div>
                            <div class="form-group col-6 mb-1">
                                <label for="">Percent</label>
                                <input type="text" class="form-control form-control-sm" required name="percentage" maxlength="3">
                            </div>
                        </div>
                    </div>
                   </div>
                   <div class="form-row">
                    <div class="form-group col-6 mb-1">
                        <label for="">Dollar Rate (PHP)</label>
                        <input type="text" class="needFormat form-control form-control-sm" required name="exchangeRate">
                    </div>
                    <div class="form-group col-6 mb-1">
                        <label for="">Dollar Rate (Date)</label>
                        <input type="text" class="needFormat form-control form-control-sm" required name="exchangeRateDate">
                    </div>
                   </div>
                   <small class="text-primary">Amount PHP = Paid Amount X Dollar Rate(PHP)</small>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroup-sizing-sm" style="font-size:10px">Amount (PHP)</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" name="amountPHP" readonly>
                    </div>
                    @if (Helper::usrChckCntrl(['AP002']))
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        <button type="button" class="btn btn-warning btn-block">Cancel</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    
     <!-- Modal -->
     <div class="modal fade" id="searchInvoiceModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="searchInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" id="searchInvoiceModalLabel">Title here</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <form id="formSearchInvoice">
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" autocomplete="off" name="search">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>
                    <input type="hidden" name="contract_id">
               </form>
                <table class="table table-bordered table-hover" style="font-size:10px">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Po Number</th>
                            <th>Description</th>
                            <th>Inv No.</th>
                            <th>Qty(MT)</th>
                            <th>FCL</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="showData">
                        <tr class="header text-center">
                            <td colspan="11">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    <!-- modal-end -->

</div><!-- row -->
@endsection
@section('moreJs')
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
    <script src="{{ asset('plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/contract.js') }}"></script>t
@endsection