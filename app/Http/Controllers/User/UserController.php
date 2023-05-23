<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Particular;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userService;
    protected $authService;
    protected $dataPart;

    public function __construct(AuthService $authService,UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->dataPart = Particular::getparticular();
    }

    public function index(){
        return view('users.admin.user',[
            'users'=>User::strictedUser()->simplePaginate(5)
        ]);
    }

    public function create(){

        return view('users.admin.user-create',[

            'title'=>'Create User',

            'particular' => $this->dataPart->sortBy('p_sort', SORT_REGULAR, false)

        ]);
    }

    public function store(UserRequest $request){

        return $this->userService->store($request);

    }

    public function edit(User $user){
        $title='Edit User';
        $particular = $this->dataPart->sortBy('p_sort', SORT_REGULAR, false);
        return  view('users.admin.user-create',compact('user','title','particular'));
    }


    public function signOut(){
        
        return $this->authService->signOut();

    }

}