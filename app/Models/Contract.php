<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        'metricTon' => 'double',
        'priceMetricTon' => 'double',
        'amountUSD' => 'double',
        'paidAmountUSD' => 'double',
        'percentage' => 'double',
        'exchangeRate' => 'double',
        'amountPHP' => 'double',
    ];

    public function advance_payment(){

        return $this->hasMany(AdvancePayment::class);

    }

    public function scopeStore($q,$request){

        return $q->create($this->requestInput($request));

    }

    public function scopeUpdateContract($q,$request){
        return $q->find($request->id)->update($this->requestInput($request));
    }

    private function requestInput($request){

        return [
            'contract_no'      => trim(strtoupper($request->input('contract_no'))),
            'metricTon'        => Helper::cleanNumberByFormat($request->input('metricTon')),
            'priceMetricTon'   => Helper::cleanNumberByFormat($request->input('priceMetricTon')),
            'amountUSD'        => Helper::cleanNumberByFormat($request->input('amountUSD')),
            'paidAmountUSD'    => Helper::cleanNumberByFormat($request->input('paidAmountUSD')),
            'percentage'       => Helper::cleanNumberByFormat($request->input('percentage')),
            'exchangeRate'     => Helper::cleanNumberByFormat($request->input('exchangeRate')),
            'exchangeRateDate' => $request->input('exchangeRateDate'),
            'amountPHP'        => Helper::cleanNumberByFormat($request->input('amountPHP')),
        ];

    }
    
}
