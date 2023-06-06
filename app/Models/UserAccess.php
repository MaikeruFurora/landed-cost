<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user(){

        return $this->belongsTo(User::class);

    }

    public function user_control(){

        return $this->belongsTo(UserControl::class);

    }

}
