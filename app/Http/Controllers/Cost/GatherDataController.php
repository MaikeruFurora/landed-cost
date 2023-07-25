<?php

namespace App\Http\Controllers\cost;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderRequest;
use App\Models\Particular;
use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GatherDataController extends Controller
{

    protected $dataService;
    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function index(Request $request){

        if (Helper::usrChckCntrl(['LC002'])) {
            return view('users.gather-data');
        }

        return view('users.default'); 
       
    }

    public function search(Request $request){

        return  $this->dataService->searchTerm($request);

    }

    // PurchaseOrderRequest

    public function storePO(PurchaseOrderRequest $request){


        $data =  $this->dataService->storePO($request);

        return $data;
        
        if ($data) {
            
            // return redirect()->route('authenticate.details')->with([
            return redirect()->back()->with([

                'msg'=>'Successfully saved #'.strtoupper(request()->input('invoiceno')),

                'action' =>'success'

            ]);

        } else {

            return redirect()->back()->with([

                'msg'=>'Something went wrong',

                'action' =>'danger',

                'icon' =>'<i class="fas fa-exclamation-triangle"></i>'

            ]);
            
        }
    }
}
