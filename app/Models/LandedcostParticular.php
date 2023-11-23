<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LandedcostParticular extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $guarded=[];

    protected $casts=[
        'detail_id'=>'integer',
        'particular_id'=>'integer',
    ];

    public function detail(){

        return $this->belongsTo(Detail::class);

    }

    public function particular(){

        return $this->belongsTo(Particular::class);

    }

    public function lcdpnego(){

        return $this->hasMany(Lcdpnego::class);

    }

    public function freight(){

        return $this->hasMany(Freight::class);

    }

    public function scopeCheckExistInvoiceAndParticular($q,$detail,$particular){
        
        return $q->where('detail_id',$detail)->where('particular_id',$particular);
        
    }
    

}