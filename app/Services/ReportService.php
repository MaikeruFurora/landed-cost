<?php

namespace App\Services;

use App\Models\Detail;
use Illuminate\Support\Facades\DB;

class ReportService{
    
    public function filterService($request){
        $data = DB::select("
        select 
            convert(DATE,a.doc_date) as doc_date,
            a.description,
            a.invoiceno,
            a.qtymt,
            c.priceMetricTon,
            c.exchangeRate,
            convert(DATE,c.created_at) as created_at
        from details 
                a inner join 
            landedcost_particulars b on 
                a.id = b.detail_id 
                inner join 
            lcdpnegos c on b.id = c.landedcost_particular_id 
        where convert(DATE,a.doc_date) between convert(date,'{$request->start}') AND convert(date,'{$request->end}') 
        
        and 
            a.description = '{$request->item}'");

            // return $data;
        return $this->extract($data);
    }

    public function searchTerm($request){

        return Detail::groupby(['description'])->orderby('description','asc')

                        ->where('description', 'like', '%'.$request->get('term').'%')
                        
                        ->limit(5)->get(['description']);

    }

    public function extract($data){

        $tmp = array();

        foreach($data as $key => $arg){
            
            $tmp[$arg->invoiceno][] = $arg;
            
        }
        
        $output = array();
        
        foreach($tmp as $key => $data){
            
                $output[] =  [

                    'doc_date'      => date("m/d/y",strtotime($data[0]->doc_date)),

                    'invoiceno'     => $key,

                    'qtymt'         => $data[0]->qtymt,

                    'priceMetricTon'=> $data[0]->priceMetricTon,

                    'avg'           => array_sum(array_column($data,'exchangeRate'))/count($data),

                ];

        }

        return $output;

    }
 

}