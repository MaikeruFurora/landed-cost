<?php

use App\Http\Controllers\Cost\AdvancePaymentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cost\BankController;
use App\Http\Controllers\Cost\DetailsController;
use App\Http\Controllers\cost\GatherDataController;
use App\Http\Controllers\Cost\LandedCostController;
use App\Http\Controllers\Cost\ParticularController;
use App\Http\Controllers\Cost\LcopeningChargeController;
use App\Http\Controllers\Cost\ReportController;
use App\Http\Controllers\user\AuditLogController;
use App\Http\Controllers\Cost\CompanyController;
use App\Http\Controllers\Cost\ContractController;
use App\Http\Controllers\Cost\DollarBookController;
use App\Http\Controllers\User\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:web', 'preventBackHistory'])->name('auth.')->group(function () {

    Route::get('/', function () {
        return view('auth/signin');
    })->name('login');
    Route::post('/post', [AuthController::class, 'loginPost'])->name('login.post');
});


Route::middleware(['auth:web','preventBackHistory','auth.user'])->name('authenticate.')->prefix('auth/')->group(function(){

    Route::get('details',[DetailsController::class,'index'])->name('details');
    Route::get('details/list',[DetailsController::class,'dataInvoice']);
    Route::get('details/create',[DetailsController::class,'create'])->name('details.create');
    //landed cost
    Route::get('details/cost/{detail}',[LandedCostController::class,'index'])->name('details.landedcost');
    Route::post('details/cost/form-store',[LandedCostController::class,'formStore'])->name('details.landedcost.form.store');
    Route::post('details/cost/particular-input/{landedcostParticular}',[LandedCostController::class,'particularInput']);


    //company maintenance

    //Company
    Route::get('company',[CompanyController::class,'index'])->name('company');
        //BANKS
        Route::post('company/bank/store',[BankController::class,'storeBank']);
        Route::get('company/bank/{company}',[BankController::class,'list'])->name('bank.list');
        //BRANCH
        Route::post('company/branch/store',[BankController::class,'storeBranch']);
        //ACCOUNT
        Route::post('company/account/store',[BankController::class,'storeAccount']);
    Route::get('details/cost/company/list',[CompanyController::class,'list']);
    Route::post('details/cost/company/store',[CompanyController::class,'store'])->name('company.store');

    //notes
    Route::get('details/cost/particular-notes/{landedcostParticular}',[LandedCostController::class,'particularNote']);
    Route::post('details/cost/particular-notes/store/{landedcostParticular}',[LandedCostController::class,'particularNoteStore']);
    //update invoice details
    Route::post('details/cost/invoice-detail/store',[DetailsController::class,'store']);
    Route::post('details/cost/invoice-detail/post',[DetailsController::class,'postInvoice']);
    
    //nego
    Route::get('details/cost/nego-list/{landedcostParticular}',[LandedCostController::class,'negoList']);
    Route::post('details/cost/nego-store/{landedcostParticular}',[LandedCostController::class,'negoStore']);
    Route::delete('details/cost/nego-delete/{lcdpnego}',[LandedCostController::class,'negoDestroy']);
    
    //freight
    Route::get('details/cost/freight-list/{landedcostParticular}',[LandedCostController::class,'freightList']);
    Route::post('details/cost/freight-store/{landedcostParticular}',[LandedCostController::class,'freightStore']);
    Route::delete('details/cost/freight-delete/{freight}',[LandedCostController::class,'freightDestroy']);

    // lcopening charge
    Route::get('opening/charge',[LcopeningChargeController::class,'index'])->name('opening.charge');
    Route::get('opening/charge/list',[LcopeningChargeController::class,'list']);
    Route::get('opening/charge/invoice/{openAmount}',[LcopeningChargeController::class,'invoice'])->name('opening.invoice');
    Route::get('opening/charge/invoice/search/item',[LcopeningChargeController::class,'invoiceList']);
    Route::post('opening/charge/invoice/store/{openAmount}',[LcopeningChargeController::class,'invoiceSave']);
    Route::post('opening/store',[LcopeningChargeController::class,'store'])->name('opening.store');
    Route::get('opening/remove/{lcopeningCharge}',[LcopeningChargeController::class,'removeInvoice']);

    //contract
    Route::get('contract',[ContractController::class,'index'])->name('contract');
    Route::post('contract/store',[ContractController::class,'store']);
    Route::get('contract/list',[ContractController::class,'list']);
    Route::get('contract/search',[ContractController::class,'search']);
    Route::post('contract/invoice/store/{contract}',[ContractController::class,'invoiceSave']);
    
    //gather data from sap datatabse
    Route::get('po',[GatherDataController::class,'index'])->name('po.search');
    Route::get('po/search',[GatherDataController::class,'search']);
    Route::post('po/store',[GatherDataController::class,'storePO'])->name('po.store');

    // particular
    Route::get('particular',[ParticularController::class,'index'])->name('particular');
    Route::post('particular/store',[ParticularController::class,'store'])->name('particular.store');
    Route::get('particular/edit/{particular}',[ParticularController::class,'edit']);
    Route::get('particular/update/sort',[ParticularController::class,'sortOrder']);

    //userController
    Route::get('users',[UserController::class,'index'])->name('user');
    Route::get('users/create',[UserController::class,'create'])->name('user.create');
    Route::post('users/store',[UserController::class,'store'])->name('user.store');
    Route::get('users/{user}/edit',[UserController::class,'edit'])->name('user.edit');
    
    //audit trail
    Route::get('audit-log',[AuditLogController::class,'index'])->name('audit.log');
    Route::get('audit-log/list',[AuditLogController::class,'list']);
    
    
    Route::get('cost',[LandedCostController::class,'details'])->name('cost');
    
    Route::get('print/{detail}',[LandedCostController::class,'print'])->name('print');

    //generate report
    Route::get('report',[ReportController::class,'index'])->name('report');
    Route::post('report/filter',[ReportController::class,'filter'])->name('report.filter');
    Route::get('report/filter/invoice',[ReportController::class,'searchItem']);
    Route::get('report/print',[ReportController::class,'print']);
    
    //export duties
    Route::get('report/export',[ReportController::class,'exportReport'])->name('report.export');
   
    // transaction
    Route::get('dollarbook',[DollarBookController::class,'dollarbook'])->name('dollarbook');
    Route::get('dollarbook/company-details',[DollarBookController::class,'getCompanyDetails']);
    Route::get('dollarbook/bankinfo/list',[DollarBookController::class,'bankInfoList']);
    Route::get('dollarbook/bankinfo/edit/{bankHistory}',[DollarBookController::class,'bankInfo']);
    Route::post('dollarbook/fund/store',[DollarBookController::class,'fundStore']);
    Route::get('dollarbook/fund/print/{bankHistory}',[DollarBookController::class,'printTemplate']);
    Route::get('dollarbook/fund/print-view/telegPDF/{telegraphicHistory}/{file}',[DollarBookController::class,'telegFDF']);
    Route::get('dollarbook/fund/print-view/telegraphic/{telegraphicHistory}',[DollarBookController::class,'telegPrint']);
    Route::get('dollarbook/fund/export/{bankHistory}',[DollarBookController::class,'exportTemplate']);
    
    //bankhistory & telegraphic
    Route::get('dollarbook/bankhistory/list',[DollarBookController::class,'bankHistoryList']);
    Route::get('dollarbook/telegraphichistory/edit/{telegraphicHistory}',[DollarBookController::class,'telegraphicInfo']);
    Route::get('dollarbook/telegraphichistory/list',[DollarBookController::class,'telegraphicHistoryList']);

    //dollarbook report summary
    Route::post('dollarbook/report',[DollarBookController::class,'report']);
    Route::get('dollarbook/report/template',[DollarBookController::class,'reportTemplate']);

    //signout
    Route::post('signout', [UserController::class, 'signout'])->name('signout');
});

