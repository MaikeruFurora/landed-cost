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
                    <form id="contractForm" action="{{ route('authenticate.payment.store') }}" method="POST" autocomplete="off">@csrf
                    <table id="contractTable" 
                        data-url="{{ route('authenticate.payment.list') }}"
                        class="table table-bordered table-hover dt-responsive nowrap adjust" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:12px">
                       <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Reference</th>
                                <th>Total MT</th>
                                <th>Price MT (USD)</th>
                                <th>Total Price MT (USD)</th>
                                <th width="8%" class="text-ceter">Action</th>
                            </tr>
                                <tr>
                                    <td><input type="text" name="suppliername" class="form-control form-control-sm " id="" required></td>
                                    <td><input type="text" name="reference" class="form-control form-control-sm" id=""></td>
                                    <td><input type="text" name="totalmt" class="form-control form-control-sm amount-class" id="" required></td>
                                    <td><input type="text" name="mtprice" class="form-control form-control-sm amount-class" id=""></td>
                                    <td><input type="text" name="totalprice" class="form-control form-control-sm amount-class" readonly></td>
                                    <td><button type="submit" class="btn btn-outline-primary btn-sm btn-block"><i class="fas fa-plus-circle"></i> Save</button></td>
                                </tr>
                            </thead>
                        </form>
                        </table>
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
@endsection