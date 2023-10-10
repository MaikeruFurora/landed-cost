<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\ContractPayment;
use App\Models\Detail;
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




}
