<?php

namespace App\Models;

use App\Helper\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function detail(){
        
        return $this->belongsTo(Detail::class);
        
    }

    public function contract(){

        return $this->belongsTo(Contract::class);

    }

}
