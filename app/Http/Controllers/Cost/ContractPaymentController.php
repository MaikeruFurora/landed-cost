<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\ContractPayment;
use App\Models\PaymentDetail;
use App\Services\ContractPaymentService;
use Illuminate\Http\Request;

class ContractPaymentController extends Controller
{
   
    protected $contractPaymentService;
    
    public function __construct(ContractPaymentService $contractPaymentService)
    {
        $this->contractPaymentService   = $contractPaymentService;

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


}
