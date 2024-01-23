<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\LandedcostParticular;
use App\Models\Particular;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandedCostService{

    public function checkParticular($data){

                      $this->existsBrorageAndsInsurance();

        $from       = $data->landedcost_particulars->pluck('particular_id')->toArray();

        $to         = Particular::getParticularId();

        $particular = array_values(array_diff($to,$from));

        return $data->storeDetailParticular($particular);

    }

    public function updateAmntAndRef($data){

        // return $this->getInsuranceReference("EX2224000268");
        
        $arr = array();

        foreach ($data->landedcost_particulars as $key => $value) {

            if (in_array($value->particular->p_code,Helper::$getRefAmntCode)) {

                $arr[]=$value->id;

            }
            
        }

        
        foreach ($arr as $key => $value) {

            $landedparticular = LandedcostParticular::find($value);

            // return $this->getBrokerageReference($landedparticular->detail->invoiceno);

            if ($landedparticular->particular->p_code==Helper::$getRefAmntCode[0]) {

                //working
                // ((empty($landedparticular->amount) || $landedparticular->amount==0 || $landedparticular->amount!=$this->getBrokerageAmount($landedparticular->detail->invoiceno)) ? 
                //                 $this->getBrokerageAmount($landedparticular->detail->invoiceno) : $landedparticular->amount);


                $landedparticular->update([

                    // 'amount'=>      empty($landedparticular->detail->posted_at) ? $this->getBrokerageAmount($landedparticular->detail->invoiceno) : $landedparticular->amount,

                    'amount'    =>   ((empty($landedparticular->amount) || $landedparticular->amount==0) ?  $this->getBrokerageAmount($landedparticular->detail->invoiceno,$landedparticular->detail->sap) : $landedparticular->amount),

                    'referenceno'=>    
                    Str::limit(empty($landedparticular->detail->posted_at) ? $this->getBrokerageReference($landedparticular->detail->invoiceno,$landedparticular->detail->sap) : $landedparticular->referenceno, 50),

                ]);
                
            } else {

                if (!empty($this->getInsuranceReference($landedparticular->detail->invoiceno,$landedparticular->detail->sap))) {
                    
                    [$docDate,$docRef] = explode("_",$this->getInsuranceReference($landedparticular->detail->invoiceno,$landedparticular->detail->sap));
                    
                } else {
                    
                    $docDate=$docRef=NULL;
                    
                }
                
               /* Updating the amount,referenceno and transaction_date of the particular. */
                $landedparticular->update([

                    'amount'            => (empty($landedparticular->amount) || $landedparticular->amount==0) ? $this->getInsuranceAmount($landedparticular->detail->invoiceno,$landedparticular->detail->sap) ?? null: $landedparticular->amount,
                    
                    // 'amount'            => empty($landedparticular->detail->posted_at)  ? $this->getInsuranceAmount($landedparticular->detail->invoiceno) : $landedparticular->amount,

                    'referenceno'       => Str::limit(empty($landedparticular->detail->posted_at)  ? ($docRef ) : $landedparticular->referenceno, 50),
                    
                    'transaction_date'  => empty($landedparticular->detail->posted_at)  ? ($docDate) : $landedparticular->transaction_date

                ]);
            }
            
        }

    }

    public function existsBrorageAndsInsurance(){
        
        $d_ata = Particular::pluck('p_code')->toArray();

        foreach(Helper::$intact_particular as $key => $data){        

            if (!in_array($data['p_code'],$d_ata)) {

                $arr_merge = array_merge(Helper::$intact_particular[$key],['p_sort'=>Particular::lastSort()]);
                
                Particular::create($arr_merge);

            }

        }
        

    }

    public function getBrokerageAmount($invoice,$sap = NULL){

        return DB::select('exec dbo.sp_getBrokerage ?,?,?',array($invoice,'amount',$sap))[0]->amount;

    }

    public function getBrokerageReference($invoice,$sap = NULL){
        
        return DB::select('exec dbo.sp_getBrokerage ?,?,?',array($invoice,'reference',$sap))[0]->refno;

    }

    public function getInsuranceAmount($invoice,$sap = NULL){
        
        return DB::select("exec sp_getInsurance ?,?,?",array($invoice,'amount',$sap))[0]->amount ?? null;

    }

    public function getInsuranceReference($invoice,$sap = NULL){
        
        $getData =  DB::select("exec sp_getInsurance ?,?,?",array($invoice,'reference',$sap));

        return !empty($getData)?$getData[0]->docdate.'_'.$getData[0]->refno:'';

    }

    
}