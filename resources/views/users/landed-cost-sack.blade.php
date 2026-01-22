@extends('../_layout/app')
@section('moreCss')
<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet">
<style>
    label{
        font-size: 11px;
    }
    .popover-title {
        color: blue;
        font-size: 1px;
    }
</style>
@endsection
@section('content')
   <!-- Page-Title -->
    <x-page-title title="Invoice Details">
            <!-- href="{{ route('authenticate.details') }}" -->
            <a class="btn btn-primary btn-sm" href="{{ route('authenticate.details') }}">
                <i class="fas fa-arrow-left"></i> Back (Invoice List)
            </a>
            <!-- accounting -->
            @if(Helper::usrChckCntrl(['LC004']))
            <button class="btn btn-warning btn-sm pl-3 pr-3" name="print" value="{{ route('authenticate.print',$detail->id) }}">
                <i class="fas fa-print"></i> Print
            </button>
            @endif
            <!-- endaccounting -->
    </x-page-title>
    <x-landed-cost.company/>
    <!-- end page title end breadcrumb -->
    <x-landed-cost.detail-form
        :detail="$detail"
        :companies="$companies"
    />
    <h6 class="page-title m-0 mb-2" data-aos="fade-up">PARTICULAR (S)</h6>

    @if(count(auth()->user()->myRights())>0 || auth()->user()->type)
    <div class="accordion" id="accordionExample">
        <div class="row">
            @foreach($detail->landedcost_particulars->sortBy('particular.p_sort', SORT_REGULAR, false) as $landedCostParticular)
                @if(in_array($landedCostParticular->particular->id,auth()->user()->myRights()) || auth()->user()->type)
                    @if ($landedCostParticular->particular->p_active)
                    <x-landed-cost.landed-cost-input 
                            :landedCostParticular="$landedCostParticular"
                            :detail="$detail"
                            :companies="$companies"
                        />
                    @endif
                @endif
            @endforeach
        </div>
    </div>
    @endif 
   @if(auth()->user()->accountingHead() || auth()->user()->type)
    <div class="card mt-3 mb-2 shadow-sm" 
            data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="false">
        <div class="card-body p-2">
            <div class="row justify-content-between">
                <div class="col-lg-6 col-md-6 col-sm-12 py-2">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6 col-sm-12 py-2">
                            <b class="ml-2">Total Landed Cost</b>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" disabled type="button" id="button-addon1">&#8369;</button>
                                </div>
                                <input type="text" 
                                    class="form-control"
                                    placeholder="0.00"
                                    readonly
                                    id="totalLandedCost">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 py-2">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6 col-sm-12 py-2">
                            <b class="ml-2">Average Cost</b>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" disabled type="button" id="button-addon1">&#8369;</button>
                                </div>
                                <input type="text" class="form-control" placeholder="0.00" readonly id="averageCost">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   @endif

@endsection
@section('moreJs')
<script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/nego-sack.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/landed-cost-sack.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/company.js')}}?v={{ time() }}"></script>
@endsection
