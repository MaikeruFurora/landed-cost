<?php

namespace App\Services;

use App\Models\LandedcostParticular;
use App\Helper\Helper;
use App\Models\OpenAmount;
use App\Models\Particular;

class OpenChargeService{

    public function store($request,$landedCostParticular){

        return $landedCostParticular->update([

            'amount'=> $this->computeLcOpenCharge($landedCostParticular->detail,$request)

        ]);

    }

    public function computeLcOpenCharge($data,$request){

        return floatval(preg_replace('/[^\d.]/', '', (($data->qtymt/$request->lc_mt)*$request->lc_amount)));

    }

    public function checkFirstParticular(){

        $data = Particular::checkIfExists(Helper::$intact_particular[0]['p_code']);

        if($data){
        
            return $data;
                
        }
        
        return Particular::selfCreateParticular(Helper::$intact_particular[0]);

    }

    public function invoiceList($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));        
    
        $filter = $search['value'];
    
        $sortColumns = array(
            'updated_at'
        );
    
        $query = OpenAmount::with(['lcopening_charge:id,open_amount_id,detail_id','lcopening_charge.detail:id,invoiceno,qtymt']);
    
        if (!empty($filter)) {
            $query
            ->where('open_amounts.lc_amount', 'like', '%'.$filter.'%')
            ->orwhere('open_amounts.lc_mt', 'like', '%'.$filter.'%')
            ->orwhere('open_amounts.lc_reference', 'like', '%'.$filter.'%');
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
    
        $openAmount = $query->get();
    
        foreach ($openAmount as $value) {
    
            $json['data'][] = [
                "lc_amount"       => number_format($value->lc_amount,2),
                "lc_mt"           => $value->lc_mt,
                "lc_reference"    => $value->lc_reference,
                "invoice_amount"  => $value->lc_reference,
                "lcopening_charge"=> $value->lcopening_charge,
                "id"              => $value->id,
                "updated_at"      => strtotime($value->updated_at),
            ];
        }

        return $json;
    }

   

}