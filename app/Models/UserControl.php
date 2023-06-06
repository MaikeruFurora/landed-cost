<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserControl extends Model
{
    use HasFactory;

    public function sub_controls(){

        return $this->hasMany(UserControl::class,'sub_control');

    }

    public function user_access(){

        return $this->hasMany(UserAccess::class);

    }
    

}
