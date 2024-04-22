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
            $affectedRows = DB::select('exec dbo.sp_unlockSAP');
            // Check the number of affected rows
            if ($affectedRows > 0) {
                // Data updated successfully
                echo "Data updated successfully. $affectedRows row(s) affected.";
            } else {
                // No changes made
                echo "No changes made.";
            }
        } catch (\Throwable $th) {
            echo "Done";
        }
    }
}
