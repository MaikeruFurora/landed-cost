<?php

namespace App\Services;

use App\Models\Freight;
use App\Models\LandedcostParticular;

class FreightService{

    public function store($request,$landedCostParticular){
          
        if ($landedCostParticular->freight) {

            return $landedCostParticular->update([

                'amount'=>$request->totalFreight

            ]);

        }else{

            return $landedCostParticular->update([

                'amount'=>$request->totalFreight

            ]);

        }
          
    }


    public function freightStore($request,$landedCostParticular){

        if (is_null($request->input('id'))) {

          return $landedCostParticular->freight()->create(

                $this->requestInput($request)

          );
                       
        } else {
            
            return Freight::find($request->input('id'))->update(

                $this->requestInput($request)

            );

        }

        // return $landedCostParticular->update([

        //     'amount'=> $this->computeFreight($landedCostParticular->detail,$request)

        // ]);

    }

    // public function negoStore($request,$landedcostParticular){

        //     if(is_null($request->input('id'))){

        //         return $landedcostParticular->lcdpnego()->create(

        //             $this->negoRequestInput($request)

        //         );

        //     }else{

        //         return Lcdpnego::find($request->input('id'))->update(

        //             $this->negoRequestInput($request)

        //         );

        //     }
    // }

    private function computeFreight($data,$request){

        return floatval(preg_replace('/[^\d.]/', '', (($data->fcl*$request->freightExhangeRate)*$request->freightDollarRate)));

    }


    private function requestInput($request){

        return [

            'dollarRate'       => $request->dollarRate,
    
            'exhangeRate'      => $request->exhangeRate,

            'exhangeRateDate'  => $request->exhangeRateDate

        ];
       
    }

}