@extends('../_layout/app')
@section('content')
    <!-- Page-Title -->
    <x-page-title title="Invoice >> {{ $openAmount->lc_reference }}">
        <a class="btn btn-primary btn-sm" href="{{ url()->previous() }}">
        <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back
        </a>
        <input type="hidden" name="open_amount_id" value="{{ $openAmount->id }}">
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
            <div class="row justify-content-between mb-1">
                <div class="col-lg-4 col-md-4 col-sm-12">
                   <small>REFERENCE: <b>{{ $openAmount->lc_reference }} </b></small> <br>
                   <small>AMOUNT: <b> {{ number_format($openAmount->lc_amount,2) }}</b></small>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <form id="searchForm" autocomplete="off">
                    <div class="input-group mb-3">
                        <input type="search" class="form-control form-control-sm" name="search" value="" autocomplete="off" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-sm" type="submit" id="button-addon2"><i class="fas fa-search"></i> Search</button>
                            <!-- <button class="btn btn-outline-warning btn-sm" type="button" name="abort"><i class="fas fa-times"></i> Abort</button> -->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 12px;">
               
               <thead class="thead-dark">
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
                            <td colspan="11">No data available</td>
                        </tr>
                    </tbody>
            </table>
            </div>
        </div>
</div>
@endsection
@section('moreJs')
 <script src="{{ asset('assets/js/gather-invoice.js') }}"></script>
@endsection
