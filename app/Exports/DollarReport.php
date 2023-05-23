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

class DollarReport extends DefaultValueBinder implements ShouldAutoSize, FromView, WithCustomValueBinder,WithColumnFormatting   
{


    public $from;

    public $to;

    public $company;

    public $totalPeso=0;

    public $totalDollar=0;

    public function __construct(String $from, String $to, int $company)
    {

        $this->from = $from;

        $this->to = $to;

        $this->company = $company;

    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'A') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }


    public function view() : View
    {

        $data = collect(DB::select("exec dbo.sp_getDollarReport ?,?,?",array($this->from,$this->to,$this->company)));

        return view('users.export-template.dollar',[

            'data'       =>  $data,

            'to'         =>  $this->to,

            'from'       =>  $this->from,

            'totalPeso'  => $this->totalPeso,

            'totalDollar'=> $this->totalDollar,

        ]);

    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection(){
        
    //     return collect(DB::select("exec dbo.sp_getDollarReport ?,?,?",array($this->from,$this->to,$this->company)));

    // }

    // public function map($data): array
    // {
    //     return [
    //         $data->invoiceno,
    //         $data->description,
    //         $data->exchangeRate,
    //         $data->exchangeRateDate,
    //         $data->priceMetricTon,
    //         $data->companyname ?? null,
    //         $data->amount,
    //     ];
    // }

    // public function headings(): array
    // {
    //     return [
    //         'INVOICE',
    //         'DESCRIPTION',
    //         'EXCHANGE RATE',
    //         'EXCHAGE RATE DATE',
    //         'PRICE METRIC TON',
    //         'COMPANY',
    //         'AMOUNT',
    //     ];
    // }
}
