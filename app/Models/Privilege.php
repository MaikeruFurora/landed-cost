<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Privilege extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $guarded=[];

    
    public function users(){
        
        return $this->belongsTo(User::class);

    }

    public function particular(){

        return $this->belongsTo(Particular::class)->sortBy('p_sort', SORT_REGULAR, false);

    }
    
}
