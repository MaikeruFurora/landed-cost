<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $casts=[
        'branchStatus' => 'boolean',
    ];

    public function bank(){

        return $this->belongsTo(Bank::class);

    }
    
    public function accounts(){

        return $this->hasMany(Account::class);

    }

    public function requestInput($request){

        return [
            
            'bank_id'       => $request->input('bank_id'),

            'branchName'    => $request->input('branchName'),

            'branchStatus'  => $request->has('branchStatus')

        ];

    }

    public function scopeStoreBranch($q,$request){

        return $q->create($this->requestInput($request));

    }

    public function scopeUpdateBranch($q,$request){

        return $q->find($request->branchId)->update($this->requestInput($request));

    }

    

}
