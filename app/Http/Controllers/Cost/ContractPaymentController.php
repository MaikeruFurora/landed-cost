<?php

namespace App\Http\Controllers\Cost;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\ContractPayment;
use App\Models\Detail;
use App\Models\InvoicePayDetail;
use App\Models\InvoicePayment;
use App\Models\LandedcostParticular;
use App\Models\Lcdpnego;
use App\Models\PaymentDetail;
use App\Services\ContractPaymentService;
use App\Services\DataService;
use App\Services\NegoService;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Help;

class ContractPaymentController extends Controller
{
   
    protected $contractPaymentService;
    protected $dataService;
    protected $negoService;
    
    public function __construct(ContractPaymentService $contractPaymentService,DataService $dataService,NegoService $negoService)
    {
        $this->contractPaymentService   = $contractPaymentService;
        $this->dataService              = $dataService;
        $this->negoService              = $negoService;
    } 

     public function index(){
        return view('users.payment.index');
    }

    public function store(Request $request){


        $data = (empty($request->id)) 

        ? ContractPayment::storePayment($request)

        : ContractPayment::updatePayment($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){
        return $this->contractPaymentService->list($request);
    }

    public function detail(ContractPayment $contractPayment){
        return view('users.payment.details',compact('contractPayment'));
    }

    public function storeDetail(Request $request){
        
        $data = (empty($request->id)) 

        ? PaymentDetail::storePayment($request)

        : PaymentDetail::updatePayment($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }

    }

    public function listDetail(Request $request,ContractPayment $contractPayment){

        // return $contractPayment->payment_detail;
        return $this->contractPaymentService->listDetail($request,$contractPayment);        
    }

    public function search(Request $request){

        $data   =   $this->dataService->sqlSap($request,'lcdpnego');

        return ($this->dataService->filterItemCode($data ?? [],FALSE));

    }

    public function save(Request $request){

        // return $request;
        
        /* This code block is related to saving an invoice for a contract. */
        $particular      = $this->negoService->checkFirstParticular();

        $request->request->add(['search'=>$request->invoiceno]);

        $data            =  $this->dataService->sqlSap($request,'lcdpnego');

        $restoreReq      = Helper::fields($data);
    
        $exists          = Detail::where('invoiceno',$request->input('invoiceno'))->exists();

        $detail          = ($exists) ? Detail::where('invoiceno',$request->invoiceno)->first(['id','qtymt','invoiceno']) : Detail::storeInvoice($restoreReq);

        $landedCost      = LandedcostParticular::checkExistInvoiceAndParticular($detail->id,$particular->id)->first();
        
        $lcData          =  ($landedCost)    ? $landedCost : LandedcostParticular::create(['detail_id'=>$detail->id,'particular_id'=>$particular->id,'amount'=>NULL]);

        $existsNego      =  Lcdpnego::withWhereHas('landedcost_particular.detail',function($q) use ($detail){
                                $q->where('id',$detail->id);
                            })->get();
        
        if (count($existsNego)>0) {
            return response()->json([
                'msg'   => 'Already Exists!'
            ]);
        }else{
    
            $lcpart          = $detail->landedcost_particulars->where('particular.p_code',Helper::$intact_particular[1]['p_code'])->first();
            
            if ($lcpart) {
                
                $dataInvoicepay = InvoicePayment::with('invoice_pay_detail')->find($request->invoice_payment);

                foreach ($dataInvoicepay->invoice_pay_detail as $key => $value) {
                    Lcdpnego::create([
                       'landedcost_particular_id'=> $lcpart->id,
                       'priceMetricTon'          => $dataInvoicepay->priceMetricTon,
                       'percentage'              => $value->totalPercentPayment,
                       'amount'                  => $value->dollar,
                       'exchangeRate'            => $value->exchangeRate,
                       'exchangeRateDate'        => $value->exchangeDate,
                   ]);
                }

                // update reference
                $lcData->amount            = $dataInvoicepay->invoice_pay_detail->sum('totalAmountInPHP'); 
                $lcData->save();
                $dataInvoicepay->reference = $detail->invoiceno;
                $dataInvoicepay->save();

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
