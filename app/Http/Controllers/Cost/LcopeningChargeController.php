<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
//models
use App\Models\Particular;
use App\Models\Detail;
use App\Models\LcopeningCharge;
use App\Models\OpenAmount;
use App\Models\LandedcostParticular;
//services
use App\Services\DataService;
use App\Services\OpenChargeService;
//helper function
use App\Helper\Helper;

use Illuminate\Http\Request;

class LcopeningChargeController extends Controller
{

    protected $dataService;
    protected $openChargeService;
    
    public function __construct(DataService $dataService,OpenChargeService $openChargeService){
        $this->dataService = $dataService;
        $this->openChargeService = $openChargeService;
    }

    public function index(){

        $data =  OpenAmount::with('lcopening_charge')->simplePaginate(4);    

        return view('users.opening-charge',compact('data'));

    }

    public function list(Request $request){
    
        return $this->openChargeService->invoiceList($request);

    }

    public function store(Request $request){

        $res = empty($request->id)? OpenAmount::store($request) : OpenAmount::updateAmount($request);

        if ($res) {

            return redirect()->back();
            
        }

    }

    

    public function invoice(OpenAmount $openAmount){

        return view('users.gather-invoice',[
            
            'openAmount'=>$openAmount
            
        ]);


    }

    public function invoiceList(Request $request){

        // $lcopen =   LcopeningCharge::select('detail_id')->with('detail:id,pono')->get();
        
            // $opnAmnt  = $openAmount->with('lcopening_charge','lcopening_charge.detail:id,pono')->first();

            // return $opnAmnt->pluck('lcopening_charge.detail.pono')->unique()->toArray();

            // return $lcopen->pluck('detail.pono')->unique()->toArray();

            // $array  =   $this->dataService->cleanArrayType($lcopen->pluck('detail.pono')->unique()->toArray());

        // $data   =   $this->dataService->sqlSap($request, $array);

        $data   =   $this->dataService->sqlSap($request, 'detail',null);

        return ($this->dataService->filterItemCode($data ?? [],FALSE));

    }

    public function invoiceSave(Request $request,OpenAmount $openAmount){

        $particular =   $this->openChargeService->checkFirstParticular();

        $exists     =   Detail::where('pono',$request->input('pono'))->orWhere('invoiceno',$request->input('invoiceno'))->exists();

        $detail     =   ($exists) ? Detail::where('pono',$request->pono)->first(['id','qtymt']) : Detail::storeInvoice($request);

                        $openAmount->lcopening_charge()->create(['detail_id'=>$detail->id]);
        
        $landedCost =   LandedcostParticular::checkExistInvoiceAndParticular($detail->id,$particular->id)->first();

        $reqVal     =   new Request([

                            'lc_mt'     =>  $openAmount->lc_mt,

                            'lc_amount' =>  $openAmount->lc_amount

                        ]);

        if($landedCost){

            return $this->openChargeService->store($reqVal->merge(['id'=>$landedCost->id]),$landedCost);

        }else{

            return LandedcostParticular::create([

                'detail_id'    =>  $detail->id,

                'particular_id'=>  $particular->id,

                'amount'    =>  $this->openChargeService->computeLcOpenCharge($detail,$reqVal)

            ]);

        }
        
    }

    public function removeInvoice(LcopeningCharge $lcopeningCharge){

        $particular = Particular::checkIfExists(Helper::$intact_particular[0]['p_code']);

        LandedcostParticular::checkExistInvoiceAndParticular(

            $lcopeningCharge->detail_id,

            $particular->id

            )->update([
                'amount'=>0
            ]);
            
        return $lcopeningCharge->delete();

    }

   


}