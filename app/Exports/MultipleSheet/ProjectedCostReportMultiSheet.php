<?php

namespace App\Exports\MultipleSheet;

use App\Exports\ProjectedCostReportM;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectedCostReportMultiSheet implements WithMultipleSheets
{
    public $from;

    public $to;

    public function __construct(String $from,String $to)
    {
        
        $this->from = $from;

        $this->to = $to;

    }

    public function sheets(): array
    {
        $sheets=[];

        $data = DB::select("exec dbo.sp_getlcTabsheetReport ?,?,?",array($this->from,$this->to,'ALL'));
        $coll = collect($data);
        $res =  $coll->groupBy('description');
        $key  = collect($data)->unique(['description'])->pluck('description');
        for ($i=0; $i <count($key) ; $i++) { 
           $sheets[]= new ProjectedCostReportM($res[$key[$i]],$this->from,$this->to,$key[$i]);
        }

        return $sheets;

    }
}
