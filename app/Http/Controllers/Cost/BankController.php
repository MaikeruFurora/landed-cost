<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class BankController extends Controller
{

   
    
    public function list(Company $company){
        
        return $company->load(['banks','banks.branches','banks.branches.accounts']);

    }

    public function storeBank(Request $request){

        // return $request->all();

        // $request->validate([

        //     'bankName' => 'unique:banks,bankName,'.$request->bank_id,

        //     'acronym'  => 'unique:banks,acronym,'.$request->bank_id

        // ]);

        return (empty($request->bank_id)) ? Bank::storeBank($request) : Bank::updateBank($request);

    }


    public function storeBranch(Request $request){
        

        // $request->validate([

        //     'branchName' => 'unique:branches,branchName,'.$request->branchId,

        //     'accountNo'  => 'unique:branches,accountNo,'.$request->branchId

        // ]);

        return (empty($request->branchId)) ? Branch::storeBranch($request) : Branch::updateBranch($request);

    }

    public function storeAccount(Request $request){

        $request->validate([

            'accountNo'   => 'unique:accounts,accountNo,'.$request->accountId

        ]);

        return (empty($request->accountId)) ? Account::storeAccount($request) : Account::updateAccount($request);

    }

}
