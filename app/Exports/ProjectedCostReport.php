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

class ProjectedCostReport extends DefaultValueBinder implements ShouldAutoSize,FromView, WithCustomValueBinder
{
    public $from;

    public $to;
    
    public $itemName;
 
    public function __construct(String $from, String $to, String $itemName)
    {
        
        $this->from     = $from;

        $this->to       = $to;

        $this->itemName = $itemName;
        
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

        $data = DB::select("exec dbo.sp_getlcTabsheetReport ?,?,?",array($this->from,$this->to,$this->itemName));
        // $data = DB::select("select id,invoiceno,qtykls,description from details where posted_at between '".Carbon::parse($this->from)."' and '".Carbon::parse($this->to)."' and  description like   '%".$this->itemName."%'");
        $particulars = Particular::get(['id','p_name']);#->sortBy('p_sort', SORT_REGULAR, false);

        return view('users.export-template.projected',[

            'search'        => $this->itemName,

            'data'          => $data,

            'particulars'   => $particulars,

            'to'            => $this->to,

            'from'          => $this->from,

        ]);
    }
}
