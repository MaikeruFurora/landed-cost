<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        'exchangeRate'          => 'double',
        'dollar'                => 'double',
        'totalPercentPayment'   => 'double',
        'totalAmountInPHP'      => 'double',
    ];

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
            'exchangeDate'        => $request->exchangeDate,
            'exchangeRate'        => Helper::cleanNumberByFormat($request->exchangeRate),
            'dollar'              => Helper::cleanNumberByFormat($request->dollar),
            'totalAmountInPHP'    => Helper::cleanNumberByFormat($request->totalAmountInPHP),
            'totalPercentPayment' => Helper::cleanNumberByFormat($request->totalPercentPayment),
        ];
    }
    
    
}
