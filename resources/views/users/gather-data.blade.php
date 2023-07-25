@extends('../_layout/app')
@section('moreCss')
    <style>
        tbody tr {
            display: none;
        }
        tbody tr.header {
            display: table-row;
        }
        .tbody.no-data {
            display: table-row;
        }
        .adjust tr td, .adjust tr th{
        padding: 4px 10px !important;
        margin: 0 !important;
        }
    </style>
@endsection
@section('content')
<!-- Page-Title -->
    <x-page-title title="Gather Data (sap)">
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
@error('invoiceno')
<div class="alert alert-danger text-dark" role="alert">
    {{ $errors->first('invoiceno') }}
</div>
@enderror
<div class="card">
        <div class="card-body">
            <div class="row justify-content-between mb-1">
                <div class="col-lg-4 col-md-4 col-sm-12">
                   <p>ITEM(s)</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <form id="searchForm" action="" method="GET" autocomplete="off">
                    <div class="input-group mb-3">
                        <input type="search" class="form-control form-control-sm" name="search" value="" autocomplete="off" required>
                        {{-- <select name="whse" class="custom-select custom-select-sm">
                            <option value="manila">Manila</option>
                            <option value="province">Province</option>
                        </select> --}}
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-sm" type="submit" id="button-addon2"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
            <table class="adjust table table-bordered table-hover" style="font-size: 10px;">
               
               <thead class="thead-dark adjust">
                   <tr>
                       <th>PO No.</th>
                       <th>Item Code.</th>
                       <th>Supplier</th>
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
                <tbody class="showData">
                    <tr class="header text-center">
                        <td colspan="12">No data available</td>
                    </tr>
                </tbody>
                <tfooter class="thead-dark">
                   <tr>
                       <th>PO No.</th>
                       <th>Item Code.</th>
                       <th>Supplier</th>
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
               </tfooter>
           </table>
            </div>
        </div>
</div>
@endsection
@section('moreJs')
<script src="{{ asset('assets/js/gather-data.js') }}"></script>
@endsection