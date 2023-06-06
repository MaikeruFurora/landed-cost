<?php

namespace App\Http\Controllers\Cost;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Exports\DutiesReport;
use App\Exports\DollarReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public $array = [];

    public function index(Request $request){

        // $data = Detail::whereBetween('created_at',[$request->start,$request->end])->get();

        if (Helper::usrChckCntrl(['RP001'])) {

            $companies = Company::get(['id','companyname']);

            return view('users.reports.report',compact('companies'));
        }

        return view('users.default'); 
      

    }

    public function filter(Request $request){
        
        Helper::auditLog('Generate Data (Report)','Generate Data (Report)');

        return $this->reportService->filterService($request);
        
    }
    
    public function searchItem(Request $request){

        $data =  $this->reportService->searchTerm($request);

        return response()->json($data);

    }

    public function print(Request $request){

        $res =  $this->reportService->filterService($request);

        Helper::auditLog('Print Preview','Print Preview');

        return view('users.reports.report-print',[

            'data'  => $res,

            'item'  => $request->item,

            'start' => $request->start,

            'end'   => $request->end,

        ]);

    }

    public function exportReport(Request $request){
        
        Helper::auditLog('Export Data(Report)','Export Data(Report)'.$request->type);

        switch ($request->type) {
            case 'dutiesReport':
                // return DB::select("exec dbo.sp_getDutiesReport ?,?,?",array($request->from,$request->to,$request->company_id));
                return Excel::download(new DutiesReport($request->from,$request->to,$request->company_id),
                            'Duties Report - '.date("F_d_Y",strtotime($request->from)).'-'.date("F_d_Y",strtotime($request->to)).'.xlsx'
                        );
                break;
            case 'dollarReport':
                // return DB::select("exec dbo.sp_getDollarReport ?,?,?",array($request->from,$request->to,$request->company_id));
                return Excel::download(new DollarReport($request->from,$request->to,$request->company_id),
                            'Dollar Report - '.date("F_d_Y",strtotime($request->from)).'-'.date("F_d_Y",strtotime($request->to)).'.xlsx'
                        );
                break;
            
            default:
                return false;
                break;
        }
    
    }

}
