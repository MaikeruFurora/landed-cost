<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        
        'accountStatus' => 'boolean',
        
    ];

    public function branch(){

        return $this->belongsTo(Branch::class);

    }

    public function bankHistory(){

        return $this->hasMany(BankHistory::class);

    }

    public function telegraphicHistory(){

        return $this->hasMany(TelegraphicHistory::class);

    }
    
    public function requestInput($request){

        return [
            
            'branch_id'     => $request->input('branch_id'),

            'accountNo'     => $request->input('accountNo'),

            'currencyType'  => $request->input('currencyType'),

            'accountStatus' => true,
            // 'accountStatus' => $request->has('accountStatus')

        ];

    }

    public function scopeStoreAccount($q,$request){

        return $q->create($this->requestInput($request));

    }

    public function scopeUpdateAccount($q,$request){

        return $q->find($request->accountId)->update($this->requestInput($request));

    }
}
