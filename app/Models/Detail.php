<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Detail extends Model implements Auditable
{
    use HasFactory;
    
    use \OwenIt\Auditing\Auditable;

    protected $guarded=[];

    protected $casts=[
        'quantity'=>'double',
        'qtykls'=>'double',
        'qtymt'=>'double',
    ];
    
    public function scopeStoreInvoice($q,$request){
        return $q->create($this->inputRequest($request));
    }

    public function scopeUpdateInvoice($q,$request){
        return $q->find($request->id)->update($this->inputRequest($request));
    }


    public function inputRequest($request){
       return [
            'pono'          => $request['pono'],
            'itemcode'      => $request['itemcode'],
            'cardname'      => $request['cardname'],
            'cardcode'      => $request['cardcode'],
            'actualQtyKLS'  => $request['actualQtyKLS'] ?? NULL,
            'actualQtyMT'   => $request['actualQtyMT'] ?? NULL,
            'vessel'        => $request['vessel'],
            'description'   => $request['description'],
            'invoiceno'     => $request['invoiceno'],
            'broker'        => $request['broker'],
            'weight'        => $request['weight'],
            'quantity'      => $request['quantity'],
            'qtykls'        => $request['qtykls'],
            'qtymt'         => $request['qtymt'],
            'fcl'           => $request['fcl'],
            'suppliername'  => $request['suppliername'],
            'blno'          => $request['blno'],
            'doc_date'      => $request['doc_date'],
            'posted_at'     => $request['posted_at'] ?? NULL,
            'company_id'    => $request['selectCompany'] ?? NULL,
            'sap'           => $request['sap'],
       ];
    }

    public function landedcost_particulars(){

        return $this->hasMany(LandedcostParticular::class);

    }

    public function scopeStoreDetailParticular($q,$particular){
        
        foreach($particular as $id){
            Static::landedcost_particulars()->create([
                'particular_id' =>$id,
            ]);
        }

    }

    public function lcopeningcharges(){

        return $this->hasOne(Lcopeningcharge::class);
    
    }

    public function advance_payment(){

        return $this->hasOne(Lcopeningcharge::class);
    
    }

    public function company(){

        return $this->belongsTo(Company::class);

    }

    public function item(){

        return $this->hasMany(Item::class);

    }

}
