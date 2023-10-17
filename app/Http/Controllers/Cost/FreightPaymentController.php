<?php

namespace App\Http\Controllers\Cost;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\Freight;
use App\Models\FreightPayment;
use App\Models\LandedcostParticular;
use Illuminate\Http\Request;
use App\Services\FreightPaymentService;
use App\Services\DataService;

class FreightPaymentController extends Controller
{

    protected $freightPaymentService;
    protected $dataService;
    
    public function __construct(DataService $dataService,FreightPaymentService $freightPaymentService)
    {
        $this->freightPaymentService   = $freightPaymentService;
        $this->dataService   = $dataService;
    } 

    public function store(Request $request){
        
        $data = (empty($request->id)) 

        ? FreightPayment::storePayment($request)

        : FreightPayment::updatePayment($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }

    }

    public function list(Request $request){
        return $this->freightPaymentService->list($request);
    }

    public function saveFreightInvoice(Request $request){
        
        $particular = $this->freightPaymentService->checkFirstParticular();

        $request->request->add(['search'=>$request->invoiceno]);

        $data            =  $this->dataService->sqlSap($request,'lcdpnego');

        $restoreReq      = Helper::fields($data);
    
        $exists          = Detail::where('invoiceno',$request->input('invoiceno'))->exists();

        $detail          = ($exists) ? Detail::where('invoiceno',$request->invoiceno)->first(['id','qtymt','invoiceno']) : Detail::storeInvoice($restoreReq);

        $landedCost      = LandedcostParticular::checkExistInvoiceAndParticular($detail->id,$particular->id)->first();
        
        $lcData          = ($landedCost)    ? $landedCost : LandedcostParticular::create(['detail_id'=>$detail->id,'particular_id'=>$particular->id,'amount'=>NULL]);

        $existsFrieght   =  Freight::withWhereHas('landedcost_particular.detail',function($q) use ($detail){
                                $q->where('id',$detail->id);
                            })->get();

        if (count($existsFrieght)>0) {
            return response()->json([
                'msg'   => 'Already Exists!'
            ]);
        }else{
    
            $lcpart          = $detail->landedcost_particulars->where('particular.p_code',Helper::$intact_particular[4]['p_code'])->first();
    
            if ($lcpart) {

                $dataFreightPayment = FreightPayment::find($request->freight_payment);
              
                Freight::create([
                    'landedcost_particular_id'=> $lcpart->id,
                    'dollarRate'              => $dataFreightPayment->dollar,
                    'exchangeRate'            => $dataFreightPayment->exchangeRate,
                    'exchangeRateDate'        => $dataFreightPayment->exchangeDate
                ]);
                

                // update reference
                $lcData->amount      = $dataFreightPayment->totalAmountInPHP;
                $lcData->referenceno = $dataFreightPayment->quantity." * ".$dataFreightPayment->dollar." * ".$dataFreightPayment->exchangeRate;
                $lcData->save();
                $dataFreightPayment->reference=$detail->invoiceno;
                $dataFreightPayment->save();

                return response()->json([
                    'msg'   => 'Succesfully Saved!'
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
