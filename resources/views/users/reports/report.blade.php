@extends('../_layout/app')
@section('moreCss')
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
<!-- DataTables -->
<link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<style>label{font-size: 11px;}.datepicker.dropdown-menu {z-index: 9999 !important;}</style>
@endsection
@section('content')
<!-- Page-Title -->
    <x-page-title title="REPORT">
        @if (Helper::usrChckCntrl(['RP003']) || auth()->user()->type)
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#dutiesModal">
            <i class="fas fa-download mr-1"></i> Report
        </button>
        @endif
    </x-page-title>
<!-- end page title end breadcrumb -->
{{-- <div class="card">
    <div class="card-body">
        <div class="row justify-content-between mb-1">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h6>ITEM(s)</h6>
                <button class="btn btn-outline-success btn-sm" id="print">Print&nbsp;<i class="fas fa-print"></i></button>
            </div>
            @if (Helper::usrChckCntrl(['RP002']))
                <div class="col-lg-4 col-md-4 col-sm-12">
                        <!-- action="{{ route('authenticate.report') }}" -->
                    <form id="searchForm" method="GET" autocomplete="off">@csrf
                        <select name="item" class="form-control"></select>
                        <!-- <input type="text" class="form-control" name="item" placeholder="ITEM" /> -->
                        <div class="input-group mb-3 mt-2">
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control form-control-sm" name="start" placeholder="Start Date" required/>
                                <input type="text" class="form-control form-control-sm" name="end" placeholder="End Date"  read_exif_data/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 11px;">
                <tr>
                    <td>PO DATE</td>
                    <td>Invoice</td>
                    <td>Qty Metric Ton</td>
                    <td>Price MT</td>
                    <td>Avg Exchange Rate</td>
                </tr>
               
                <tbody class="showData">
                    <tr>
                        <td colspan="5" class="text-center">No data available</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> --}}

<div class="card">
    <div class="card-header">
        Report
    </div>
    <div class="card-body">
        @if (Helper::usrChckCntrl(['RP002']))
                <div class="col-lg-4 col-md-4 col-sm-12 ">
                        <!-- action="{{ route('authenticate.report') }}" -->
                    <form id="searchForm" action="{{ route("authenticate.report.filter") }}" autocomplete="off"
                    data-cost="{{ route('authenticate.details.landedcost',['invoice']) }}">@csrf
                        <div class="form-group mb-2">
                            <select id="{{ route("authenticate.report.filter.description") }}" name="item" class="form-control"></select>
                        </div>
                        <div class="form-group mb-1">
                            <select id="{{ route("authenticate.report.filter.supplier") }}" name="supplier" class="form-control"></select>
                        </div>
                        <div class="input-group mb-3 mt-2">
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control form-control-sm" name="start" placeholder="Start Date" required/>
                                <input type="text" class="form-control form-control-sm" name="end" placeholder="End Date"  read_exif_data/>
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
        @endif
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-all-tab" data-toggle="tab" data-target="#nav-all" type="button" role="tab" aria-controls="nav-all" aria-selected="false">All</button>
              <button class="nav-link" id="nav-nego-tab" data-toggle="tab" data-target="#nav-nego" type="button" role="tab" aria-controls="nav-nego" aria-selected="true">LCDP Nego</button>
              <button class="nav-link" id="nav-freight-tab" data-toggle="tab" data-target="#nav-freight" type="button" role="tab" aria-controls="nav-freight" aria-selected="false">Freight</button>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                <div class="mt-4">
                 <div class="table-responsive">
                     <table id="allDollarExpenes" class="table table-bordered table-striped table-hover" style="width: 100%;font-size:10px">
                         <thead>
                             <tr>
                                <th width="10%">PARTICULAR</th>
                                <th width="7%">DATE</th>
                                <th>ITEM</th>
                                <th>SUPPLIER</th>
                                <th  width="6%">MT(Qty/FCL)</th>
                                <th width="6%">MT(USD)</th>
                                <th width="6%">TOTAL(USD)</th>
                                <th width="6%">ExRate(PHP)</th>
                                <th width="10%">Total(PHP)</th>
                             </tr>
                         </thead>
                         <tbody>
                         </tbody>
                         <tfoot class="bg-secondary text-white">
                             <tr>
                                 <th colspan="4">TOTAL</th>
                                 <th>0</th>
                                 <th>0</th>
                                 <th>0</th>
                                 <th>0</th>
                                 <th>0</th>
                             </tr>
                         </tfoot>
                     </table>
                 </div>
                </div>
             </div>
            <div class="tab-pane fade" id="nav-nego" role="tabpanel" aria-labelledby="nav-nego-tab">
               <div class="mt-4">
                <div class="table-responsive">
                    <table id="negoTable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
                        <thead>
                            <tr>
                                <th width="7%">DATE</th>
                                <th>ITEM</th>
                                <th>SUPPLIER</th>
                                <th>MT(Qty)</th>
                                <th>MT(USD)</th>
                                <th>TOTAL(USD)</th>
                                <th>ExRate(PHP)</th>
                                <th>Total(PHP)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="bg-secondary text-white">
                            <tr>
                                <th colspan="3">TOTAL</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               </div>
            </div>
            <div class="tab-pane fade" id="nav-freight" role="tabpanel" aria-labelledby="nav-freight-tab">
               <div class="mt-4">
                <div class="table-responsive">
                    <table id="freightTable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
                        <thead>
                            <tr>
                                <th width="7%">DATE</th>
                                <th>ITEM</th>
                                <th>SUPPLIER</th>
                                <th>MT(Qty/FCL)</th>
                                <th>MT(USD)</th>
                                <th>TOTAL(USD)</th>
                                <th>ExRate(PHP)</th>
                                <th>Total(PHP)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="bg-secondary text-white">
                            <tr>
                                <th colspan="3">TOTAL</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               </div>
            </div>
          </div>
       
    </div>
</div>
<x-export.report :companies="$companies"/>
@endsection
@section('moreJs')
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.js') }}"></script>
<script src="{{ asset('assets/js/report.js') }}"></script>
@endsection