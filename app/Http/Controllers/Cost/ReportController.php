<?php

namespace App\Http\Controllers\Cost;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Exports\DutiesReport;
use App\Exports\DollarReport;
use App\Exports\FundReport;
use App\Exports\MultipleSheet\DollarBookReportParticularMultiSheet;
use App\Exports\MultipleSheet\ProjectedCostReportMultiSheet;
use App\Exports\ProjectedCostReport;
use App\Exports\ProjectedCostReportM;
use App\Models\Particular;
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

    public function searchSupplier(Request $request){

        $data =  $this->reportService->searchSupplier($request);
        // $data[0]=["suppliername" => "ALL"];

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

            case 'fundReport':
                // return DB::select("exec dbo.sp_getDollarReport ?,?,?",array($request->from,$request->to,$request->company_id));
                return Excel::download(new FundReport($request->from,$request->to,$request->company_id),
                            'Fund Report - '.date("F_d_Y",strtotime($request->from)).'-'.date("F_d_Y",strtotime($request->to)).'.xlsx'
                        );
                break;

            case 'projectedCostReport':
                if ($request->itemName!='All') { 
                    return Excel::download(new ProjectedCostReport($request->from,$request->to,$request->itemName),
                        'Projected Cost Report - '.date("F_d_Y",strtotime($request->from)).'-'.date("F_d_Y",strtotime($request->to)).'.xlsx'
                    );
                }else{
                    return Excel::download(new ProjectedCostReportMultiSheet($request->from,$request->to),
                        'Projected Cost Report - '.date("F_d_Y",strtotime($request->from)).'-'.date("F_d_Y",strtotime($request->to)).'.xlsx'
                    );
                }

                    
                break;
            case 'dollarBook':
                    return Excel::download(new DollarBookReportParticularMultiSheet($request->from,$request->to),
                        'Dollar Expense'.'.xlsx'
                    );
                    // return DB::select("exec dbo.sp_getPivotTabSheet ?,?",array($request->from,$request->to));
                    break;
            
            default:
                return false;
                break;
        }
    
    }

    public function projectedCostList(Request $request){

        $particulars = Particular::get(['id','p_name']);#->sortBy('p_sort', SORT_REGULAR, false);
        return view('users.reports.projected-cost-list',compact('particulars'));

    }

}
