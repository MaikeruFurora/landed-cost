<?php

namespace App\Services;

use App\Models\Particular;
use Illuminate\Http\Request;

class ParticularService{


    public function store($request){


        if ($request->input('strOrpdt')=="store") {

            $data =  Particular::ParticularStore($request);
            
        } else {

            $data = Particular::ParticularUpdate($request);
            
        }
        

        if($data){
            return back()->with([

                'msg'=>     'Successfully Saved',

                'action'=>  'success'

            ]);

        }else{

            return back()->with([

                'msg'=>     'Something went wrong!',

                'action'=>  'warning'

            ]);
            
        }
        
    }

    public function getData(){

        $data =  Particular::orderBy('p_sort', 'ASC')->get(['id','p_name','p_sort','p_code','action','p_active']);

        return $data->sortBy('p_sort', SORT_REGULAR, false);
    }

    public function setOrder($request){
            
        $i = 1;

        foreach ($request->input('item') as $id) {

            Particular::whereId($id)->update(['p_sort'=>$i]);
          
            $i++;

        }

    }

}