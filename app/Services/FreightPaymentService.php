<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\FreightPayment;
use App\Models\Particular;

class FreightPaymentService{

    public function list($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));


        $filter = $search['value'];
    
    
         $query = FreightPayment::select("freight_payments.id",
         "freight_payments.suppliername",
         "freight_payments.description",
         "reference","exchangeRate","exchangeDate","freight_payments.quantity","dollar","totalAmountInPHP","details.invoiceno","details.id as invoice"
         )->leftjoin('details','freight_payments.reference','details.invoiceno');
    
        if (!empty($filter)) {
            $query
            ->where('suppliername', 'like', '%'.$filter.'%')
            ->where('description', 'like', '%'.$filter.'%')
            ->where('reference', 'like', '%'.$filter.'%')
            ->where('exchangeRate', 'like', '%'.$filter.'%')
            ->where('exchangeDate', 'like', '%'.$filter.'%')
            ->where('quantity', 'like', '%'.$filter.'%')
            ->where('dollar', 'like', '%'.$filter.'%')
            ->where('totalAmountInPHP', 'like', '%'.$filter.'%');
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
                    "invoice"           => $value->invoice,
                    "suppliername"      => $value->suppliername,
                    "description"       => $value->description,
                    "reference"         => $value->reference,
                    "exchangeDate"      => $value->exchangeDate,
                    "refernce"          => $value->refernce,
                    "exchangeRate"      => number_format($value->exchangeRate,2),
                    "quantity"          => number_format($value->quantity,2),
                    "dollar"            => number_format($value->dollar,2),
                    "totalAmountInPHP"  => number_format($value->totalAmountInPHP,2),
                ];
        }

        return $json;

    }

    public function checkFirstParticular(){


        $data = Particular::checkIfExists(Helper::$intact_particular[4]['p_code']);

        if($data){
        
            return $data;
                
        }
        
        return Particular::selfCreateParticular(Helper::$intact_particular[4]);

    }

}