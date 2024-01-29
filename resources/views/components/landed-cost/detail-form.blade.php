<div>
    <input type="hidden" value="{{ $detail->item }}" name="sackItem">
    <form id="invoiceForm" autocomplete="off">@csrf
        <div class="card border" data-aos="fade-up">
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $detail->id }}">
                <input type="hidden" name="cardcode" value="{{ $detail->cardcode }}">
                <input type="hidden" name="doc_date" value="{{ $detail->doc_date }}">
                <input type="hidden" name="weight"   value="{{ $detail->weight }}">
                <input type="hidden" name="sap"      value="{{ $detail->sap }}">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group mb-0">
                            <label for="">Supplier's Name</label>
                            <select style="" id="{{ route("authenticate.report.filter.supplier") }}" name="suppliername" class="form-control"  data-id="{{ $detail->suppliername }}" data-name="{{ $detail->suppliername }}" ></select>
                            <em><small style="font-size: 10px" class="supplier_reflect">{{ $detail->suppliername }}&nbsp;</small></em>
                            {{-- <input type="text" class="form-control form-control-sm" name="suppliername" value="{{ $detail->suppliername }}" @if(!empty($detail->posted_at)) disabled @endif required> --}}
                        </div>
                        <div class="form-group">
                            <label for="">PO Number</label>
                            <input type="text" class="form-control form-control-sm" readonly name="pono" value="{{ $detail->pono }}">
                        </div>
                        <div class="form-group">
                            <label for="">Invoice Number</label>
                            <input type="text" class="form-control form-control-sm" readonly name="invoiceno" value="{{ $detail->invoiceno }}">
                        </div>
                        <div class="form-group">
                            <label for="">Destination</label>
                            <input type="text" class="form-control form-control-sm" name="invoiceno" value="{{ $detail->destination }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="">Vessel</label>
                            <input type="text" class="form-control form-control-sm" name="vessel" value="{{ $detail->vessel }}">
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
                        <div class="form-group">
                            <label for="">BL NO</label>
                            <input type="text" class="form-control form-control-sm" readonly name="blno" value="{{ $detail->blno }}" >
                        </div>
                        <div class="form-group">
                            <label for="">Posted at</label>
                            <input type="text" class="form-control form-control-sm" readonly name="posted_at" value="{{ $detail->posted_at }}" >
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="">Quantity</label>
                                    <input type="text" class="form-control form-control-sm"  name="quantity" value="{{ $detail->quantity }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Full Container Load</label>
                                    <input type="text" class="form-control form-control-sm"  name="fcl" value="{{ $detail->fcl }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="">Total Quantity in MT</label>
                                    <input type="text" class="form-control form-control-sm" name="qtymt" value="{{ $detail->qtymt }}">
                                </div>
                                <div class="form-group">
                                    <label for="">Total Quantity in KLS</label>
                                    <input type="text" class="form-control form-control-sm" name="qtykls" value="{{ $detail->qtykls }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="">Actual Quantity in MT</label>
                                    <input type="text" class="form-control form-control-sm" name="actualQtyMT" value="{{ $detail->actualQtyMT }}" 
                                        @if(!empty($detail->posted_at)) disabled @endif
                                        @if($detail->itemcode=='PM') disabled @endif>
                                </div>
                                <div class="form-group">
                                    <label for="">Actual Quantity in KLS</label>
                                    <input type="text" class="form-control form-control-sm" name="actualQtyKLS" value="{{ $detail->actualQtyKLS }}" 
                                        @if(!empty($detail->posted_at)) disabled @endif
                                        @if($detail->itemcode=='PM') disabled @endif>
                                </div>
                            </div>
                        </div>
                      
                        <div class="form-group">
                            <label for="">Description</label>
                            <input type="text" class="form-control form-control-sm" readonly name="description" value="{{ $detail->description }}">
                        </div>
                        <div class="row">
                            
                            <div class="col-lg-8 col-sm-12">
                                <div class="form-group">
                                    <label for="">Broker's Name</label>
                                    <input type="text" class="form-control form-control-sm" name="broker" value="{{ $detail->broker }}" @if(!empty($detail->posted_at)) disabled @endif>
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
            <div class="card-footer p-1">
                <div class="row justify-content-between">
                    <div class="col-4">
                        <small class="ml-2">{{ $detail->sap }}</small>
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
</div>