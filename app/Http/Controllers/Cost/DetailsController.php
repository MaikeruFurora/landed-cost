<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\OpenAmount;
use App\Helper\Helper;
use App\Services\DetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Facades\Auditor;

class DetailsController extends Controller
{

    protected $detailService;
    protected $auditor;

    public function __construct(DetailService $detailService,Auditor $auditor)
    {
        $this->detailService = $detailService;
        $this->auditor = $auditor;
    }

    public function index(){


        if (Helper::usrChckCntrl(['LC001'])) {

            $data =  Detail::orderBy('id','desc')->get([
    
                'id','pono','itemcode','cardname','cardcode','vessel','description','posted_at',
    
                'invoiceno','broker','weight','quantity','qtykls','qtymt','fcl','created_at'
    
            ]);
            return view('users.details.details',[
                
                'data'=>$data
                
            ]);

        }

        return view('users.default'); 

    }

    public function dataInvoice(Request $request){
        
        return $this->detailService->dataList($request);

    }

    public function create(){

        return view('users.details.details-create');

    }

    public function store(Request $request){

        return Detail::updateInvoice($request);

    }

    public function postInvoice(Request $request){

        if (is_array($request->invoice)) {

            foreach ($request->invoice as $key => $value) {

                Detail::find($value)->update(['posted_at'=>now()]);

            }

        } else {

            $detail = Detail::find($request->invoice);

            $detail->posted_at = empty($detail->posted_at) ? now() : NULL;

            // if ($audit = Auditor::execute($detail)) {
            //     Auditor::prune($detail);
            // }

           return $detail->save();

        }
        
    }

}