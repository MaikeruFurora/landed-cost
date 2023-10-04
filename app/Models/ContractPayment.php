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
        'totalmt' => 'double',
        'mtprice' => 'double',
        'totalprice' => 'double',
    ];

    public function payment_detail(){
        return $this->hasMany(PaymentDetail::class);
    }

    public function scopeStorePayment($q,$request){
        return Static::create($this->requestInput($request));
    }

    public function scopeUpdatePayment($q,$request){
        return Static::find($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        return [
            'suppliername' => $request->suppliername,
            'reference'    => $request->reference,
            'totalmt'      => Helper::cleanNumberByFormat($request->totalmt),
            'mtprice'      => Helper::cleanNumberByFormat($request->mtprice),
            'totalprice'   => Helper::cleanNumberByFormat($request->totalprice),
        ];
    }


}