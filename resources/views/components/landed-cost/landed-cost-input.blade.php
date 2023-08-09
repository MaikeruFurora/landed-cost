<div class="col-lg-12 col-md-12 col-sm-12 card-particular ">
    <div class="card mt-0 mb-1 border shadow-sm card-hover focusEffect{{ $landedCostParticular->id }}" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="false">
        <div class="card-body pb-2">
            
            <div class="row justify-content-between">
                <div class="col-lg-2 col-md-12 col-sm-12 expand" id="{{ $landedCostParticular->id }}"
                    @if($detail->itemcode=='PM')  style="cursor: pointer"
                            data-toggle="collapse" 
                            data-target="#col<?=$landedCostParticular->id?>sasa"
                            aria-controls="col<?=$landedCostParticular->id?>sasa"
                    @endif>
                        <h6 class="py-1" style="font-size: 14px;">
                            @if($detail->itemcode=='PM')<i class="fas fa-chevron-circle-down mr-1 text-primary"></i>@endif
                            {{ $landedCostParticular->particular->p_name }}
                    </h6>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <div class="input-group m-1">
                        <input type="search" 
                            id="{{ $landedCostParticular->id }}"
                            class="form-control form-control-sm transaction-date-class"
                            placeholder="MM/DD/YYYY"
                            name="transaction-date-{{ $landedCostParticular->id }}"
                            value="{{ !empty($landedCostParticular->transaction_date) ? date('m/d/Y',strtotime($landedCostParticular->transaction_date)) : '' }}"
                            @if(!empty($detail->posted_at)) readonly @endif
                            >
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="input-group m-1">
                        <input type="text" 
                            id="{{ $landedCostParticular->id }}"
                            class="form-control form-control-sm refrence-class"
                            placeholder="Reference No."
                            name="referenceno-{{ $landedCostParticular->id }}"
                            value="{{ $landedCostParticular->referenceno }}"
                            @if(!empty($detail->posted_at)) readonly @endif
                            >
                    </div>
                    
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="input-group input-group-sm m-1">
                        <div class="input-group-prepend">
                            <button class="btn btn-secondary" disabled type="button" id="button-addon1">&#8369;</button>
                        </div>
                        <input type="text" 
                            id="{{ $landedCostParticular->id }}"
                            class="form-control amount-class amount-sack-class" 
                            placeholder="0.0000"
                            value="{{ number_format($landedCostParticular->amount,4) }}"
                            name="amount-{{ $landedCostParticular->id }}"
                            @if($landedCostParticular->particular->action || !empty($detail->posted_at)) readonly @endif
                            >
                            <!-- pattern="[0-9]+([\.,][0-9]+)?" step="0.01" -->
                    </div>
                </div>
                <div class="col-lg-1 col-md-12 col-sm-12">
                    <button type="button" class="btn btn-note float-right mr-2" data-toggle="popover" value="{{ $landedCostParticular->id }}"
                            data-html="true"
                            title="<small>{{ $landedCostParticular->particular->p_name }} (Notes)</small>"
                            data-content='
                            <textarea name="note-{{ $landedCostParticular->id }}" class="form-control" maxlength="500" cols="50" rows="10"></textarea>
                            <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                <button type="button" style="font-size:11px" class="btn btn-sm btn-success pop-save" value="{{ $landedCostParticular->id }}"><i class="fas fa-pencil-alt"></i>&nbsp;Save</button>
                                <button type="button" style="font-size:11px" class="btn btn-sm btn-danger pop-close"><i class="fas fa-times"></i>&nbsp;Close</button>
                            </div>
                            '>
                    <i class="fas fa-comment-alt text-info" style="font-size:16px"></i>
                    <small style="font-size: 10px;display:block">Notes</small>
                    </button>
                </div>
                
                @if(empty($detail->posted_at)) 
                <div class="col-lg-1 col-md-12 col-sm-12">
                    @if($landedCostParticular->particular->action)
                        

                        @if ($detail->itemcode=='PM')
                          @switch($landedCostParticular->particular->p_code)
                              @case("NEG")
                                    <x-landed-cost.sack-lcdpnego
                                        :landedCostParticular="$landedCostParticular"
                                        :detail="$detail"
                                    />
                                  @break
                              @case(2)
                                  
                                  @break
                              @default
                                  
                          @endswitch
                        @else
                            <x-modal-form
                                    :lcoc="$detail->lcopeningcharges"
                                    :mt="$detail->qtymt"
                                    :code="$landedCostParticular->particular->p_code" 
                                    :landedCostParticular="$landedCostParticular"
                            />
                        @endif

                        <button class="btn btn-outline-primary float-right mt-1 btnTransaction"
                                style="font-size:12px"
                                data-toggle="modal"
                                data-target="#staticBackdrop-{{ $landedCostParticular->particular->p_code }}"
                                data-code="{{ $landedCostParticular->particular->p_code }}"
                                value="{{ $landedCostParticular->id }}"
                                id="{{ $detail->qtymt }}"
                        ><i class="fas fa-info-circle"></i>&nbsp;&nbsp;View</button>
                        @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    {{--  --}}
    @if ($detail->itemcode=='PM')
        <div id="col<?=$landedCostParticular->id?>sasa" class="collapse" data-parent="#accordionExample">
            <div class="card-body border pb-1 mb-1 border-right" style="background: #f9eee8">
                <form>
                @foreach ($detail->item as $i => $item)
                <div class="row mb-2" >
                    <div class="col-6 py-2">
                    {{ ++$i.". ".$item->description }}
                    </div>
                    <div class="col-2">
                    <input type="text"  class="form-control form-control-sm number-class" disabled value="{{ $item->qtypcs }}">
                    </div>
                    <div class="col-2">
                        <label class="sr-only" for="inlineFormInputGroup"></label>
                        <div class="input-group input-group-sm mb-2" >
                        <div class="input-group-prepend">
                            <div class="input-group-text font-weight-bold" style="font-size: 11px">&#8369;</div>
                        </div>
                        <input type="text" name="amntPerPCS-{{ $item->id }}" class="form-control number-class" id="inlineFormInputGroup" readonly value="{{ ($item->qtypcs/$detail->quantity)*$landedCostParticular->amount }}">
                        </div>
                    </div>
                </div>
                @endforeach
                </form>
            </div>
        </div>
    @endif
</div>