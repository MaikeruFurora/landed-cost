@extends('../_layout/app')
@section('moreCss')
        <!-- DataTables -->
        <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    
        <!-- Responsive datatable examples -->
        <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Summernote css -->
        <link href="{{ asset('plugins/summernote/summernote-bs4.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<style>
    .summernote-content p {
        display: inline; 
        margin: 0; 
    }
    .my-input, .my-card-header, .form-check-label{
        font-size: 10px;
    }

    .my-input, .my-card-header, .form-check-label{
        font-size: 10px;
    }
    .nav-item{
        cursor: pointer;
    }
</style>
<x-page-title title="FUND">

   {{-- <form id="reportForm">@csrf
        <div class="input-group input-daterange input-group-sm" id="date-range">
            <input type="text" class="form-control" name="dateTo" placeholder="Date From" required>
            <input type="text" class="form-control" name="dateFrom" placeholder="Date To" required>
            <div class="input-group-append">
            <button  class="btn btn-primary" type="submit" id="button-addon2" style="font-size: 12px">Report</button>
            </div>
        </div>
   </form> --}}
</x-page-title>
<!-- Button trigger modal -->
{{-- <div class="card" oncopy="return false" oncut="return false" onpaste="return false"> --}}
    <div class="card">
    <div class="card-body">
       <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item " role="presentation">
              <a class="nav-link active" id="account-tab" data-toggle="tab" data-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true"><i class="fas fa-file-invoice"></i> Account</a>
            </li>
            <li class="nav-item " role="presentation">
              <a class="nav-link" id="posted-tab" data-toggle="tab" data-target="#posted" type="button" role="tab" aria-controls="posted" aria-selected="false"><i class="fas fa-list-ul"></i> POSTED (ToF / AoD)</a>
            </li>
            <li class="nav-item " role="presentation">
                <a class="nav-link" id="draft-tab" data-toggle="tab" data-target="#draft" type="button" role="tab" aria-controls="draft" aria-selected="false"><i class="fas fa-list-ul"></i> DRAFT (ToF / AoD)</a>
              </li>
            <li class="nav-item " role="presentation">
                <a class="nav-link" id="config-tab" data-toggle="tab" data-target="#config" type="button" role="tab" aria-controls="config" aria-selected="false"><i class="fas fa-list-ul"></i> Record for Telegraphic</a>
              </li>
          </ul>
         
          <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                @if (Helper::usrChckCntrl(['DB002']))
                <div class="table-responsive mt-4">
                {{-- <button class="btn btn-sm btn-primary float-left my-2" style="font-size: 10px">Refresh</button> --}}
                <table id="companybankTable" class="table table-sm table-bordered text-center" style="font-size: 10px" width="100%">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>Company</td>
                            {{-- <td>Bank</td> --}}
                            <td>Account No.</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                </table>
               </div>
               @else
                   <h6 class="p-3"><em>Access Denied</em></h6>
               @endif
            </div>
            <div class="tab-pane fade" id="draft" role="tabpanel" aria-labelledby="draft-tab">
                @if (Helper::usrChckCntrl(['DB003']))
                <div class="table-responsive mt-4">
                    <table id="bankHistoryTableDraft" class="table table-sm table-bordered text-center" style="font-size: 11px" width="100%">
                        <thead class="bg-secondary text-white">
                        <tr>
                            <td rowspan="2" width="5%">REFERENCE</td>
                            <td rowspan="2" width="15%">TYPES</td>
                            <td rowspan="2" width="15%">AMOUNT</td>
                            <td colspan="2" width="55%">TRANSFER</td>
                            <td rowspan="2" width="10%">OPTIONS</td>
                        </tr>
                        <tr>
                            <td width="50%">FROM</td>
                            <td width="50%">TO</td>
                        </tr>
                        </thead>
                    </table>
                </div>
                @else
                    <h6 class="p-3"><em>Access Denied</em></h6>
                @endif
            </div>
            <div class="tab-pane fade" id="posted" role="tabpanel" aria-labelledby="posted-tab">
                @if (Helper::usrChckCntrl(['DB003']))
                <div class="table-responsive mt-4">
                    <table id="bankHistoryTable" class="table table-sm table-bordered text-center" style="font-size: 11px" width="100%">
                        <thead class="bg-secondary text-white">
                        <tr>
                            <td rowspan="2" width="5%">REFERENCE</td>
                            <td rowspan="2" width="15%">TYPES</td>
                            <td rowspan="2" width="15%">AMOUNT</td>
                            <td colspan="2" width="55%">TRANSFER</td>
                            <td rowspan="2" width="10%">OPTIONS</td>
                        </tr>
                        <tr>
                            <td width="50%">FROM</td>
                            <td width="50%">TO</td>
                        </tr>
                        </thead>
                    </table>
                </div>
                @else
                    <h6 class="p-3"><em>Access Denied</em></h6>
                @endif
            </div>
            <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="config-tab">
                @if (Helper::usrChckCntrl(['DB004']))
                <div class="table-responsive mt-4">
                    <table id="telegraphicHistoryTable" class="table table-sm table-bordered dt-responsive display nowrap" cellspacing="0" style="font-size: 10px"  width="100%">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <td>Transaction No</td>
                                <td>Value Date</td>
                                <td>Amount and Currency</td>
                                <td>Application Name</td>
                                <td>Beneficiary Bank</td>
                                <td>Name</td>
                                <td>Beneficiary Account No</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                    </table>
                </div>
                @else
                    <h6 class="p-3"><em>Access Denied</em></h6>
                @endif
            </div>
          </div>
        
    </div>
</div>
<!-- Modal -->
<x-dollarbook.dollar-book-report/>
<x-dollarbook.bank-form/>
@endsection
@section('moreJs')
     <!-- Required datatable js -->
     <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
     <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
     <!-- Responsive examples -->
     <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
     <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <!--Summernote js-->
    {{--  --}}

    <script src="{{ asset('plugins/typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('assets/js/dollarbook/dollarbook.js') }}"></script>
    <script src="{{ asset('assets/js/dollarbook/bankhistory.js') }}"></script>
    <script src="{{ asset('assets/js/dollarbook/telegraphic.js') }}"></script>
@endsection