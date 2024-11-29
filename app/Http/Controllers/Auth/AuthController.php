<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {

        return $this->authService = $authService;

    }

    public function loginPost(LoginRequest $request){

        return $this->authService->login($request);

    }

    public function signOut(){

        return $this->authService->signOut();
        
    }

    public function unlockSap(){
        try {
            // Execute the stored procedure
            DB::statement('exec dbo.sp_unlockSAP');
            // If execution is successful, this block will run
            return view('auth.unlocked');
        } catch (\Throwable $th) {
            // If there's an error, this block will run
            echo "An error occurred: " . $th->getMessage();
        }
    }
}
