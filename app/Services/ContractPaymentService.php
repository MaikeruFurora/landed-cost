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
    
    
         $query = ContractPayment::select([
            'id',
            'suppliername',
            'reference',
            'totalmt',
            'mtprice',
            'totalprice',
         ]);
    
        if (!empty($filter)) {
            $query
            ->where('suppliername', 'like', '%'.$filter.'%')
            ->where('reference', 'like', '%'.$filter.'%')
            ->where('totalmt', 'like', '%'.$filter.'%')
            ->where('mtprice', 'like', '%'.$filter.'%')
            ->where('totalprice', 'like', '%'.$filter.'%');
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
                    "id"           => $value->id,
                    "suppliername" => $value->suppliername,
                    "reference"    => $value->reference,
                    "totalmt"      => number_format($value->totalmt,2),
                    "mtprice"      => number_format($value->mtprice,2),
                    "totalprice"   => number_format($value->totalprice,2),
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
            ->where('metricTon', 'like', '%'.$filter.'%');
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
                    "payment_detail"    =>$value,
                    "contract_payment"  => $value->contract_payment,
                    "exchangeDate"      => $value->exchangeDate,2,
                    "exchangeRate"      => number_format($value->exchangeRate,2),
                    "metricTon"         => number_format($value->metricTon,2),
                ];
        }

        return $json;

    }

}