<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Helper;
use OwenIt\Auditing\Contracts\Auditable;

class Particular extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;
    
    protected $guarded=[];

    protected static $code;

    protected $casts = [
        'p_sort' => 'integer',
        'action' => 'boolean',
        'company' => 'boolean'
    ];
    public function scopeParticularStore($q,$request){

        return $q->create($this->inputRequest($request));

    }

    
    public function scopeParticularUpdate($q,$request){

        return $q->find($request->input('id'))->update($this->inputRequest($request));

    }

    public function inputRequest($request,){

        // if (!empty($request->input('id')) && in_array($request->input('code'), Helper::$except_code)) {
        //     $request->merge(['action'=>true]);
        // }

       return [ 
                'p_name' => $request->input('name'),

                'p_sort' => $request->input('sort') ?? $this->lastSort(),

                // 'action' => $request->input('action') ?? false,

                'p_code' => $request->input('code')
            ];

    }

    public static function lastSort(){

        // $d = Static::max('p_sort'); problem in sorting highest to lowest

        $dt     =   Static::get(['p_sort'])->pluck(['p_sort'])->toArray();

        $max    =   collect($dt)->max();

        return (!empty($max)) ? intval($max+1) :  1;

    }

    public function scopeGetparticular($q){

        return $q->orderBy('p_sort','ASC')->get(['id','p_name']);

    }

    public function scopeGetParticularId($q){

        $data = $q->get(['id'])->pluck(['id'])->toArray();

        $sorted = collect($data)->sort();

        return $sorted->values()->all();
    }


    public static function selfCreateParticular($array){

        return Static::create(
            array_merge($array,[
                'p_sort' => Static::lastSort(),
                'action' => true,
                'company' => false,
            ])
        );

    }

    public static function checkIfExists($code){
        
        return Static::where('p_code',$code)->first();

    }
}
