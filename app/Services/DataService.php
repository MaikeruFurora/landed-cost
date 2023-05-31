<?php

namespace App\Services;

use App\Models\Detail;
use App\Models\Particular;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseOrderRequest;

class DataService{
    
    public function searchTerm($request){
        // $ponoList = array_map(function($value) {  return intval($value); }, Detail::get(['pono'])->pluck('pono')->toArray());

        $result = $this->cleanArrayType(Detail::get(['pono'])->pluck('pono')->unique()->toArray());

        $data = $this->sqlSap($request,'detail',null);

        return ($this->filterItemCode($data ?? []));

    }

    public function cleanArrayType($array){

        $json = array_map('intval',$array);

        return "'" . implode ( "', '", $json ) . "'";

    }

    public function sqlSap($request,$option1,$option2){

        $search = $request->input('search');

        return DB::select("exec dbo.sp_po_details ?,?,?",array($search,$option1,$option2));
        
        // $tblview = $request->input('whse')=='manila'?'[dbo].[vw_PO_Details]':'[dbo].[vw_PO_Details_Province]';

        // return DB::select("
            //         select  
            //             [PONumber], [CardCode], [CardName], [ItemCode],[InvoiceNo], [Dscription],[QtyInKls],[QtyInMT],
            //             [ContainerNo], [UOM],[Weight], [BLNo], [Broker], [quantity],[CreateDate],[DocDate],[vessel],[suppliername]
            //         from 
            //             {$tblview}
            //             where 
            //             (
            //                     [PONumber] like '%{$search}%'
            //                 or
            //                     [BLNo] like '%{$search}%'
            //                 or
            //                     [InvoiceNo] like '%{$search}%'
            //                 or  
            //                     [Dscription] like '%{$search}%'
            //                 or
            //                     [vessel] like '%{$search}%'
            //                 or
            //                     [suppliername] like '%{$search}%'
            //             )
            //             and [PONumber] NOT IN ($notInclude)
        //     ");
    }

    


    public function filterItemCode(array $data,$permission=TRUE): array{

                $tmp = array();

                foreach($data as $key => $arg){
                    $tmp[$arg->InvoiceNo][] = $arg;
                }
                
                $output = array();
                
                foreach($tmp as $key => $data){
                    
                        $output[] =  [
                            'invoiceno'     => $key,
                            'suppliername'  => $data[0]->suppliername ?? '',
                            'itemcode'      => $data[0]->ItemCode ?? '',
                            'containerno'   => $data[0]->ContainerNo ?? '',
                            'pono'          => $data[0]->PONumber ?? '',
                            'cardname'      => $data[0]->CardName ?? '',
                            'cardcode'      => $data[0]->CardCode ?? '',
                            'vessel'        => $data[0]->vessel ?? '',
                            'description'   => $data[0]->Dscription ?? '',
                            'broker'        => $data[0]->Broker ?? '',
                            'createdate'    => $data[0]->CreateDate ?? '',
                            'docdate'       => $data[0]->DocDate ?? '',
                            'weight'        => (int)$data[0]->Weight,
                            'uom'           => $data[0]->UOM ?? '',
                            'quantity'      => array_sum(array_column($data,'quantity')),
                            'qtykls'        => array_sum(array_column($data,'QtyInKls')),
                            'qtymt'         => array_sum(array_column($data,'QtyInMT')),
                            'fcl'           => count($data),
                            'doc_date'      => $data[0]->CreateDate,
                            'data'          => ($permission)?$data:[]
                        ];

                }

                return $output;
    }



    public function storePO(PurchaseOrderRequest $request){
        
        $data = Detail::storeInvoice($request);

        $particular = Particular::getParticularId();

        $data->storeDetailParticular($particular);

        return $data;

    }

}