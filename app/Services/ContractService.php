<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\AdvancePayment;
use App\Models\Contract;

class ContractService{

    public function list($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));


        $filter = $search['value'];
    
        $sortColumns = array('contract_no','metricTon','priceMetricTon');
    
         $query = Contract::with(['lcdpnego:id,contract_id,percentage,amount,landedcost_particular_id,allocatedAmount','lcdpnego.landedcost_particular.detail:id,invoiceno,qtymt']);
    
        if (!empty($filter)) {
            $query
            ->where('contract_no', 'like', '%'.$filter.'%')
            ->orwhere('metricTon', 'like', '%'.$filter.'%')
            ->orwhere('priceMetricTon', 'like', '%'.$filter.'%');
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
                    "contract_no"       => $value->contract_no,
                    "metricTon"         => number_format($value->metricTon,2),
                    "priceMetricTon"    => $value->priceMetricTon,
                    "percentage"        => $value->percentage,
                    "exchangeRate"      => $value->exchangeRate,
                    "exchangeRateDate"  => $value->exchangeRateDate,
                    "amountUSD"         => number_format($value->amountUSD,2),
                    "paidAmountUSD"     => number_format($value->paidAmountUSD,2),
                    "amountPHP"         => number_format($value->amountPHP,2),
                    "lcdpnego"          => $value->lcdpnego,
                    "id"                => $value->id,
                ];
        }

        return $json;

    }
    
}