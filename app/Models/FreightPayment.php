<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreightPayment extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        'exchangeRate'      => 'double',
        'dollar'            => 'double',
        'totalAmountInPHP'  => 'double',
    ];

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
            'exchangeDate'      => $request->exchangeDate,
            'exchangeRate'      => Helper::cleanNumberByFormat($request->exchangeRate),
            'quantity'          => Helper::cleanNumberByFormat($request->quantity),
            'dollar'            => Helper::cleanNumberByFormat($request->dollar),  
            'totalAmountInPHP'  => Helper::cleanNumberByFormat($request->totalAmountInPHP),
        ];
    }
}
