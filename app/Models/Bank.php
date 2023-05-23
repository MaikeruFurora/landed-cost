<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function company(){

        return $this->belongsTo(Company::class);

    }

    public function branches(){

        return $this->hasMany(Branch::class);

    }

    private function requestInput($request){

        return [

            'company_id'    =>  $request->input('company_id'),
            
            'acronym'       =>  $request->input('acronym'),

            'bankName'      =>  $request->input('bankName'),

        ];

    }

    public function scopeStoreBank($q,$request){

        return $q->create($this->requestInput($request));

    }

    public function scopeUpdateBank($q,$request){

        return $q->find($request->bank_id)->update($this->requestInput($request));

    }

    

}
