<?php

namespace App\Services;

use App\Models\BankHistory;
use App\Models\Company;
use App\Models\TelegraphicHistory;
use Illuminate\Support\Facades\DB;

class DollarBookService{



    public function bankList($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));


        $filter = $search['value'];
    
        $sortColumns = array('companyname','companies.acronym','bankName','branchName','accountNo');
    
        $query = Company::select('accounts.id','companyname','companies.acronym','bankName','branchName','accountNo','currencyType','companyAddress','registrationDate','tinNo')
                        ->join('banks','banks.company_id','companies.id')
                        ->join('branches','branches.bank_id','banks.id')
                        ->join('accounts','accounts.branch_id','branches.id');
    
        if (!empty($filter)) {
            $query
            ->where('companyname', 'like', '%'.$filter.'%')
            ->orwhere('bankName', 'like', '%'.$filter.'%')
            ->orwhere('branchName', 'like', '%'.$filter.'%')
            ->orwhere('accountNo', 'like', '%'.$filter.'%');
        }

        $query->where('companyStatus', 1);
    
        $recordsTotal = $query->count();
    
        $sortColumnName = $sortColumns[$order[0]['column']];
    
        $query->take($length)->skip($start);

        // if($draw==1){
        //     $query->orderBy($sortColumnName, $order[0]['dir']);
        // }
    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "companyname"       => $value->companyname,
                    "companyAddress"    => $value->companyAddress,
                    "registrationDate"  => $value->registrationDate,
                    "tinNo"             => $value->tinNo,
                    "bankName"          => $value->bankName, 
                    "branchName"        => $value->branchName,
                    "accountNo"         => $value->accountNo,
                    "currencyType"      => $value->currencyType,
                    "id"                => $value->id,
                ];
        }

        return $json;
    }

    public function bankHistoryList($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));
    
        $filter = $search['value'];
    
        $sortColumns = array('companyname','companies.acronym','bankName','branchName','accountNo');
    
        $query = BankHistory::select('bank_histories.id', 'types', 'amount', 'toName', 'toBankName', 'toBranchName', 'toAccountNo', 'purpose', 'branchName', 'bankName', 'companyname', 'transactionNo', 'currencyType', 'bank_histories.posted_at')
                ->join('accounts', 'bank_histories.account_id', '=', 'accounts.id')
                ->join('branches', 'accounts.branch_id', '=', 'branches.id')
                ->join('banks', 'branches.bank_id', '=', 'banks.id')
                ->join('companies', 'banks.company_id', '=', 'companies.id')
                ->whereNotNull('bank_histories.posted_at');

            if (!empty($filter)) {
                $query->where(function ($query) use ($filter) {
                    $query->orWhere('types', 'like', '%' . $filter . '%')
                        ->orWhere('toName', 'like', '%' . $filter . '%')
                        ->orWhere('toBankName', 'like', '%' . $filter . '%')
                        ->orWhere('toAccountNo', 'like', '%' . $filter . '%')
                        ->orWhere('bankName', 'like', '%' . $filter . '%')
                        ->orWhere('branchName', 'like', '%' . $filter . '%')
                        ->orWhere('companyname', 'like', '%' . $filter . '%')
                        ->orWhere('transactionNo', 'like', '%' . $filter . '%');
                });
            }
    
        $recordsTotal = $query->count();
    
        $sortColumnName = $sortColumns[$order[0]['column']];
    
        $query->take($length)->skip($start);

        // if($draw==1){
        //     $query->orderBy($sortColumnName, $order[0]['dir']);
        // }
    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "types"         => $value->types,
                    "amount"        => number_format($value->amount,2),
                    "toName"        => $value->toName,
                    "toBankName"    => $value->toBankName,
                    "toBranchName"  => $value->toBranchName,
                    "toAccountNo"   => $value->toAccountNo,
                    "purpose"       => $value->purpose,
                    "branchName"    => $value->branchName,
                    "bankName"      => $value->bankName,
                    "companyname"   => $value->companyname,
                    "transactionNo" => $value->transactionNo,
                    "currencyType"  => $value->currencyType,
                    "posted_at"     => $value->posted_at,
                    "id"            => $value->id,
                ];
        }

        return $json;

    }


    public function bankHistoryListDraft($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));
    
        $filter = $search['value'];
    
        $sortColumns = array('companyname','companies.acronym','bankName','branchName','accountNo');
    
        $query= BankHistory::select('bank_histories.id','types','amount','toName','toBankName','toBranchName','toAccountNo','purpose','branchName','bankName','companyname','transactionNo','currencyType','bank_histories.posted_at')
            ->join('accounts','bank_histories.account_id','accounts.id')
            ->join('branches','accounts.branch_id','branches.id')
            ->join('banks','branches.bank_id','banks.id')
            ->join('companies','banks.company_id','companies.id')
            ->whereNull('bank_histories.posted_at');
    
            if (!empty($filter)) {
                $query->where(function ($query) use ($filter) {
                    $query->orWhere('types', 'like', '%' . $filter . '%')
                        ->orWhere('toName', 'like', '%' . $filter . '%')
                        ->orWhere('toBankName', 'like', '%' . $filter . '%')
                        ->orWhere('toAccountNo', 'like', '%' . $filter . '%')
                        ->orWhere('bankName', 'like', '%' . $filter . '%')
                        ->orWhere('branchName', 'like', '%' . $filter . '%')
                        ->orWhere('companyname', 'like', '%' . $filter . '%')
                        ->orWhere('transactionNo', 'like', '%' . $filter . '%');
                });
            }
    
        $recordsTotal = $query->count();
    
        $sortColumnName = $sortColumns[$order[0]['column']];
    
        $query->take($length)->skip($start);

        // if($draw==1){
        //     $query->orderBy($sortColumnName, $order[0]['dir']);
        // }
    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "types"         => $value->types,
                    "amount"        => number_format($value->amount,2),
                    "toName"        => $value->toName,
                    "toBankName"    => $value->toBankName,
                    "toBranchName"  => $value->toBranchName,
                    "toAccountNo"   => $value->toAccountNo,
                    "purpose"       => $value->purpose,
                    "branchName"    => $value->branchName,
                    "bankName"      => $value->bankName,
                    "companyname"   => $value->companyname,
                    "transactionNo" => $value->transactionNo,
                    "currencyType"  => $value->currencyType,
                    "posted_at"     => $value->posted_at,
                    "id"            => $value->id,
                ];
        }

        return $json;

    }


   

    public function telegraphicHistoryList($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));
    
        $filter = $search['value'];
    
        $sortColumns = array('companyname','companies.acronym','bankName','branchName','accountNo');
    
        $query = TelegraphicHistory::select('telegraphic_histories.id','transactionNo','32a_valueDate','32a_amountAndCurrency','50_applicationName','50_presentAddress','50_taxIdNo',
                    '56_intermediaryBank','56_name','56_address','57_beneficiaryBank','57_name','57_address','59_beneficiaryAccountNo','59_beneficiaryName','59_address',
                    '71_chargeFor','sourceOfFund','registrationDate','natureOfWorkOrBusiness','purposeOrReason','accountNo')->join('accounts','telegraphic_histories.account_id','accounts.id');
    
        if (!empty($filter)) {
            $query
            ->where('transactionNo', 'like', '%'.$filter.'%')
            ->orWhere('32a_valueDate', 'like', '%'.$filter.'%')
            ->orWhere('32a_amountAndCurrency', 'like', '%'.$filter.'%')
            ->orWhere('50_applicationName', 'like', '%'.$filter.'%')
            ->orWhere('50_presentAddress', 'like', '%'.$filter.'%')
            ->orWhere('50_taxIdNo', 'like', '%'.$filter.'%')
            ->orWhere('56_intermediaryBank', 'like', '%'.$filter.'%')
            ->orWhere('56_name', 'like', '%'.$filter.'%')
            ->orWhere('56_address', 'like', '%'.$filter.'%')
            ->orWhere('57_beneficiaryBank', 'like', '%'.$filter.'%')
            ->orWhere('57_name', 'like', '%'.$filter.'%')
            ->orWhere('57_address', 'like', '%'.$filter.'%')
            ->orWhere('59_beneficiaryAccountNo', 'like', '%'.$filter.'%')
            ->orWhere('59_beneficiaryName', 'like', '%'.$filter.'%')
            ->orWhere('59_address', 'like', '%'.$filter.'%')
            ->orWhere('71_chargeFor', 'like', '%'.$filter.'%')
            ->orWhere('sourceOfFund', 'like', '%'.$filter.'%')
            ->orWhere('registrationDate', 'like', '%'.$filter.'%')
            ->orWhere('natureOfWorkOrBusiness', 'like', '%'.$filter.'%')
            ->orWhere('purposeOrReason', 'like', '%'.$filter.'%')
            ->orWhere('accountNo', 'like', '%'.$filter.'%');
        }
    
        $recordsTotal = $query->count();
    
        $sortColumnName = $sortColumns[$order[0]['column']];
    
        $query->take($length)->skip($start);

        // if($draw==1){
        //     $query->orderBy($sortColumnName, $order[0]['dir']);
        // }
    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    'transactionNo'          => $value['transactionNo'],
                    '32a_valueDate'          => $value['32a_valueDate'],
                    '32a_amountAndCurrency'  => $value['32a_amountAndCurrency'],
                    '50_applicationName'     => $value['50_applicationName'],
                    '50_presentAddress'      => $value['50_presentAddress'],
                    '50_taxIdNo'             => $value['50_taxIdNo'],
                    '56_intermediaryBank'    => $value['56_intermediaryBank'],
                    '56_name'                => $value['56_name'],
                    '56_address'             => $value['56_address'],
                    '57_beneficiaryBank'     => $value['57_beneficiaryBank'],
                    '57_name'                => $value['57_name'],
                    '57_address'             => $value['57_address'],
                    '59_beneficiaryAccountNo'=> $value['59_beneficiaryAccountNo'],
                    '59_beneficiaryName'     => $value['59_beneficiaryName'],
                    '59_address'             => $value['59_address'],
                    '71_chargeFor'           => $value['71_chargeFor'],
                    'sourceOfFund'           => $value['sourceOfFund'],
                    'registrationDate'       => $value['registrationDate'],
                    'natureOfWorkOrBusiness' => $value['natureOfWorkOrBusiness'],
                    'purposeOrReason'        => $value['purposeOrReason'],
                    'id'                     => $value['id'],
                ];
        }

        return $json;

    }

    public function searchCompanyDetails($request){

        $data = $request->all();

        $query = $data['query'];

        $filter_data = DB::select("select [toAccountNo], [toname] from [dbo].[vw_company_details] where [toaccountno] like '%{$query}%' OR [toname] like '%{$query}%'");
        
        return response()->json($filter_data);

    }

}