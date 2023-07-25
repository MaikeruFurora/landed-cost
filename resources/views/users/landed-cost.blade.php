@extends('../_layout/app')
@section('moreCss')
<style>
    label{
        font-size: 11px;
    }
    .popover-title {
        color: blue;
        font-size: 1px;
    }
</style>
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
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

<form id="invoiceForm" autocomplete="off">@csrf
    <div class="card border" data-aos="fade-up">
        <div class="card-body">
            <input type="hidden" name="id" value="{{ $detail->id }}">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label for="">Supplier's Name</label>
                        <input type="text" class="form-control form-control-sm" name="suppliername" value="{{ $detail->suppliername }}" @if(!empty($detail->posted_at)) disabled @endif required>
                    </div>
                    <div class="form-group">
                        <label for="">PO Number</label>
                        <input type="text" class="form-control form-control-sm" readonly name="pono" value="{{ $detail->pono }}">
                    </div>
                    <div class="form-group">
                        <label for="">Invoice Number</label>
                        <input type="text" class="form-control form-control-sm" readonly name="invoiceno" value="{{ $detail->invoiceno }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                   <div class="form-row">
                        <div class="form-group col-6">
                            <label for="">Vessel</label>
                            <input type="text" class="form-control form-control-sm" name="vessel" value="{{ $detail->vessel }}">
                        </div>
                        <div class="form-group col-6">
                            <label for="">Broker's Name</label>
                            <input type="text" class="form-control form-control-sm" name="broker" value="{{ $detail->broker }}" @if(!empty($detail->posted_at)) disabled @endif>
                        </div>
                   </div>
                   <div class="form-group">
                        <label for="">Company</label>
                        <select name="selectCompany" class="custom-select custom-select-sm">
                            <option selected disabled>Select Company</option>
                            @foreach($companies as $company)
                                <option  @selected($detail->company_id==$company->id) value="{{ $company->id }}">{{ $company->companyname }}</option>
                            @endforeach
                            <option value="addOption"><b>&plus;</b>&nbsp; Add a selection</option>
                        </select>
                   </div>
                   <div class="form-row">
                        <div class="form-group col-6">
                            <label for="">Posted at</label>
                            <input type="text" class="form-control form-control-sm" readonly name="posted_at" value="{{ $detail->posted_at }}" >
                        </div>
                        <div class="form-group col-6">
                            <label for="">BL NO</label>
                            <input type="text" class="form-control form-control-sm" readonly name="blno" value="{{ $detail->blno }}" >
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input type="text" class="form-control form-control-sm" readonly name="quantity" value="{{ $detail->quantity }}">
                            </div>
                            <div class="form-group">
                                <label for="">Full Container Load</label>
                                <input type="text" class="form-control form-control-sm" readonly name="fcl" value="{{ $detail->fcl }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="">Total Quantity in MT</label>
                                <input type="text" class="form-control form-control-sm" readonly name="qtymt" value="{{ $detail->qtymt }}">
                            </div>
                            <div class="form-group">
                                <label for="">Total Quantity in KLS</label>
                                <input type="text" class="form-control form-control-sm" readonly name="qtykls" value="{{ $detail->qtykls }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="">Actual Quantity in MT</label>
                                <input type="text" class="form-control form-control-sm" name="actualQtyMT" value="{{ $detail->actualQtyMT }}" @if(!empty($detail->posted_at)) disabled @endif>
                            </div>
                            <div class="form-group">
                                <label for="">Actual Quantity in KLS</label>
                                <input type="text" class="form-control form-control-sm" name="actualQtyKLS" value="{{ $detail->actualQtyKLS }}" @if(!empty($detail->posted_at)) disabled @endif>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-sm-12">
                            <div class="form-group">
                                    <label for="">Description</label>
                                    <input type="text" class="form-control form-control-sm" readonly name="description" value="{{ $detail->description }}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                    <label for="">Type</label>
                                     <select name="cardname" id="" class="form-control form-control-sm" {{ (!empty($detail->cardname) || !empty($detail->posted_at)) ?'readonly' :'required' }}>
                                        <option value="" @selected(empty($detail->cardname))>PLEASE SELECT</option>
                                        <option value="MIP" @selected(trim($detail->cardname)=="MIP")>MIP</option>
                                        <option value="MV VESSEL" @selected(trim($detail->cardname)=="MV VESSEL")>MV VESSEL</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer p-2 bg-secondary">
            <div class="row justify-content-between">
                <div class="col-4">
                    <small class="ml-2"></small>
                </div>
                <div class="col-4">
                    <!-- accounting -->
                    
                    @if(Helper::usrChckCntrl(['LC005']))
                        <button class="float-right btn btn-sm btn-primary ml-2 pl-3 pr-3" name="postBtn"
                            @if(!empty($detail->posted_at) && !Helper::usrChckCntrl(['LCOO6'])) disabled @endif
                        >
                            <i class="fas fa-check"></i> {{ empty($detail->posted_at)?'Post':'Unpost' }}
                        </button>
                    @endif
                    <!-- endaccounting -->
                    @if(empty($detail->posted_at) && Helper::usrChckCntrl(['LC007']))
                    <button type="submit" class="float-right btn btn-success btn-sm"><i class="fas fa-user-shield"></i> Save</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

    <h6 class="page-title m-0 mb-2" data-aos="fade-up">PARTICULAR (S)</h6>

    <div class="row">
        @if(count(auth()->user()->myRights())>0 || auth()->user()->type)

        @foreach($detail->landedcost_particulars->sortBy('particular.p_sort', SORT_REGULAR, false) as $landedCostParticular)
    
            @if(in_array($landedCostParticular->particular->id,auth()->user()->myRights()) || auth()->user()->type)
                <x-landed-cost.landed-cost-input 
                    :landedCostParticular="$landedCostParticular"
                    :detail="$detail"
                    :companies="$companies"
                />
            @endif

        @endforeach
                
    </div><!--row-->
   @if(auth()->user()->accountingHead() || auth()->user()->type)
   <div class="card mt-3 shadow-sm" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="false">
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
                            <b class="ml-2">Projected Cost(Per Kilo)</b>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-secondary" disabled type="button" id="button-addon1">&#8369;</button>
                                </div>
                                <input type="text" class="form-control" placeholder="0.00" readonly id="projectedCost">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   @endif
    
    @else
    <div class="col-lg-12">
        <div class="card mt-3 shadow-sm">
            <div class="card-body pb-0">
            <p class="text-center">  No available here </p>
            </div>
        </div>
    </div>
    @endif

@endsection
@section('moreJs')
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
<script src="{{ asset('assets/js/landed-cost.js')}}"></script>
<script src="{{ asset('assets/js/company.js')}}"></script>
<script src="{{ asset('assets/js/nego.js')}}"></script>
<script src="{{ asset('assets/js/freight.js')}}"></script>
@endsection
