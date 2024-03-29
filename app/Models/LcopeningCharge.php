<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LcopeningCharge extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $guarded =[];

    public function detail(){
        
        return $this->belongsTo(Detail::class);
        
    }

    public function open_amount(){
    
        return $this->belongsTo(OpenAmount::class);
    
    }


    public function freight(){
    
        return $this->hasOne(Freight::class);
    
    }

   
    
}
