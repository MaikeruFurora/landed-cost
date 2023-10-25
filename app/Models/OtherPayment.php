<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPayment extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function invoice_payment(){
        return $this->belongsTo(InvoicePayment::class);
    }
    

    public function scopeStorePayment($q,$request){
        return Static::create($this->requestInput($request));
    }

    public function scopeUpdatePayment($q,$request){
        return Static::find($request->id)->update($this->requestInput($request));
    }

    public function requestInput($request){
        return [
            'invoice_payment_id'  => $request->invoice_payment,
            'exchangeDate'        => $request->exchangeDate,
            'particular'          => $request->particular,
            'exchangeRate'        => Helper::cleanNumberByFormat($request->exchangeRate),
            'quantity'            => Helper::cleanNumberByFormat($request->quantity),
            'dollar'              => Helper::cleanNumberByFormat($request->dollar),
            'totalAmountInPHP'    => Helper::cleanNumberByFormat($request->totalAmountInPHP),
        ];
    }

}
