<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Lcdpnego extends Model implements Auditable
{
    use HasFactory;
    
    use \OwenIt\Auditing\Auditable;

    protected $guarded =[];

    protected $casts=[
        'priceMetricTon'=>'double',
        'exchangeRate'=>'double',
        'percentage'=>'double',
        'amount'=>'double'
    ];

    public function landedcost_particular(){
        
        return $this->belongsTo(LandedcostParticular::class);
        
    }

    public function contract(){

        return $this->belongsTo(Contract::class);

    }
}
