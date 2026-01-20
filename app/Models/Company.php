<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Crypt;

class Company extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $guarded=[];

    protected $casts=[
        'companystatus'=>'boolean'
    ];

    public function detail(){

        return $this->hasOne(Detail::class);

    }

    public function banks(){

        return $this->hasMany(Bank::class);
        
    }

    public function requestInput($request){

        return [
        
            'companyname'       => $request->input('companyname'),

            'acronym'           => $request->input('companyacronym'),
            
            'companystatus'     => $request->has('companystatus'),

            'companyAddress'    => $request->input('companyAddress'),

            'tinNo'             => $request->input('tinNo'),

            'registrationDate'  => $request->input('registrationDate'),

        ];

    }

    public function scopeSaveCompany($q,$request){
        
        return $q->create(

            $this->requestInput($request)

        );
    }

    public function scopeUpdateCompany($q,$request){
        
        return $q->find($request->id)->update($this->requestInput($request));
    }


     // Automatically encrypt before save
    public function setCompanynameAttribute($value)
    {
        $this->attributes['companyname'] = Crypt::encryptString($value);
    }

    // Automatically decrypt when reading
    public function getCompanynameAttribute($value)
    {
        return Crypt::decryptString($value);
    }

     // Automatically encrypt before save
    public function setAcronymAttribute($value)
    {
        $this->attributes['Acronym'] = Crypt::encryptString($value);
    }

    // Automatically decrypt when reading
    public function getAcronymAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
