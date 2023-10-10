<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function contract_payment(){
        return $this->belongsTo(ContractPayment::class);
    }

    public function scopeStorePayment($q,$request){
        return Static::create($this->requestInput($request));
    }

    public function scopeUpdatePayment($q,$request){
        return Static::find($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        return [
          
            'contract_payment_id' => $request->contract_payment,
            'reference'           => $request->reference,
            'metricTon'           => Helper::cleanNumberByFormat($request->metricTon),
            'priceMetricTon'      => Helper::cleanNumberByFormat($request->priceMetricTon),
            'amountUSD'           => Helper::cleanNumberByFormat($request->amountUSD),
        ];
    }

}
