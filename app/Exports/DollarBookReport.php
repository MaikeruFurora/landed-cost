<?php

namespace App\Exports;

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

class DollarBookReport  extends DefaultValueBinder implements ShouldAutoSize, FromView, WithTitle, WithCustomValueBinder
{

    public $data;

    public $title;

    public function __construct(Object $data,String $title)
    {
        
        $this->data = $data;

        $this->title = $title;

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
        return view('users.export-template.dollarBook',[
            'data'=>$this->data
        ]);
    }

    public function title(): string
    {
        return strtoupper($this->title);
    }
}
