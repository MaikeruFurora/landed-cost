<?php

namespace App\Http\Controllers\Cost;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParticularRequest;
use App\Models\Detail;
use App\Models\Particular;
use App\Services\ParticularService;
use Illuminate\Http\Request;

class ParticularController extends Controller
{

    protected $particularService;

    public function __construct(ParticularService $particularService)
    {
        $this->particularService = $particularService;
    }
   
    public function index(){
        return view('users.admin.particular',[
            'data' => $this->particularService->getData()
        ]);
    }

    public function store(ParticularRequest $request){

        return $this->particularService->store($request);

    }

    public function sortOrder(Request $request){
       
        return $this->particularService->setOrder($request);
        
    }

    public function edit(Particular $particular){

        return response()->json($particular);

    }
}
