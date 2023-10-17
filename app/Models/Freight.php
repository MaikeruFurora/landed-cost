<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freight extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        
        'dollarRate'      => 'double',

        'exchangeRate'     => 'double',

        //'exhangeRateDate' => 'date'

    ];

    // public function setExhangeRateDateAttribute( $value ) {

    //     $this->attributes['exhangeRateDate'] = (new Carbon($value))->format('m/d/Y');

    // }

    public function landedcost_particular(){
        
        return $this->belongsTo(LandedcostParticular::class);
        
    }

    public function scopeStoreFreight($q, $request){

        return $q->create($this->requestInput($request));

    }

    public function scopeUpdateFreight($q,$request){

        return $q->find($request->idFreight)->update($this->requestInput($request));

    }

    public function requestInput($request){

        return [

            'landedcost_particular_id'=> $request->id,

            'dollarRate'              => $request->freightDollarRate,

            'exchangeRate'             => $request->freightExhangeRate,

            'exchangeRateDate'         => $request->freightExhangeRateDate

        ];
    }

    public function landedcost_particulars(){

        return $this->belongsTo(LandedcostParticular::class);

    }
    

}
