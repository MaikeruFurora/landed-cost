<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class OpenAmount extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;
    
    protected $guarded=[];

    protected $casts=[
        'lc_reference' => 'string',
        'lc_amount' => 'double',
        'lc_mt' => 'double'
    ];

    public function lcopening_charge(){

        return $this->hasMany(LcopeningCharge::class);

    }

    public function scopeStore($q,$request){
        return $q->create($this->requestInput($request));
    }

    public function scopeUpdateAmount($q,$request){
        return $q->find($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){

        return [
            
            'lc_reference'      => strtoupper($request->input('reference')),

            'transaction_date'  => $request->input('transaction_date'),

            'lc_amount'         => Helper::cleanNumberByFormat($request->input('amount')),

            'lc_mt'             => Helper::cleanNumberByFormat($request->input('mt'))
        
        ];

    }

}
