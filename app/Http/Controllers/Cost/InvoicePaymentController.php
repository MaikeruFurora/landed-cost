<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\ContractPayment;
use App\Models\InvoicePayDetail;
use App\Models\InvoicePayment;
use App\Services\InvoicePaymentService;
use Illuminate\Http\Request;

class InvoicePaymentController extends Controller
{

    protected $invoicePaymentService;
    
    public function __construct(InvoicePaymentService $invoicePaymentService)
    {
        $this->invoicePaymentService              = $invoicePaymentService;
    } 

    public function store(Request $request){

        $data = (empty($request->id)) 

        ? InvoicePayment::storePayment($request)

        : InvoicePayment::updatePayment($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request,ContractPayment $contractPayment){
        return $this->invoicePaymentService->list($request,$contractPayment);
    }

    public function remove(InvoicePayment $invoicePayment){
        return $invoicePayment->delete();
    }

    public function storeInvoiceDetail(Request $request){


        $data = (empty($request->id)) 

        ? InvoicePayDetail::storePayment($request)

        : InvoicePayDetail::updatePayment($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }

    }

    public function listInvoiceDetail(Request $request,InvoicePayment $invoicePayment){
        return $this->invoicePaymentService->listDetail($request,$invoicePayment);        
    }
   
}
