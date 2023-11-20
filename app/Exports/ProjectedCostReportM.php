<?php

namespace App\Exports;

use App\Models\Particular;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ProjectedCostReportM  extends DefaultValueBinder implements ShouldAutoSize, FromView, WithTitle, WithCustomValueBinder
{

    public $data;

    public $title;

    public $from;
    
    public $to;

    public function __construct(Object $data,String $from,String $to,String $title)
    {
        
        $this->data = $data;

        $this->title = $title;

    }

    // public function bindValue(Cell $cell, $value)
    // {
    //     if ($cell->getColumn() == 'A' || $cell->getColumn() == 'B') {
    //         $cell->setValueExplicit($value, DataType::TYPE_STRING);

    //         return true;
    //     }
    //     return parent::bindValue($cell, $value);
    // }


    public function view(): View
    {
       $particulars = Particular::get(['id','p_name']);#->sortBy('p_sort', SORT_REGULAR, false);

       return view('users.export-template.projected',[

           'search'        => 'ALL',

           'data'          => $this->data,

           'particulars'   => $particulars,

           'to'            => $this->to,

           'from'          => $this->from,

       ]);
    }

    public function title(): string
    {
        return floor(microtime(true) * 1000);
    }
}
