<?php

namespace App\Services;

use App\Models\Detail;

class DetailService{


    public function dataList($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));
    
        $filter = $search['value'];
    
        $sortColumns = array(
           'updated_at'
        );
    
        $query = Detail::select(['id','pono','vessel','description','suppliername','invoiceno','posted_at','broker','quantity','qtykls','qtymt','fcl','created_at','updated_at','blno']);
    
        if (!empty($filter)) {
            $query
            ->where('invoiceno', 'like', '%'.$filter.'%')
            ->orwhere('vessel', 'like', '%'.$filter.'%')
            ->orwhere('pono', 'like', '%'.$filter.'%')
            ->orwhere('blno', 'like', '%'.$filter.'%')
            ->orwhere('suppliername', 'like', '%'.$filter.'%')
            ->orwhere('description', 'like', '%'.$filter.'%');
        }
    
        $recordsTotal = $query->count();
    
        $sortColumnName = $sortColumns[$order[0]['column']];
    
        $query->take($length)->skip($start);

        if($draw==1){
            $query->orderBy($sortColumnName, $order[0]['dir']);
        }
    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();
        
        // usort($products, function($a,$b){
        //     return strtotime($a->updated_at) > strtotime($b->updated_at);
        // });

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "res"         => $value,
                    "pono"       => $value->pono,
                    // "itemcode"   => $value->itemcode,
                    "vessel"     => $value->vessel,
                    "description"=> $value->description,
                    "invoiceno"  => $value->invoiceno,
                    "blno"       => $value->blno,
                    "broker"     => $value->broker,
                    "quantity"   => $value->quantity,
                    "qtykls"     => $value->qtykls,
                    "qtymt"      => $value->qtymt,
                    "fcl"        => $value->fcl,
                    "id"         => $value->id,
                    "updated_at" => strtotime($value->updated_at),
                ];
        }

        return $json;
    }

}