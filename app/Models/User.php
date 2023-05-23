<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable;

    use \OwenIt\Auditing\Auditable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'type',
        'other_prev',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type'=>'boolean',
        'other_prev'=>'array',
    ];

    public function setNameAttribute($value){
       return $this->attributes['name'] = ucwords($value);
    }

    public function privileges(){
        
        return $this->hasMany(Privilege::class);

    }

    public function myRights(){
        
        /* Returning the particular_id of the privileges table. */
        return Static::privileges()->pluck('particular_id')->toArray();

    }

    public function scopeStrictedUser($q){

        return $q->where('users.type',0);

    }

    public function accountingHead(){
        
        return count(Static::myRights())==Particular::count();

    }

    public function findOtherPrev($value){
        
        $op = auth()->user()->other_prev;

        if (is_array($op)) {
            return in_array($value,$op);
        }

        return false;

    }

    public function scopeUpdateUser($q,$request,$setPassword){

        return $q->find($request->id)->update([

            'name'      =>$request->name,

            'username'  =>$request->username,

            'email'     =>$request->email,

            'password'  =>$setPassword,

            'other_prev'=>$request->otherPrivelege ?? []

        ]);
    }

    public function scopeCreateUser($q,$request,$setPassword){

        return $q->create([

            'name'      => $request->name,

            'username'  => $request->username,

            'email'     => $request->email,

            'password'  => $setPassword,

            'other_prev'=> $request->otherPrivelege ?? []

        ]);
    }
}
