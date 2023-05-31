<?php

namespace App\Http\Controllers\Cost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\Contract;
use App\Models\Detail;
use App\Services\ContractService;
use App\Services\DataService;

class ContractController extends Controller
{
    protected $contractService;
    protected $dataService;

    public function __construct(ContractService $contractService,DataService $dataService)
    {
        $this->contractService = $contractService;
        $this->dataService     = $dataService;
    } 

    public function index(){

        return view('users/contract/contract');
        
    }

    public function store(Request $request){


        return empty($request->id) ? Contract::store($request) : Contract::updateContract($request);

    }

    public function list(Request $request){

        return $this->contractService->list($request);

    }

    public function search(Request $request){

        $data   =   $this->dataService->sqlSap($request,null,'advance_payments');

        return ($this->dataService->filterItemCode($data ?? [],FALSE));

    }

    public function invoiceSave(Request $request,Contract $contract){
        
        $exists  =  Detail::where('pono',$request->input('pono'))->orWhere('invoiceno',$request->input('invoiceno'))->exists();

        $detail  =  ($exists) ? Detail::where('pono',$request->pono)->first(['id','qtymt']) : Detail::storeInvoice($request);
        
        $allocatedAmount = (($contract->paidAmountUSD*$detail->qtymt)/$contract->metricTon);

        $percent   = ($allocatedAmount/($contract->priceMetricTon*$detail->qtymt)*100);

        $amount    = ($contract->priceMetricTon*$detail->qtymt);

        return $contract->advance_payment()->create(['detail_id'=>$detail->id,'percentage'=>$percent,'allocatedAmount'=>$allocatedAmount,'amount'=>$amount]);

    }

}
