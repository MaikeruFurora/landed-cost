<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\LandedcostParticular;
use App\Models\Particular;
use App\Helper\Helper;
use App\Models\Company;
use App\Models\Freight;
use App\Models\Lcdpnego;
use App\Services\FreightService;
use App\Services\LandedCostService;
use App\Services\NegoService;
use App\Services\OpenChargeService;
use Illuminate\Http\Request;

class LandedCostController extends Controller
{

    protected $landedCostService;

    protected $negoService;

    protected $openChargeService;

    protected $freightService;

    public function __construct(
        LandedCostService $landedCostService,
        NegoService $negoService,
        OpenChargeService $openChargeService,
        FreightService $freightService
    ){

        $this->landedCostService = $landedCostService;
        $this->negoService       = $negoService;
        $this->openChargeService = $openChargeService;
        $this->freightService    = $freightService;
    }

    public function index(Detail $detail){

        if (Helper::usrChckCntrl(['LC003'])) {

            $this->landedCostService->checkParticular($detail);
        
            if (empty($detail->posted_at)) {
                
                $this->landedCostService->updateAmntAndRef($detail);
            
            }
            
            $detail->load(['landedcost_particulars','lcopeningcharges','landedcost_particulars.particular','lcopeningcharges.open_amount']);
    
            $companies = Company::get(['id','companyname']);

            if ($detail->itemcode=='PM') {
                return view('users.landed-cost-sack',compact('detail','companies'));
            }else{
                return view('users.landed-cost',compact('detail','companies'));
            }

        }

        return view('users.default'); 

    }

    public function formStore(Request $request){

        $landedCostParticular = LandedcostParticular::find($request->id);
        
        switch ($request->input('spcPrtclr')) {

            case  Helper::$intact_particular[0]['p_code']:
                    return $this->openChargeService->store($request,$landedCostParticular);
                break;

            case  Helper::$intact_particular[1]['p_code']:
                    return $this->negoService->store($request,$landedCostParticular);
                break;
            
            default:
                    return $this->freightService->store($request,$landedCostParticular);
                break;
        }
        
    }

    public function particularInput(Request $request,LandedcostParticular $landedcostParticular){

        $amount = Helper::cleanNumberByFormat($request->input('amount'));
        
        return $landedcostParticular->update([

            'transaction_date'  =>  $request->transactionDate,

            'amount'            =>  $amount,

            'referenceno'       =>  $request->referenceno

        ]);

    }

    public function print(Detail $detail){

        $this->landedCostService->checkParticular($detail);

        if (empty($detail->posted_at)) {
            
            $this->landedCostService->updateAmntAndRef($detail);

        }

        $detail->load(['landedcost_particulars','lcopeningcharges','landedcost_particulars.particular','lcopeningcharges.open_amount','landedcost_particulars'=>function($q){
                return $q->with('lcdpnego')->get();
        }]);

        if ($detail->itemcode=='PM'){
            
            $paticulars = Particular::get(['id','p_name']);

            return view('print-sack',compact('detail','paticulars'));
        }else{
            return view('print',compact('detail'));
        }


    }

    public function particularNote(LandedcostParticular $landedcostParticular){

        return $landedcostParticular;

    }

    public function particularNoteStore(Request $request,LandedcostParticular $landedcostParticular){

        return $landedcostParticular->update([
            'notes'=>$request->input('notes')
        ]);

    }

    /**
     * 
     * 
     *  NEGO
     * 
     * 
     */

    public function negoList(LandedcostParticular $landedcostParticular){

        return $landedcostParticular->lcdpnego;
    
    }

    public function negoStore(Request $request,LandedcostParticular $landedcostParticular){

        // return $request;

        $this->negoService->negoStore($request,$landedcostParticular);

    }

    public function negoDestroy(Lcdpnego $lcdpnego){

        return $lcdpnego->delete();

    }

    /**
     *
     *  
     *  FREIGHT
     * 
     * 
     */

     public function freightList(LandedcostParticular $landedcostParticular){

        return $landedcostParticular->freight;
    
    }

    public function freightStore(Request $request, LandedcostParticular $landedcostParticular){

        return $this->freightService->Freightstore($request,$landedcostParticular);

    }

    public function freightDestroy(Freight $freight){

        return $freight->delete();

    }


    
}
