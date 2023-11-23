<?php

namespace App\Services;

use App\Models\ContractPayment;
use App\Models\PaymentDetail;

class ContractPaymentService{

    public function list($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = ContractPayment::with(['invoice_payment','invoice_payment.invoice_pay_detail']);
    
        if (!empty($filter)) {
            $query
            ->orWhere('suppliername', 'like', '%'.$filter.'%')
            ->orWhere('description', 'like', '%'.$filter.'%')
            ->orWhere('reference', 'like', '%'.$filter.'%')
            ->orWhere('metricTon', 'like', '%'.$filter.'%')
            ->orWhere('priceMetricTon', 'like', '%'.$filter.'%')
            ->orWhere('contract_percent', 'like', '%'.$filter.'%')
            ->orWhere('amountUSD', 'like', '%'.$filter.'%')
            ->orWhere('paidAmountUSD', 'like', '%'.$filter.'%')
            ->orWhereHas('invoice_payment',function($q) use ($filter){
                // return $q->whereHas('invoice_pay_detail',function($query) use ($filter){
                    $q->where('reference','like', '%'.$filter.'%')
                        ->orwhere('invoiceno','like', '%'.$filter.'%')
                        ->orwhere('priceMetricTon','like', '%'.$filter.'%');
                // });
            }); 
        }
    
        $recordsTotal = $query->count();
    
    
        $query->take($length)->skip($start);

    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "id"                => $value->id,
                    "payment_detail"    => $value->payment_detail,
                    "invoice_payment"   => $value->invoice_payment,
                    "suppliername"      => $value->suppliername,
                    "description"       => $value->description,
                    "reference"         => $value->reference,
                    "invoiceno"         => $value->invoiceno,
                    "metricTon"         => $value->metricTon,
                    "priceMetricTon"    => $value->priceMetricTon,
                    "contract_percent"  => $value->contract_percent,
                    "amountUSD"         => number_format($value->amountUSD,2),
                    "paidAmountUSD"     => number_format($value->paidAmountUSD,2),
                ];
        }

        return $json;

    }

    public function listDetail($request,$contractPayment){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));


        $filter = $search['value'];
    
    
        // $query = PaymentDetail::with(['contract_payment']);

        $query  = PaymentDetail::with(['contract_payment'])->where('contract_payment_id',$contractPayment->id);
    
        if (!empty($filter)) {
            $query
            ->orWhere('exchangeDate', 'like', '%'.$filter.'%')
            ->orWhere('exchangeRate', 'like', '%'.$filter.'%')
            ->orWhere('dollar', 'like', '%'.$filter.'%')
            ->orWhere('totalAmountInPHP', 'like', '%'.$filter.'%')
            ->orWhere('totalPercentPayment', 'like', '%'.$filter.'%');
        }
    
        $recordsTotal = $query->count();
    
    
        $query->take($length)->skip($start);

    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "payment_detail"        => $value,
                    "contract_payment"      => $value->contract_payment,
                    "exchangeDate"          => $value->exchangeDate,2,
                    "exchangeRate"          => number_format($value->exchangeRate,2),
                    "dollar"                => number_format($value->dollar,2),
                    "totalAmountInPHP"      => number_format($value->totalAmountInPHP,2),
                    "totalPercentPayment"   => number_format($value->totalPercentPayment,2),
                ];
        }

        return $json;

    }

}