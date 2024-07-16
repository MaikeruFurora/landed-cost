<div class="modal fade" id="staticBackdrop-{{ $landedCostParticular->particular->p_code }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header pt-2 pb-2">
                <h5 class="modal-title" style="font-size: 13px;" id="staticBackdropLabel">{{ strtoupper($landedCostParticular->particular->p_name) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card m-1 border">
                    <div class="card-body p-0">
                        <form action="{{ route('authenticate.nego.store',['landedcostParticular'=>'sample']) }}" method="POST" id="negoForm" autocomplete="off">@csrf
                            <input type="hidden" name="id">
                            {{--  --}}
                            <div class="form-row px-3 pt-2">
                                <div class="form-group col pb-0 mb-2">
                                    <label for="">Amount <span class="text-primary">(Dollar)</span></label>
                                    <input type="text" placeholder="Amount" class="form-control form-control-sm number-class" name="amount" required readonly>
                                </div>
                                <div class="form-group col pb-0 mb-2">
                                    <label for="">Percent</label>
                                    <input type="text" placeholder="Percent" class="form-control form-control-sm number-class" name="percentage" size="6" required readonly>
                                </div>
                                <div class="form-group col pb-0 mb-2">
                                    <label for="">Dollar Rate <span class="text-primary">(Php)</span></label>
                                    <input type="text" placeholder="Exchange Rate" class="form-control form-control-sm number-class" name="exchangeRate" required>
                                </div>
                                <div class="form-group col pb-0 mb-2">
                                    <label for="">Exchange Date</label>
                                    <input type="text" placeholder="Exchange Rate Date" class="form-control form-control-sm transaction-date-class" name="exchangeRateDate" required>
                                </div>
                            </div>
                            {{--  --}}
                        <div class="table-responsive">
                            <table class="table table-hovered m-0 adjust border-bottom" id="negoEmptySackTable" data-item="{{ $detail->item->pluck('id') }}">
                                <thead style="background: #f9eee8">
                                    <tr style="font-size: 10px"  class="text-center">
                                        <th>ITEM SACK</th>
                                        <th width="12%">QUANTITY</th>
                                        <th width="12%">PRICE <span class="text-primary">(USD)</span></th>
                                        <th width="12%">TOTAL DOLLAR <span class="text-primary">(USD)</span></th>
                                        <th width="12%">TOTAL AMOUNT <sub>EXCHANGE<sup>x</sup>TOTAL DOLLAR</sub> <span class="text-primary">(PHP)</span></th>
                                        <th width="12%">ALLOCATED AMOUNT <span class="text-primary">(DOLLAR)</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sorting = collect($detail->item)->sortBy('description')
                                    @endphp
                                    @foreach ($sorting as $i => $item)
                                    <tr>
                                        <td><small><b>{{ $item->description }}</b></small></td>
                                        <td><input type="text" class="form-control form-control-sm number-class" data-qty="{{ $i }}" disabled value="{{ $item->qtypcs }}" required tabindex="-1"></td>
                                        <td><input type="text" name="prices[]" class="form-control form-control-sm number-class negoPrice" data-price={{ $i }} id="{{ $i }}" required readonly></td>
                                        <td><input type="text" class="form-control form-control-sm number-class negoTotal" data-total="{{ $i }}" id="{{ $i }}" readonly value="0.0000" required tabindex="-1"></td>
                                        <td><input type="text" class="form-control form-control-sm number-class " data-cost="{{ $i }}" id="{{ $i }}" readonly required tabindex="-1"></td>
                                        <td><input type="text" class="form-control form-control-sm number-class " data-allocated="{{ $i }}" id="{{ $i }}" readonly required tabindex="-1"></td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3"></td>
                                        <td><input type="text" name="totalDollar" class="form-control form-control-sm number-class" readonly></td>
                                        <td><button type="submit" class="btn btn-sm btn-outline-primary btn-block" id="{{ $landedCostParticular->id }}">Submit</button></td>
                                        <td><input type="text" name="totalDollarAllocated" class="form-control form-control-sm number-class" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{--  --}}
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive mb-0 mt-2">
                    <table class="table table-bordered text-center adjust border" id="tblNego" style="font-size: 11px" >
                        <thead style="background: #f9eee8">
                            <tr>
                                <th>#</th>
                                <th>DOLLAR RATE</th>
                                <th width="15%">AMOUNT</th>
                                <th>TOTAL AMOUNT <sup class="text-primary">(PHP)</sup></th>
                                <th width="15%">PERCENT</th>
                                <th>EXHANGE RATE DATE</th>
                                <th>COPY PRICE</th>
                                <th>REMOVE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">No Data Availble</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if(!empty($detail->posted_at))
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btnNegoSubmit btn btn-sm btn-primary" value="{{ $landedCostParticular->id }}">Save Transaction</button>
            </div>
            @endif
        </div>
    </div>
</div>