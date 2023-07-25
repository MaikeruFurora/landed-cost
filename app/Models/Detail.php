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
            'pono'          => $request->input('pono'),
            'itemcode'      => $request->input('itemcode'),
            'cardname'      => $request->input('cardname'),
            'cardcode'      => $request->input('cardcode'),
            'actualQtyKLS'  => $request->input('actualQtyKLS'),
            'actualQtyMT'   => $request->input('actualQtyMT'),
            'vessel'        => $request->input('vessel'),
            'description'   => $request->input('description'),
            'invoiceno'     => $request->input('invoiceno'),
            'broker'        => $request->input('broker'),
            'weight'        => $request->input('weight'),
            'quantity'      => $request->input('quantity'),
            'qtykls'        => $request->input('qtykls'),
            'qtymt'         => $request->input('qtymt'),
            'fcl'           => $request->input('fcl'),
            'suppliername'  => $request->input('suppliername'),
            'blno'          => $request->input('blno'),
            'doc_date'      => $request->input('doc_date'),
            'posted_at'     => $request->input('posted_at'),
            'company_id'    => $request->input('selectCompany'),
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
