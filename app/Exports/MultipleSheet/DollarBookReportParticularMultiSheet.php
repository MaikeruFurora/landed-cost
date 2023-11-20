<?php

namespace App\Exports\MultipleSheet;

use App\Exports\DollarBookReport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DollarBookReportParticularMultiSheet implements WithMultipleSheets
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

        $data = DB::select("exec dbo.sp_getDollarBookReport ?,?",array($this->from,$this->to));
        $coll = collect($data);
        $res =  $coll->groupBy('REF');
        $key  = collect($data)->unique(['REF'])->pluck('REF');
        for ($i=0; $i <count($key) ; $i++) { 
            $sheets[]= new DollarBookReport($res[$key[$i]],$key[$i]);
        }

        return $sheets;

    }
}
