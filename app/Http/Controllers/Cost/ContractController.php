<?php

namespace App\Http\Controllers\Cost;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\Contract;
use App\Models\Detail;
use App\Models\LandedcostParticular;
use App\Models\Lcdpnego;
use App\Services\ContractService;
use App\Services\DataService;
use App\Services\NegoService;

class ContractController extends Controller
{
    protected $contractService;
    protected $dataService;
    protected $openChargeService;
    protected $negoService;

    public function __construct(ContractService $contractService,DataService $dataService, NegoService $negoService)
    {
        $this->contractService   = $contractService;
        $this->dataService       = $dataService;
        $this->negoService = $negoService;
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

        $data   =   $this->dataService->sqlSap($request,'lcdpnego');

        return ($this->dataService->filterItemCode($data ?? [],FALSE));

    }

    public function saveInvoice(Request $request,Contract $contract){
        
       /* This code block is related to saving an invoice for a contract. */
        $particular      = $this->negoService->checkFirstParticular();
        
        $exists          = Detail::where('pono',$request->input('pono'))->orWhere('invoiceno',$request->input('invoiceno'))->exists();

        $detail          = ($exists) ? Detail::where('pono',$request->pono)->first(['id','qtymt']) : Detail::storeInvoice($request);

        $landedCost      = LandedcostParticular::checkExistInvoiceAndParticular($detail->id,$particular->id)->first();
        
        ($landedCost)    ? $landedCost : LandedcostParticular::create(['detail_id'=>$detail->id,'particular_id'=>$particular->id]);

       /* These lines of code are calculating the allocated amount, percentage, and amount for a
       particular invoice based on the contract details. */

       $allocatedAmount = (($contract->paidAmountUSD*$detail->qtymt)/$contract->metricTon);
       
       $percent         = ($allocatedAmount/($contract->priceMetricTon*$detail->qtymt)*100);
       
       $amount          = ($contract->priceMetricTon*$detail->qtymt);
       
       $sumMT           =  $contract->lcdpnego->load('landedcost_particular','landedcost_particular.detail')->sum('landedcost_particular.detail.qtymt');
       
       $exists          =  Lcdpnego::withWhereHas('landedcost_particular.detail',function($q) use ($detail){
                              $q->where('id',$detail->id);
                           })->get();

       $sumMT           =  $sumMT==0? $detail->qtymt : $sumMT+$detail->qtymt;

                        //    return count($exists);
       if (count($exists)>0) {
            return response()->json([
                'msg'   => 'Already Exists!'
            ]);
       }else{
           if ($sumMT>$contract->metricTon) {
                return response()->json([
                    'msg'   => 'Adding this invoice will result to overstated quantity versus the specified metric tons on the contract '.$detail->qtymt.'MT vs '.$contract->metricTon.'MT'
                ]);
            }else{
                
                $lcpart          = $detail->landedcost_particulars->where('particular.p_code',Helper::$intact_particular[1]['p_code'])->first();
        
               if ($lcpart) {
                   return Lcdpnego::create([
                       'landedcost_particular_id'=> $lcpart->id,
                       'contract_id'             => $contract->id,
                       'allocatedAmount'         => $allocatedAmount,
                       'priceMetricTon'          => $contract->priceMetricTon,
                       'percentage'              => $percent,
                       'amount'                  => $amount,
                       'exchangeRate'            => $contract->exchangeRate,
                       'exchangeRateDate'        => $contract->exchangeRateDate,
                   ]);
               }
               else{
        
                   return response()->json([
                       'msg'=>'Server error please contact the IT Team'
                   ],500);
        
               }
           }
       }


    }

    public function removeInvoice(Lcdpnego $lcdpnego){

        return $lcdpnego->delete();

    }



}
