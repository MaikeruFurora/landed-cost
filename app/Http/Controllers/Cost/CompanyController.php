<?php

namespace App\Http\Controllers\Cost;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function index(){

        return view('users.company.company',[
            'companies'=>$this->list()
        ]);
        
    }
    
    public function list(){

        $data = Company::orderBy('created_at','desc')->get();

        return $data;
    }

    public function store(Request $request){
        
        $request->validate([
            
            'companyname' => 'required|unique:companies,companyname,'.$request->id,

            'companyacronym'     => 'nullable|sometimes|unique:companies,acronym,'.$request->id,
            
        ],[

            'companyname.required'  => 'The acount no field is required..',
            
            'companyname.unique'    => 'The company name has already been taken.',

            'companyacronym.unique'        => 'The acronym has already been taken.',
            
        ]);

        $check =  (empty($request->id)) ? Company::saveCompany($request) : Company::updateCompany($request);
        
        if ($check) {
            return back();
        }

    }

}