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
    <x-page-title title="Invoice">

        @if (Helper::usrChckCntrl(['LC002']))
            <button type="button" class="btn btn-sm btn-success pl-2 pr-2 mr-2">
                <i class="far fa-check-circle mr-1" style="font-size: 12px;"></i>Post
            </button>
            
            <a class="btn btn-primary btn-sm" href="{{ route('authenticate.po.search') }}">
                <i class="fas fa-plus"></i>&nbsp;&nbsp;Gather Data (<b>SAP</b>)
            </a>
        @endif
        <button class="btn btn-sm btn-secondary" name="refreshTable"><i class="fas fa-sync-alt"></i> Refresh</button>
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

    <!-- Aler End -->
   <div class="card">
        <div class="card-body">
            
            <div class="table-responsive mt-2">
            <table cellpadding="0" cellspacing="0" id="datatable" class="table table-bordered table-hover dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
               
               <thead class="bg-secondary text-white">
                   <tr>
                       <th>&nbsp;</th>
                       <th>ID(s)</th>
                       <th>PO No.</th>
                       <!-- <th>Item Code.</th> -->
                       <th>Vessel</th>
                       <th>Description</th>
                       <th>Inv No.</th>
                       <th>Broker</th>
                       <th>Qty</th>
                       <th>Qty(KLS)</th>
                       <th>Qty(MT)</th>
                       <th>FCL</th>
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody>
                  
               </tbody>
              
           </table>
            </div>
        </div>
   </div>
    <div class="modal fade" id="propMessage" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="propMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <p class="modal-title" id="propMessageLabel"></p>
                </div>
                <div class="modal-body text-center">
                   <span id="promptText"></span>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary pl-3 pr-3">Post</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('moreJs')
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/details.js') }}"></script>
@endsection
