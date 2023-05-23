<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class UserService{
    
    public function store($request){
        if(empty($request->id)){

            $setPassword = Hash::make($request->password);

        }else{

            $setPassword = empty($request->password)? User::find($request->id)->password: Hash::make($request->password);

        }      

        if(empty($request->id)){
            $user =  User::createUser($request,$setPassword);
        }else{
            User::updateUser($request,$setPassword);
            $user = User::find($request->id);
        }
           
  
        if($user->privileges()->exists()){

            $user->privileges()->delete();
            
        }

        if ($request->particular) {

            if(is_array($request->particular)){

                foreach($request->particular as $particular){
                   
                    $user->privileges()->create([
    
                        'particular_id'=>$particular
                    
                    ]);
                }
                
            }
                   
        }


        if(empty($request->id)){

            return redirect()->route('authenticate.user.create')->with([
                'msg'=>     'Successfully Saved',
                'action'=>  'success'
            ]);
            
        }else{

            return redirect()->route('authenticate.user.edit',$request->id)->with([
                'msg'=>     'Successfully Updated',
                'action'=>  'success'
            ]);

        }
       
    }

}