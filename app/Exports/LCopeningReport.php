<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class LCopeningReport extends DefaultValueBinder implements ShouldAutoSize,FromView, WithCustomValueBinder
{
    
    public $from;

    public $to;

    public $company;
 
    public function __construct(String $from, String $to, int $company)
    {
        
        $this->from     = $from;

        $this->to       = $to;

        $this->company  = $company;
        
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'C' || $cell->getColumn() == 'B') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }



    public function view(): View
    {

        $data = DB::select("exec dbo.sp_getLCopeningReport ?,?,?",array($this->from,$this->to,$this->company));

        return view('users.export-template.duties',[

            'data'   => $data,

            'to'     => $this->to,

            'from'   => $this->from,

        ]);
    }

 

}
