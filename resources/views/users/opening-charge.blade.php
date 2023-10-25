@extends('../_layout/app')
@section('moreCss') 
     <!-- DataTables -->
     <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
     <!-- Sweet Alert -->
     <link href="{{ asset('plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet" type="text/css">
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!-- Page-Title -->
    <x-page-title title="L/C Opening Charge">
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
    <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-2">
                <table cellpadding="0" cellspacing="0" id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:11px">
                <thead class="thead-dark">
                        <tr>
                            <th>ID(s)</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Metric Ton</th>
                            <th>Referece No</th>
                            <th>Invoice</th>
                            <th>Invoice MT</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card">
            <div class="card-header">
                LC Opening Charge
            </div>
            <div class="card-body">
                <form method="POST" id="formAmount" action="{{ route('authenticate.opening.store') }}" autocomplete="off">@csrf
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label for="">Total Amount</label>
                        <input type="text" class="needFormat form-control" id="" required name="amount">
                    </div>
                    <div class="form-group">
                        <label for="">Total Metric Ton</label>
                        <input type="text" class="needFormat form-control" id="" required name="mt">
                    </div>
                    <div class="form-group">
                        <label for="">Reference</label>
                        <input type="text" class="form-control" id="" required name="reference">
                    </div>
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="text" class="form-control" id="" required name="transaction_date">
                    </div>
                    @if (Helper::usrChckCntrl(['OA002']))
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        <button type="button" class="btnCancel btn btn-warning btn-block">Cancel</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    
    <!-- modal -->
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
                            <input type="hidden" name="id_lcopc">
                            {{-- <select name="whse" class="custom-select">
                                <option value="manila">Manila</option>
                                <option value="province">Province</option>
                            </select> --}}
                            <button class="btn btn-secondary" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>
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
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
    <script src="{{ asset('plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/opening-charge.js') }}"></script>t
@endsection