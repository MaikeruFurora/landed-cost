<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\LandedcostParticular;
use App\Models\Lcdpnego;
use App\Models\Particular;
use Symfony\Component\Mailer\Transport\Dsn;

class NegoService{
    
    public function store($request,$landedCostParticular){

        // return $landedCostParticular->lcdpnego->sum();
          
        if ($landedCostParticular->lcdpnego) {

            // $landedCostParticular->lcdpnego()->update($this->requestNego($request));

            return $landedCostParticular->update([

                'amount' => Helper::cleanNumberByFormat($request->totalNego)

            ]);

        }else{

            // $landedCostParticular->lcdpnego()->create($this->requestNego($request));

            return $landedCostParticular->update([

                'amount' => Helper::cleanNumberByFormat($request->totalNego)

            ]);

        }
          
    }

    public function autoCompute($data){

        $t1=$t2=0;
        
        foreach ($data->lcdpnego as $key => $value) {

            $t1 = $data->detail->qtymt*($value->priceMetricTon*$value->exchangeRate);

            $t2 += $t1;
        }

        return $t2;

    }

    public function totalAmount($data,$request){

        return (($data->detail->qtymt * $request->priceMetricTon) * $request->exchangeRate);
        
    }

    public function negoStore($request,$landedcostParticular){

        if(is_null($request->input('id'))){

            return $landedcostParticular->lcdpnego()->create(

                $this->negoRequestInput($request)

            );

        }else{

            return Lcdpnego::find($request->input('id'))->update(

                $this->negoRequestInput($request)

            );

        }
    }

    public function negoRequestInput($request){
        return [

            'priceMetricTon'    => $request->input('priceMetricTon'),

            'exchangeRate'      => Helper::cleanNumberByFormat($request->input('exchangeRate')),

            'exchangeRateDate'  => $request->input('exchangeRateDate'),

            'percentage'        => Helper::cleanNumberByFormat($request->input('percentage')),

            'amount'            => Helper::cleanNumberByFormat($request->input('amount')),

            'prices'            => $request->input('prices'),
           # 'prices'            => is_array($request->input('prices'))?(array_map(fn($value): int => str_replace(',', '', $value),$request->input('prices'))):NULL,

        ];
    }

    public function checkFirstParticular(){


        $data = Particular::checkIfExists(Helper::$intact_particular[1]['p_code']);

        if($data){
        
            return $data;
                
        }
        
        return Particular::selfCreateParticular(Helper::$intact_particular[1]);

    }

}

   