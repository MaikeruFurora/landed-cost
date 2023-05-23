<div class="col-lg-12 col-md-12 col-sm-12 card-particular ">
<div class="card mt-0 mb-2 shadow-sm card-hover focusEffect{{ $landedCostParticular->id }}" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-mirror="true" data-aos-once="false">
        <div class="card-body pt-2 pb-2 border" >
            <div class="row justify-content-between">
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <h6 class="py-1" style="font-size: 14px;">{{ $landedCostParticular->particular->p_name }}</h6>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <div class="input-group m-1">
                        <input type="search" 
                            id="{{ $landedCostParticular->id }}"
                            class="form-control transaction-date-class"
                            placeholder="MM/DD/YYYY"
                            name="transaction-date-{{ $landedCostParticular->id }}"
                            value="{{ !empty($landedCostParticular->transaction_date) ? date('m/d/Y',strtotime($landedCostParticular->transaction_date)) : '' }}"
                            @if(!empty($detail->posted_at)) readonly @endif
                            >
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="input-group m-1">
                        {{-- @if($landedCostParticular->particular->company)
                            <select name="selectCompany" id="{{ $landedCostParticular->id }}" class="custom-select">
                                <option selected disabled>Select Company</option>
                                @foreach($companies as $company)
                                    <option @selected($landedCostParticular->company_id==$company->id) value="{{ $company->id }}">{{ $company->companyname }}</option>
                                @endforeach
                                <option value="addOption"><b>&plus;</b>&nbsp; Add a selection</option>
                            </select>
                        @endif --}}
                        <input type="text" 
                            id="{{ $landedCostParticular->id }}"
                            class="form-control refrence-class"
                            placeholder="Reference No."
                            name="referenceno-{{ $landedCostParticular->id }}"
                            value="{{ $landedCostParticular->referenceno }}"
                            @if(!empty($detail->posted_at)) readonly @endif
                            >
                        <div class="input-group-append btn-save-referenceno btnCheckRef-{{ $landedCostParticular->id }}">
                            <button class="btn btn-outline-success" 
                                    type="button"
                                    name="nameReferenceNo"
                                    id="button-addon2"
                                    value="{{ $landedCostParticular->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                   
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="input-group m-1">
                        <div class="input-group-prepend">
                            <button class="btn btn-secondary" disabled type="button" id="button-addon1">&#8369;</button>
                        </div>
                        <input type="text" 
                            id="{{ $landedCostParticular->id }}"
                            class="form-control amount-class" 
                            placeholder="0.0000"
                            value="{{ number_format($landedCostParticular->amount,4) }}"
                            name="amount-{{ $landedCostParticular->id }}"
                            @if($landedCostParticular->particular->action || !empty($detail->posted_at)) readonly @endif
                            >
                            <!-- pattern="[0-9]+([\.,][0-9]+)?" step="0.01" -->
                        <div class="input-group-append btn-save-particular btnCheckAmnt-{{ $landedCostParticular->id }}">
                            <button class="btn btn-outline-success" 
                                    type="button"
                                    id="button-addon2"
                                    name="btnAmount"
                                    value="{{ $landedCostParticular->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
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
                        <x-modal-form
                                :lcoc="$detail->lcopeningcharges"
                                :mt="$detail->qtymt"
                                :code="$landedCostParticular->particular->p_code" 
                                :landedCostParticular="$landedCostParticular"
                        />
                        <button class="btn btn-primary float-right mt-1 btnTransaction"
                                style="font-size:12px"
                                data-toggle="modal"
                                data-target="#staticBackdrop-{{ $landedCostParticular->particular->p_code }}"
                                data-code="{{ $landedCostParticular->particular->p_code }}"
                                value="{{ $landedCostParticular->id }}"
                                id="{{ $detail->qtymt }}"
                        >Transaction</button>
                        @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>