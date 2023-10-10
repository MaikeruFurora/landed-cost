<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        'priceMetricTon'    => 'double',
        // 'contract_percent'  => 'double',
        'amountUSD'         => 'double',
        'paidAmountUSD'     => 'double',
    ];

    public function payment_detail(){
        return $this->hasMany(PaymentDetail::class);
    }

    public function invoice_payment(){
        return $this->hasMany(InvoicePayment::class);
    }

    public function scopeStorePayment($q,$request){
        return Static::create($this->requestInput($request));
    }

    public function scopeUpdatePayment($q,$request){
        return Static::find($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        return [
            'suppliername'      => $request->suppliername,
            'reference'         => $request->reference,
            'description'       => $request->description,
            'contract_percent'  => $request->contract_percent,
            'metricTon'         => Helper::cleanNumberByFormat($request->metricTon),
            'priceMetricTon'    => Helper::cleanNumberByFormat($request->priceMetricTon),
            'amountUSD'         => Helper::cleanNumberByFormat($request->amountUSD),  
            'paidAmountUSD'     => Helper::cleanNumberByFormat($request->paidAmountUSD),
        ];
    }


}