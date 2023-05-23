<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\LandedcostParticular;
use App\Models\Particular;
use Illuminate\Support\Facades\DB;

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

                    'amount'=> $this->getBrokerageAmount($landedparticular->detail->invoiceno),

                    'referenceno'=> empty($landedparticular->referenceno) ? $this->getBrokerageReference($landedparticular->detail->invoiceno) : $landedparticular->referenceno,

                ]);
                
            } else {

                if (!empty($this->getInsuranceReference($landedparticular->detail->invoiceno))) {
                    
                    [$docDate,$docRef] = explode("_",$this->getInsuranceReference($landedparticular->detail->invoiceno));
                    
                } else {
                    
                    $docDate=$docRef=NULL;
                    
                }
                
               /* Updating the amount,referenceno and transaction_date of the particular. */
                $landedparticular->update([

                    #'amount'            => (empty($landedparticular->amount) || $landedparticular->amount==0) ? $this->getInsuranceAmount($landedparticular->detail->invoiceno) ?? null: $landedparticular->amount,
                    
                    'amount'            =>  $this->getInsuranceAmount($landedparticular->detail->invoiceno) ?? null,

                    'referenceno'       => empty($landedparticular->referenceno)      ? ($docRef ) : $landedparticular->referenceno,
                    
                    'transaction_date'  => empty($landedparticular->transaction_date) ? ($docDate) : $landedparticular->transaction_date

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

    public function getBrokerageAmount($invoice){

        return DB::select('exec dbo.sp_getBrokerage ?,?',array($invoice,'amount'))[0]->amount;

    }

    public function getBrokerageReference($invoice){
        
        return DB::select('exec dbo.sp_getBrokerage ?,?',array($invoice,'reference'))[0]->refno;

    }

    public function getInsuranceAmount($invoice){
        
        return DB::select("exec sp_getInsurance ?,?",array($invoice,'amount'))[0]->amount ?? null;

    }

    public function getInsuranceReference($invoice){
        
        $getData =  DB::select("exec sp_getInsurance ?,?",array($invoice,'reference'));

        return !empty($getData)?$getData[0]->docdate.'_'.$getData[0]->refno:'';

    }

    
}