<?php

namespace App\Helper;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Helper{

    public static $currencyType = [
        array('acronym' =>'PHP','','Currency'=>'Peso'),
        array('acronym' =>'USD','','Currency'=>'US Dollar'),
    ];

    public static $intact_particular=[
        array('p_name'=>'LC Opening Charge','p_code'=>'LC','action'=>true),
        array('p_name'=>'LC/DP NEGO','p_code'=>'NEG','action'=>true),
        array('p_name'=>'Brokerage Fee','p_code'=>'BF'),
        array('p_name'=>'Insurance','p_code'=>'IN'),
        array('p_name'=>'Freight','p_code'=>'FR','action'=>true),
    ];

    public static $otherPrev  = ['Print','Gather-SAP','Posting','Unposting','Generate-Report','Dollar-Book','Particular-Invoice'];

    public static $pageAccess = ['LandedCost','LCOpening','AdvancePayment','Report','DollarBook','Paticular','Users'];

    public static $getRefAmntCode =['BF','IN'];

    public static $except_code  = ['LC','NEG','FR'];

    public static function createPaticularThisInvoice($invoice,$amount=0){

    }

    public static function cleanNumberByFormat($value){

        $data =  floatval(preg_replace('/[^\d.]/', '', $value));

        return $data;

    }

    public static function helperRight(){

        return (auth()->user()->accountingHead() || auth()->user()->type);

    }

    public static function fields($data){
        return [
            'pono'          => $data[0]->PONumber,
            'itemcode'      => $data[0]->ItemCode,
            'cardname'      => $data[0]->CardName,
            'cardcode'      => $data[0]->CardCode,
            'actualQtyKLS'  => $data[0]->actualQtyKLS ?? NULL,
            'actualQtyMT'   => $data[0]->actualQtyMT ?? NULL,
            'vessel'        => $data[0]->vessel,
            'description'   => $data[0]->Dscription,
            'invoiceno'     => $data[0]->InvoiceNo,
            'broker'        => $data[0]->Broker,
            'weight'        => (int)$data[0]->Weight,
            'quantity'      => $data[0]->quantity,
            'qtykls'        => $data[0]->QtyInKls,
            'qtymt'         => $data[0]->QtyInMT,
            'fcl'           => count($data),
            'suppliername'  => $data[0]->suppliername,
            'blno'          => $data[0]->BLNo,
            'doc_date'      => $data[0]->DocDate,
            'posted_at'     => $data[0]->posted_at ?? NULL,
            'company_id'    => $data[0]->selectCompany ?? NULL,
        ];
    }

    public static function usrChckCntrl($code) 
    {

         if(!empty($code)) {        

            return (auth()->user()->user_accesses
                    ->load('user_control:id,code')
                    ->whereIn('user_control.code', $code)
                    ->count()>0) || auth()->user()->type;

        }
        
        return false;

    }

    public static function auditLog($type,$event){

        return  DB::table('audits')->insert([

                    'auditable_id'   => auth()->user()->id,

                    'auditable_type' => $type,

                    'event'          => $event,

                    'url'            => request()->fullUrl(),

                    'ip_address'     => request()->getClientIp(),

                    'user_agent'     => request()->userAgent(),

                    'created_at'     => Carbon::now(),

                    'updated_at'     => Carbon::now(),

                    'user_id'        => auth()->user()->id,

                ]);
    }

    public static function numberToWord($num,$currency=null) {
 
        $ones = array(
                1 => "one",
                2 => "two",
                3 => "three",
                4 => "four",
                5 => "five",
                6 => "six",
                7 => "seven",
                8 => "eight",
                9 => "nine",
                10 => "ten",
                11 => "eleven",
                12 => "twelve",
                13 => "thirteen",
                14 => "fourteen",
                15 => "fifteen",
                16 => "sixteen",
                17 => "seventeen",
                18 => "eighteen",
                19 => "nineteen"
            );
            $tens = array(
                1 => "ten",
                2 => "twenty",
                3 => "thirty",
                4 => "forty",
                5 => "fifty",
                6 => "sixty",
                7 => "seventy",
                8 => "eighty",
                9 => "ninety"
            );
            $hundreds = array(
                "hundred",
                "thousand",
                "million",
                "billion",
                "trillion",
                "quadrillion"
            );
            $num = number_format($num,2,".",",");
            $num_arr = explode(".",$num);
            $wholenum = $num_arr[0];
            $decnum = $num_arr[1];
            $whole_arr = array_reverse(explode(",",$wholenum));
            krsort($whole_arr);
            $words = "";
            foreach($whole_arr as $key => $i) {
                if($i == 0) {
                continue;
                }
                if($i < 20) {
                    $words .= $ones[intval($i)];
                } elseif($i < 100) {
                    if(substr($i,0,1) == 0 && strlen($i) == 3) {
                        $words .= $tens[substr($i,1,1)];
                        if(substr($i,2,1) != 0) {
                            $words .= " ".$ones[substr($i,2,1)];
                        }
                    } else {
                        $words .= $tens[substr($i,0,1)];
                    if(substr($i,1,1) != 0) {
                        $words .= " ".$ones[substr($i,1,1)];
                    }
                }
                } else {
                    // $words .= $ones[substr($i,0,1)]." ".$hundreds[0].' and ';
                    if(substr($i,1,1) != 0 || substr($i,2,1) != 0) {
                        $words .= $ones[substr($i,0,1)]." ".$hundreds[0].' ';
                    } else {
                        $words .= $ones[substr($i,0,1)]." ".$hundreds[0];
                    }
                    if(substr($i,1,2) < 20 && substr($i,1,1) != 0) {
                        $words .= " ".$ones[(substr($i,1,2))];
                    } else {
                        if(substr($i,1,1) != 0) {
                            $words .= " ".$tens[substr($i,1,1)];
                        }
                        if(substr($i,2,1) != 0) {
                            $words .= " ".$ones[substr($i,2,1)];
                        }
                    }
                }
                if($key > 0) {
                    $words .= " ".$hundreds[$key]." ";
                }
            }
            $words .= $unit??' ';
            
            if($decnum > 0) {
                $words .= " and ";
            if($decnum < 20) {
                // $words .= $ones[intval($decnum)];
                $words .= $decnum."/100 ";
            } elseif($decnum < 100) {
                // $words .= $tens[substr($decnum,0,1)].$decnum."/100 ";
                $words .= $decnum."/100 ";
                if(substr($decnum,1,1) != 0) {
                    $words .= " ".$ones[substr($decnum,1,1)];
                }
            }
                $words .= $subunit??' ';
            }

            switch (true) {
                case (strtoupper($currency)=="PHP"):
                        return $words.' Pesos only';
                    break;
                case (strtoupper($currency)=="USD"):
                        return 'U.S Dollar '. $words .' only';
                    break;
                default:
                        return $words;
                    break;
            }
           
            
    }

}