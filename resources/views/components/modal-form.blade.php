<!-- <form id="formStore" method="POST" autocomplete="off">@csrf -->
<div class="modal fade" id="staticBackdrop-{{ $code }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-{{ $landedCostParticular->particular->p_code=='NEG' || $landedCostParticular->particular->p_code=='FR'?'xl':'sm'}}">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h6 class="modal-title" style="font-size: 13px;" id="staticBackdropLabel">{{ $landedCostParticular->particular->p_name }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="spcPrtclr-{{ $landedCostParticular->id }}" value="{{ $landedCostParticular->particular->p_code }}">
                <input type="hidden" name="id-{{ $landedCostParticular->id }}" value="{{ $landedCostParticular->id }}">
                @if($landedCostParticular->particular->p_code==Helper::$except_code[1])
                    <!-- <div class="input-group input-group-sm d-none">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><b>Total</b></span>
                        </div>
                        number_format($landedCostParticular->amount,2) ?? 0
                    </div> -->
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12">
                            <input type="hidden" name="id-nego">
                            <div class="form-group">
                                <label for="">Metric Tons</label>
                                <input type="text" class="form-control form-control-sm" name="metricTon" value="{{ $mt }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Price Per MT <span class="text-danger text-bolder">($)</span></label>
                                <input type="text" class="form-control form-control-sm" name="priceMetricTon" autocomplete="off" id="{{ $landedCostParticular->id }}" >
                            </div>
                            <div class="form-group">
                                <label for="">Percentage</label>
                                
                                <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="percentage"  autocomplete="off" id="{{ $landedCostParticular->id }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                </div>
                                <b><small class="">Remaining Percentage: <span class="text-info percentLeft"></span>%</small></b>
                            </div>
                            <div class="form-group">
                                <label for="">Amount <span class="text-danger text-bolder">($)</span></label>
                                <input type="text" class="form-control form-control-sm" name="amount" autocomplete="off" id="{{ $landedCostParticular->id }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6">
                                    <label for="">Exchange Rate</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" name="exchangeRate" autocomplete="off"  id="{{ $landedCostParticular->id }}" >
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">ExchangeRate Date</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" name="exchangeRateDate"   value="{{ date('m/d/Y') }}" autocomplete="off"  id="{{ $landedCostParticular->id }}" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Total Amount</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" name="negoTotalAmount" readonly  id="{{ $landedCostParticular->id }}" >
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="">Total</label>
                                <div class="input-group input-group-sm"> -->
                                    <!-- </div>
                                </div> -->
                                <button class="btn btn-sm btn-success btn-block" name="btnNego" type="submit" value="{{ $landedCostParticular->id }}" >Add Transaction&nbsp;&nbsp;<i class="fas fa-plus-circle"></i></button>
                                <button class="btn btn-sm btn-danger btn-block" name="btnNegoCancel" type="button" value="{{ $landedCostParticular->id }}" >Cancel Transaction&nbsp;&nbsp;<i class="fas fa-times-circle"></i></button>
                            </div>
                            <div class="col-lg-9 col-md-12 col-sm-12">
                                <table class="table table-bordered table-hover mt-2" style="font-size:11px">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Price Per MT</th>
                                            <th>Percentage</th>
                                            <th>Amount</th>
                                            <th>Exchange Rate</th>
                                            <th>ExchangeRate Date</th>
                                            <th>Total</th>
                                            <th class="text-center">Control</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableNeg">
                                        <tr>
                                            <td colspan="7" class="text-center">No data available</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" class="form-control form-control-sm" name="totalNego" id="{{ $landedCostParticular->id }}" >
                        </div>
                    </div>
                           
                @elseif($landedCostParticular->particular->p_code==Helper::$except_code[0])
                    @if(!is_null($lcoc))
                    <div class="form-group">
                        <label for="">Metric Tons</label>
                        <input type="text" class="form-control form-control-sm opening-charge" 
                               required
                               name="metricTon"
                               value="{{ $mt }}" 
                               readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Total LC Opening Amount</label>
                        <input type="text" class="form-control form-control-sm opening-charge" 
                               required
                               name="lc_amount"
                               readonly
                               value="{{ $lcoc->open_amount->lc_amount }}">
                    </div>
                    <div class="form-group">
                        <label for="">Total LC Metric Ton</label>
                        <input type="text" class="form-control form-control-sm opening-charge" 
                               required
                               name="lc_mt"
                               readonly
                               value="{{ $lcoc->open_amount->lc_mt ?? 0 }}">
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><b>Total</b></span>
                        </div>
                        <input type="text"
                               class="form-control"
                               name="totalOpenCharge"
                               readonly
                               value="{{ number_format((($mt/$lcoc->open_amount->lc_mt)*$lcoc->open_amount->lc_amount),2) }}">
                    </div>
                    @else
                        <div class="alert alert-primary" role="alert">
                            <h6 class="alert-heading">Information Message</h6>
                            No open amount for this invoice
                            <a href="{{ route('authenticate.opening.charge') }}" class="mt-2 btn btn-success btn-sm">Open amount here</a>
                        </div>
                    @endif
                @elseif($landedCostParticular->particular->p_code==Helper::$except_code[2])
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <input type="hidden" name="id-freight">
                        <div class="form-group">
                            <label for="">
                               {{ $landedCostParticular->detail->cardname=='MIP'?'Container': 'MV Vessel'}}
                            </label>
                            <input type="text" class="form-control form-control-sm" 
                                required
                                name="vesselType"
                                readonly
                                value="">
                        </div>
                        <div class="form-group">
                            <label for="">Dollar Rate <span class="text-danger">($)</span></label>
                            <input type="text" class="form-control form-control-sm freight-amount" 
                                required
                                name="freightDollarRate"
                                autocomplete="off">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="">Exchange Rate</label>
                                <input type="text" class="form-control form-control-sm freight-amount" 
                                    required
                                    name="freightExhangeRate"
                                    autocomplete="off">
                            </div>
                            <div class="form-group col-6">
                                <label for="">Exchange Rate Date</label>
                                <input type="text" class="form-control form-control-sm" 
                                    required
                                    name="freightExhangeRateDate"
                                    value="{{ date('m/d/Y') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Total Freight</label>
                            <input type="text" class="form-control form-control-sm freight-amount" 
                                required
                                name="freightTotalAmount"
                                value=""
                                readonly>
                        </div>
                        <input type="hidden" class="form-control form-control-sm" name="totalFreight" id="{{ $landedCostParticular->id }}" >
                        <button class="btn btn-sm btn-success btn-block" name="btnFreight" type="submit" value="{{ $landedCostParticular->id }}" >Add Transaction&nbsp;&nbsp;<i class="fas fa-plus-circle"></i></button>
                        <button class="btn btn-sm btn-danger btn-block" name="btnFreightCancel" type="button" value="{{ $landedCostParticular->id }}" >Cancel Transaction&nbsp;&nbsp;<i class="fas fa-times-circle"></i></button>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <table class="table table-sm table-bordered" style="font-size: 11px;">
                            <thead class="table-dark">
                                <tr>
                                    <td>Dollar Rate</td>
                                    <td>Exchange Rate</td>
                                    <td>Total</td>
                                    <td>Exchange Date</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody class="freightTable">
                                <tr>
                                    <td class="text-center" colspan="2">No data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                   
                @endif
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                @if($landedCostParticular->particular->p_code=='NEG' || $landedCostParticular->particular->p_code=='FR' || !is_null($lcoc))
                <button type="button" class="btnSubmit btn btn-sm btn-primary" value="{{  $landedCostParticular->id }}">Save Transaction</button>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- </form> -->