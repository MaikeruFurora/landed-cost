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
            ->where('suppliername', 'like', '%'.$filter.'%')
            ->where('description', 'like', '%'.$filter.'%')
            ->where('reference', 'like', '%'.$filter.'%')
            ->where('metricTon', 'like', '%'.$filter.'%')
            ->where('priceMetricTon', 'like', '%'.$filter.'%')
            ->where('contract_percent', 'like', '%'.$filter.'%')
            ->where('amountUSD', 'like', '%'.$filter.'%')
            ->where('paidAmountUSD', 'like', '%'.$filter.'%');
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
            ->where('exchangeDate', 'like', '%'.$filter.'%')
            ->where('exchangeRate', 'like', '%'.$filter.'%')
            ->where('dollar', 'like', '%'.$filter.'%')
            ->where('totalAmountInPHP', 'like', '%'.$filter.'%')
            ->where('totalPercentPayment', 'like', '%'.$filter.'%');
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