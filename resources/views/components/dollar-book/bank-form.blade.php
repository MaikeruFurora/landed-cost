<!-- Modal -->
<div class="modal fade" id="bankFormModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="bankFormModalLabel" aria-hidden="true">
    <form id="FundForm" autocomplete="off">@csrf
        <input type="hidden" name="account">
        <input type="hidden" name="bank_history_id">
        <input type="hidden" name="telegraphic_history_id">
        <input type="hidden" name="types">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-1 bg-secondary text-white">
                    <p class="modal-title font-weight-bold ml-2" id="bankFormModalLabel"></p>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form01">
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text px-4">Date</span>
                            </div>
                            <input type="text" class="form-control" autocomplete="off" name="dated_at" maxlength="10" required>
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text px-3">Subject</span>
                            </div>
                            <input type="text" class="form-control" autocomplete="off" name="subject" maxlength="50" required>
                        </div>
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text">Attention</span>
                            </div>
                            <input type="text" aria-label="First name" class="form-control" autocomplete="off" name="attention" maxlength="20" required>
                        </div>
                       <div class="card border mb-2" id="fromCardAccount">
                        <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">FROM ( <em></em> )</span></div>
                            <div class="card-body pb-2 pt-2">
                                <div class="form-row aod-requisite">
                                    <div class="form-group mb-1 col-lg-6">
                                        <label class="form-check-label" for="">Exchange Rate</label>
                                        <input type="text" class="form-control form-control-sm" id="" autocomplete="off" name="exchangeRate" required>
                                    </div>
                                    <div class="form-group mb-1 col-lg-6">
                                        <label class="form-check-label" for="">Exchange Date</label>
                                        <input type="text" class="form-control form-control-sm" id="" autocomplete="off" name="exchangeRateDate" required>
                                    </div>
                                </div>
                                <div class="input-group input-group-sm mb-1">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text px-3 prefixCurrency">N/A</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="" autocomplete="off" name="amount" required>
                                </div>
                                <div class="input-group input-group-sm aod-requisite">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text px-3">PHP</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="" autocomplete="off" name="phpAmount" readonly>
                                </div>
                            </div>
                       </div>
                       <div class="card border mb-2"  id="toCardAccount">
                        <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">TO (Account)</span></div>
                            <div class="card-body pb-3 pt-2">
                                <div class="form-group">
                                    <label class="form-check-label" for="">Account No</label>
                                    <input type="text" maxlength="12" class="form-control form-control-sm" name="toAccountNo" placeholder="Account No." required>
                                </div>
                                <div class="form-group mb-1">
                                    <label class="form-check-label" for="">Account Name</label>
                                    <input type="text" class="form-control form-control-sm" name="toName" placeholder="Name" maxlength="50">
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label class="form-check-label" for="">Bank</label>
                                    <input type="text" class="form-control form-control-sm" name="toBankName" placeholder="Bank Name" maxlength="35">
                                    </div>
                                    <div class="col">
                                        <label class="form-check-label" for="">Branch</label>
                                        <input type="text" class="form-control form-control-sm" name="toBranchName" placeholder="Branch" maxlength="35">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-check-label" for="">Purpose</label>
                                    <textarea id="" class="form-control border" name="purposes" rows="3" placeholder="Type here" maxlength="50" required></textarea>
                                </div>
                           </div>
                       </div>
                    </div>
                    <div class="form02">

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 32A</span></div>
                                    <div class="card-body p-2">
                                        <div class="card border mb-1">
                                            <div class="card-body p-3">
                                                <div class="form-group mb-1">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input ttOption" type="checkbox" id="telegTansOption1" name="domesticTT">
                                                        <label class="form-check-label" for="telegTansOption1">Domestic Telegraphic Transfer</label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-1">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input ttOption" type="checkbox" id="telegTansOption2" name="foreignTT">
                                                        <label class="form-check-label" for="telegTansOption2">Foreign Telegraphic Transfer</label>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <div class="form-group mb-0">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input ttOption" type="checkbox" id="telegTansOption3" name="otherTT">
                                                            <label class="form-check-label" for="telegTansOption3">Other <em>(Please Specify)</em></label>
                                                        </div>
                                                        <input id="my-input" class="form-control form-control-sm" type="text" name="otherTTSpecify" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card border mb-1">
                                            <div class="card-body p-1 pt-2  px-5">
                                                <div class="form-row">
                                                    <div class="form-group mb-0 p-0 col-6">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input crrncyType" type="checkbox" id="pddtsDollar" name="pddtsDollar">
                                                            <label class="form-check-label" for="pddtsDollar">PDDTS (Dollar)</label>
                                                          </div>
                                                    </div>
                                                    <div class="form-group mb-0 p-0 col-6">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input crrncyType" type="checkbox" id="rtgsPeso" name="rtgsPeso">
                                                            <label class="form-check-label" for="rtgsPeso">RTGS (PESO)</label>
                                                          </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 20</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Correspondent / Receiving Bank</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="20_correspondent">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Reference No.</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="20_referenceNo">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Remitters Account No.</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="20_remittersAccountNo">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Invisible Code</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="20_invisibleCode">
                                            </div>
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Importers’ Code</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="20_importersCode">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 32A</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-row">
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Value Date (yy/mm/dd)</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="32a_valueDate">
                                            </div>
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Amount and Currency</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="32a_amountAndCurrency">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 50</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Application Name</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="50_applicationName">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Present Address</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="2" name="50_presentAddress"></textarea>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Permanent Address</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="2" name="50_permanentAddress"></textarea>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Telephone Nos.</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="50_telephoneNo" value="8150469">
                                            </div>
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Tax ID No.</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="50_taxIdNo">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Fax Nos.</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="50_faxNo" value="215-261-911">
                                            </div>
                                            <div class="form-group mb-1 col-6">
                                                <label class="my-input">*Other ID Type and No.</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="50_otherIdType">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 52</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1 ">
                                            <label class="my-input">*Ordering Bank</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="52_orderingBank">
                                        </div>
                                    </div>
                                </div>
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 56</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Intermediary Bank:FW/CH No. or SWIFT Code</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="56_intermediaryBank">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Name</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="56_name">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Address</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="4" name="56_address"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                           
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 57</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Beneficiary Bank:FW/CH No. or SWIFT Code</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="57_beneficiaryBank">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Name</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="57_name">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Address</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="4" name="57_address"></textarea>
                                        </div>
                                        <div class="form-group mb-1 ">
                                            <label class="my-input">*Country of Destination</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="57_CountryOfDestination">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 59</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Beneficiary Account No.</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="59_beneficiaryAccountNo">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Beneficiary Name</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="59_beneficiaryName">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Address</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="4" name="59_address"></textarea>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Industry type</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="59_industryType">
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 70</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Remittance Info</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="2" name="70_remittanceInfo"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 71</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Charge for (Sha/Our)</label>
                                            <select id="" class="form-control form-control-sm" name="71_chargeFor">
                                                <option>SHA</option>
                                                <option>OUR</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">: 72</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Sender to Receiver info</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="2" name="72_senderToReceiverInfo"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">Remitter’s Other information</span></div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group mb-1 col-lg-4">
                                                <label class="my-input">*Source of Funds</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="sourceOfFund">
                                            </div>
                                            <div class="form-group mb-1 col-lg-4">
                                                <label class="my-input">*Industry Type</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="industrytype">
                                            </div>
                                            <div class="form-group mb-1 col-lg-4">
                                                <label class="my-input">*Birthdate/Registration Date</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="registrationDate">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group mb-1 col-lg-4">
                                                <label class="my-input">*Birth Place</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="birthPlace">
                                            </div>
                                            <div class="form-group mb-1 col-lg-4">
                                                <label class="my-input">*Nationality</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="nationality">
                                            </div>
                                            <div class="form-group mb-1 col-lg-4">
                                                <label class="my-input">*Nature of Work/Business</label>
                                                <input id="my-input" class="form-control form-control-sm" type="text" name="natureOfWorkOrBusiness">
                                            </div>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="my-input">*Purpose/Reason</label>
                                            <textarea class="form-control form-control-sm" cols="100" rows="2" name="purposeOrReason"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border mb-1">
                                    <div class="card-header bg-secondary text-white p-0"><span class="ml-2 my-card-header font-weight-bolder">Debit from Account</span></div>
                                    <div class="card-body p-2">
                                        <div class="form-group mb-1">
                                            <label class="my-input">Debit from Account</label>
                                            <input id="my-input" class="form-control form-control-sm" type="text" name="debitFromAccount" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        
                    </div>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle px-3" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Save as</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            @if (Helper::usrChckCntrl(['DB006']))
                            <button type="submit" class="dropdown-item border" name="saveAndPrint"> Save and Print</button>
                            @endif
                            @if (Helper::usrChckCntrl(['DB005']))
                            <button type="submit" class="dropdown-item border" name="save"> Save</button>
                            @endif
                        </div>
                    </div>
                    {{-- <button type="submit" class="btn btn-primary btn-sm">Submit</button> --}}
                </div>
            </div>
        </div>
    </form>
</div>