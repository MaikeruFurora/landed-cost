<?php

namespace App\Exports;

use App\Models\Particular;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class CustomDutiesReport extends DefaultValueBinder implements ShouldAutoSize,FromView, WithCustomValueBinder
{
    public $from;

    public $to; 
 
    public function __construct(String $from, String $to)
    {
        
        $this->from     = $from;

        $this->to       = $to;
  
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'A' || $cell->getColumn() == 'B') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }



    public function view(): View
    {

        $data = DB::select("exec dbo.sp_getCustomDutyReport ?,?",array($this->from,$this->to));
        

        return view('users.export-template.customDutiesReport',[ 
            'data'          => $data, 

            'to'            => $this->to,

            'from'          => $this->from,

        ]);
    }
}
