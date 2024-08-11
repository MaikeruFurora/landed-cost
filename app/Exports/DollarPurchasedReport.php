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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DollarPurchasedReport extends DefaultValueBinder implements ShouldAutoSize,FromView, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $from;

    public $to;

    public $company;


    public function __construct(String $from, String $to, int $company)
    {

        $this->from = $from;

        $this->to = $to;

        $this->company = $company;

    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function view() : View
    {

        $data = collect(DB::select("exec dbo.sp_getDollarPurchasedReport ?,?,?",array($this->from,$this->to,$this->company)));

        return view('users.export-template.dollarPurchased',[

            'data'       =>  $data,

            'to'         =>  $this->to,

            'from'       =>  $this->from,

        ]);

    }

}
