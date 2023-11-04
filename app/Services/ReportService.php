<?php

namespace App\Services;

use App\Models\Detail;
use Illuminate\Support\Facades\DB;

class ReportService{
    
    public function filterService($request){

        return [
            DB::select("exec dbo.sp_negoFilter ?,?,?,?",array($request->start,$request->end,$request->supplier,$request->item)),
            DB::select("exec dbo.sp_freightFilter ?,?,?,?",array($request->start,$request->end,$request->supplier,$request->item)),
            DB::select("exec dbo.sp_getReportForDollarBook ?,?,?,?",array($request->start,$request->end,$request->supplier,$request->item)),
        ];

        // $data = DB::select("
        // select 
        //     c.exchangeRateDate,
        //     a.description,
        //     a.invoiceno,
        //     a.qtymt,
        //     c.priceMetricTon,
        //     c.exchangeRate,
        //     convert(DATE,c.created_at) as created_at
        // from details 
        //         a left join 
        //     landedcost_particulars b on 
        //         a.id = b.detail_id 
        //         left join 
        //     lcdpnegos c on b.id = c.landedcost_particular_id 
        // where convert(DATE,c.exchangeRateDate) between convert(date,'{$request->start}') AND convert(date,'{$request->end}') 
        
        // and 
        //     a.description = '{$request->item}'");

            // return $data;
        // return $this->extract($data);
    }

    public function searchTerm($request){

        $data =  Detail::groupby(['description'])->orderby('description','asc')
        
        ->where('description', 'like', '%'.$request->get('term').'%')
        
        ->limit(5)->get(['description']);

        // $data[] = (object) ['description' => 'All']; 

        return $data;

    }

    public function searchSupplier($request){

        $data =  Detail::groupby(['suppliername'])->orderby('suppliername','asc')
        
        ->where('suppliername', 'like', '%'.$request->get('term').'%')
        
        ->limit(5)->get(['suppliername']);

        return $data;

    }

    public function extract($data){

        $tmp = array();

        foreach($data as $key => $arg){
            
            $tmp[$arg->invoiceno][] = $arg;
            
        }
        
        $output = array();
        
        foreach($tmp as $key => $data){
            
                $output[] =  [

                    // 'doc_date'      => date("m/d/y",strtotime($data[0]->doc_date)),
                    'exchangeRateDate'      => date("m/d/y",strtotime($data[0]->exchangeRateDate)),

                    'invoiceno'     => $key,

                    'qtymt'         => $data[0]->qtymt,

                    'priceMetricTon'=> $data[0]->priceMetricTon,

                    'avg'           => array_sum(array_column($data,'exchangeRate'))/count($data),

                ];

        }

        return $output;

    }
 

}