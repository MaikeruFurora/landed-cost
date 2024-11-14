<?php

namespace App\Http\Controllers\Cost;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankHistory;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Contract;
use App\Models\TelegraphicHistory;
use App\Services\DollarBookService;
use Carbon\Carbon;
use FPDM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DollarBookController extends Controller
{

    protected $dollarBookService;

    public function __construct(DollarBookService $dollarBookService)
    {
        $this->dollarBookService = $dollarBookService;
    }


    public function dollarbook(){ 

        if (Helper::usrChckCntrl(['DB001'])) {

            return view('users.dollarbook.dollarbook');

        }

        return view('users.default'); 

    }

    public function printTemplate(BankHistory $bankHistory){
        
        // return $bankHistory->account->branch->id;

        $dd =  Branch::find($bankHistory->account->branch->id)->load('accounts');
        
        $data =  $bankHistory->load(['account','account.branch','account.branch.bank','account.branch.bank.company']);

        if ($data->types=='TOF') {
            return view('users.dollarbook.print.transfer-of-fund',compact('data','dd'));
        }else{
            return view('users.dollarbook.print.authority-of-debit',compact('data','dd'));
        }

    }

    public function bankHistoryList(Request $request){

        if ($request->posted=="false") { 
            return $this->dollarBookService->bankHistoryListDraft($request);
        }else{
            return $this->dollarBookService->bankHistoryList($request);

        }


    }

    public function bankInfoList(Request $request){

        return $this->dollarBookService->bankList($request);

    }

    public function requestForPosted(BankHistory $bankHistory,Request $request){
        if ($bankHistory->update(['posted_at' => Carbon::now()]))
            return response()->json([
                'msg'  => 'Request for posting successfully submited',
                'data' => $bankHistory
            ]);
        else
            return response()->json([
                'msg' => 'Failed to request for posting'
            ], 422);
    }

    public function telegraphicHistoryList(Request $request){

        return $this->dollarBookService->telegraphicHistoryList($request);

    }

    public function fundStore(Request $request){

        switch (true) {
            case $request->types!="TTA":
                    return  empty($request->bank_history_id)? BankHistory::storeBankHstry($request) : BankHistory::updateBankHstry($request);
                break;
            case $request->types=="TTA":
                    return  empty($request->telegraphic_history_id)? TelegraphicHistory::storeTelegraphicHstry($request) : TelegraphicHistory::updateTelegraphicHstry($request);
                break;
            default:
                    return false;
                break;
        }

    }

    public function bankInfo(BankHistory $bankHistory){
        
        $data =  $bankHistory->load(['account','account.branch','account.branch.bank','account.branch.bank.company']);

        return $data;

    }

    public function telegraphicInfo(TelegraphicHistory $telegraphicHistory){

        $data = $telegraphicHistory->load('account');

        return $data;

    }

    public function exportTemplate(BankHistory $bankHistory){

        $data =  $bankHistory->load(['account','account.branch','account.branch.bank','account.branch.bank.company']);

        switch ($data->types) {
            case 'TOF':
                    return  $this->generateWordTemp($data,'transfer-of-fund.docx');
                break;
            case 'AOD':
                    return $this->generateWordTemp($data,'authority-of-debit.docx');
                break;
            default:
                return response()->json([
                        'msg'=> 'Something went wrong'
                    ]);
                break;
        }

    }

    protected function generateWordTemp($data,$filename){

        // return $data->account->branch->bank->company->id;

        $companies = Company::select('accountNo','currencyType')
        ->join('banks','banks.company_id','companies.id')
        ->join('branches','branches.bank_id','banks.id')
        ->join('accounts','accounts.branch_id','branches.id')
        ->where('companies.id',$data->account->branch->bank->company->id)
        ->get();
        
        $dd =  Branch::find($data->account->branch->id)->load('accounts');

        $templateProcessor= new TemplateProcessor('template/'.$filename);
        $nmbrTowrd        = ucwords(Helper::numberToWord($data->amount,$data->toCurrencyType));
        $toACNo           =  $data->toAccountNo .' ('.$data->toName.') '.$data->toBankName. (empty($data->toBranchName)?'':'- '.$data->toBranchName);
        $templateProcessor->setValue('toACNo',$toACNo);
        $templateProcessor->setValue('dateNow',date("F j, Y"));
        $templateProcessor->setValue('numberToWord',$nmbrTowrd);
        $templateProcessor->setValue('bankName',$data->account->branch->bank->bankName);
        $templateProcessor->setValue('bankAcronym',$data->account->branch->bank->acronym);
        $templateProcessor->setValue('branchName',$data->account->branch->branchName);
        $templateProcessor->setValue('companyName',$data->account->branch->bank->company->companyname);

        $templateProcessor->setValue('accountNo',$data->account->accountNo);

        $templateProcessor->setValue('accountNoPHP', $companies[0]->currencyType=='PHP'? $companies[0]->accountNo : $companies[1]->accountNo);
        $templateProcessor->setValue('accountNoUSD', $companies[1]->currencyType=='USD'? $companies[1]->accountNo : $companies[0]->accountNo);

        $templateProcessor->setValue('attention',$data->attention);
        $templateProcessor->setValue('subject',$data->subject);
        $templateProcessor->setValue('fromCurrencyType',$data->account->currencyType);
        $templateProcessor->setValue('amount',number_format($data->amount,2));
        $templateProcessor->setValue('toCurrencyType',$data->toCurrencyType);
        $templateProcessor->setValue('toName',$data->toName);
        $templateProcessor->setValue('toBankName',$data->toBankName);
        $templateProcessor->setValue('toBranchName',$data->toBranchName);
        $templateProcessor->setValue('toAccountNo',$data->toAccountNo);
        $templateProcessor->setValue('amountInPeso',number_format($data->amount*$data->exchangeRate,2));
        $templateProcessor->setValue('amountInDollar',number_format($data->amount,2));
        $templateProcessor->setValue('exchangeRate',number_format($data->exchangeRate,2));
        $templateProcessor->setValue('exchangeRateDate', date("F j, Y",strtotime($data->exchangeRateDate)));
        $templateProcessor->setValue('purpose',strip_tags($data->purpose));
        $templateProcessor->saveAs($filename);
        return response()->download($filename)->deleteFileAfterSend(true);

    }

    public function telegFDF(TelegraphicHistory $telegraphicHistory,$file){

        $telegraphicHistory->load('account');

        $fields = array(
            'dateTT'                  => $telegraphicHistory['dateTT'],
            'branch'                  => $telegraphicHistory['branch'],
            'domesticTT'              => $telegraphicHistory['domesticTT'],
            'foreignTT'               => $telegraphicHistory['foreignTT'],
            'otherTT'                 => $telegraphicHistory['otherTT'],
            'otherTTSpecify'          => $telegraphicHistory['otherTTSpecify'],
            'pddtsDollar'             => $telegraphicHistory['pddtsDollar'],
            'rtgsPeso'                => $telegraphicHistory['rtgsPeso'],
            '20_correspondent'        => $telegraphicHistory['20_correspondent'],
            '20_referenceNo'          => $telegraphicHistory['20_referenceNo'],
            '20_remittersAccountNo'   => $telegraphicHistory['20_remittersAccountNo'],
            '20_invisibleCode'        => $telegraphicHistory['20_invisibleCode'],
            '20_importersCode'        => $telegraphicHistory['20_importersCode'],
            '32a_valueDate'           => $telegraphicHistory['32a_valueDate'],
            '32a_amountAndCurrency'   => $telegraphicHistory['32a_amountAndCurrency'],
            '50_applicationName'      => $telegraphicHistory['50_applicationName'],
            '50_presentAddress'       => $telegraphicHistory['50_presentAddress'],
            '50_permanentAddress'     => $telegraphicHistory['50_permanentAddress'],
            '50_telephoneNo'          => $telegraphicHistory['50_telephoneNo'],
            '50_taxIdNo'              => $telegraphicHistory['50_taxIdNo'],
            '50_faxNo'                => $telegraphicHistory['50_faxNo'],
            '50_otherIdType'          => $telegraphicHistory['50_otherIdType'],
            '52_orderingBank'         => $telegraphicHistory['52_orderingBank'],
            '56_intermediaryBank'     => $telegraphicHistory['56_intermediaryBank'],
            '56_name'                 => $telegraphicHistory['56_name'],
            '56_address'              => $telegraphicHistory['56_address'],
            '57_beneficiaryBank'      => $telegraphicHistory['57_beneficiaryBank'],
            '57_name'                 => $telegraphicHistory['57_name'],
            '57_address'              => $telegraphicHistory['57_address'],
            '57_CountryOfDestination' => $telegraphicHistory['57_CountryOfDestination'],
            '59_beneficiaryAccountNo' => $telegraphicHistory['59_beneficiaryAccountNo'],
            '59_beneficiaryName'      => $telegraphicHistory['59_beneficiaryName'],
            '59_address'              => $telegraphicHistory['59_address'],
            '59_industryType'         => $telegraphicHistory['59_industryType'],
            '70_remittanceInfo'       => $telegraphicHistory['70_remittanceInfo'],
            '71_chargeFor'            => $telegraphicHistory['71_chargeFor'],
            '72_senderToReceiverInfo' => $telegraphicHistory['72_senderToReceiverInfo'],
            'sourceOfFund'            => $telegraphicHistory['sourceOfFund'],
            'industrytype'            => $telegraphicHistory['industrytype'],
            'registrationDate'        => strtoupper(date("M d, Y", strtotime($telegraphicHistory['registrationDate']))),
            'birthPlace'              => $telegraphicHistory['birthPlace'],
            'nationality'             => $telegraphicHistory['nationality'],
            'natureOfWorkOrBusiness'  => $telegraphicHistory['natureOfWorkOrBusiness'],
            'purposeOrReason'         => $telegraphicHistory['purposeOrReason'],
            'accountNo'               => $telegraphicHistory->account->accountNo,
        );

        if ($file=="ttform2") {
            unset($fields['industrytype']);
            unset($fields['59_industryType']);
        }

        $pdf = new FPDM($file.'.pdf');
        $pdf->useCheckboxParser = true; // Checkbox parsing is ignored (default FPDM behaviour) unless enabled with this setting
        $pdf->Load($fields, true);
        $pdf->Merge();
        $pdf->Output();
    }

    public function telegPrint(TelegraphicHistory $telegraphicHistory){

         $telegraphicHistory->load('account');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load('TOF.xlsx');

        
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.12);
        $sheet->getPageMargins()->setLeft(0.7);
        $sheet->getPageMargins()->setBottom(0.75);
        $sheet->getPageMargins()->setHeader(0.3);
        $sheet->getPageMargins()->setFooter(0.3);
     
        $sheet->setCellValue('A1', ($telegraphicHistory['pddtsDollar'])?"x":"");
        $sheet->setCellValue('C1', ($telegraphicHistory['rtgsPeso'])?"x":"");
        $sheet->setCellValue('A2', $telegraphicHistory['20_correspondent']);
        $sheet->setCellValue('A3', $telegraphicHistory['20_referenceNo']);
        $sheet->setCellValue('A4', $telegraphicHistory['20_remittersAccountNo']);
        $sheet->setCellValue('A5', $telegraphicHistory['20_invisibleCode']);
        $sheet->setCellValue('H5', $telegraphicHistory['20_importersCode']);
        $sheet->setCellValue('A6', $telegraphicHistory['32a_valueDate']);
        $sheet->setCellValue('A7', $telegraphicHistory['32a_amountAndCurrency']);
        $sheet->getStyle('A7')->getFont()->setBold(true);
        $sheet->setCellValue('A9', $telegraphicHistory['50_applicationName']);
        $sheet->setCellValue('A11', $telegraphicHistory['50_presentAddress']);

        //52
        $sheet->setCellValue('A15', $telegraphicHistory['50_telephoneNo']);
        $sheet->setCellValue('H15', $telegraphicHistory['50_faxNo']);
        $sheet->setCellValue('A16', $telegraphicHistory['50_taxIdNo']);
        $sheet->setCellValue('H16', $telegraphicHistory['50_otherIdType']);

        //52
        $sheet->setCellValue('A17', $telegraphicHistory['52_orderingBank']);
        $sheet->setCellValue('A18', $telegraphicHistory['56_intermediaryBank']);
        $sheet->getStyle('A18')->getFont()->setBold(true);
        $sheet->setCellValue('A19', $telegraphicHistory['56_name']);
        $sheet->getStyle('A19')->getFont()->setBold(true);
        $sheet->setCellValue('A20', $telegraphicHistory['56_address']);

        //57
        $sheet->setCellValue('A22', $telegraphicHistory['57_beneficiaryBank']);
        $sheet->getStyle('A22')->getFont()->setBold(true);
        $sheet->setCellValue('A23', $telegraphicHistory['57_name']);
        $sheet->getStyle('A23')->getFont()->setBold(true);
        $sheet->setCellValue('A24', $telegraphicHistory['57_address']);
        $sheet->setCellValue('A25', $telegraphicHistory['57_CountryOfDestination']);
        
        //59
        $sheet->setCellValue('A26', $telegraphicHistory['59_beneficiaryAccountNo']);
        $sheet->setCellValue('A28', $telegraphicHistory['59_beneficiaryName']);
        $sheet->setCellValue('A30', $telegraphicHistory['59_address']);
        $sheet->setCellValue('A31', $telegraphicHistory['59_industryType']);

        //70-72
        $sheet->setCellValue('A32', $telegraphicHistory['70_remittanceInfo']);
        $sheet->setCellValue('A35', $telegraphicHistory['71_chargeFor']);
        $sheet->setCellValue('A36', $telegraphicHistory['72_senderToReceiverInfo']);

        $sheet->setCellValue('Q3', $telegraphicHistory['sourceOfFund']);
        $sheet->setCellValue('Q4', $telegraphicHistory['industrytype']);
        $sheet->setCellValue('Q5', $telegraphicHistory['registrationDate']);
        $sheet->setCellValue('Q6', $telegraphicHistory['birthPlace']);
        $sheet->setCellValue('V6', $telegraphicHistory['nationality']);
        $sheet->setCellValue('Q7', $telegraphicHistory['natureOfWorkOrBusiness']);
        $sheet->setCellValue('Q8', $telegraphicHistory['purposeOrReason']);
        $sheet->setCellValue('U9', $telegraphicHistory->account->accountNo);
        
     
        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        // return $writer->save('php://output');
        
        // redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="myfile.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');


        // $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
        // \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
        // $writer->save('php://output');
    }

    public function report(Request $request){

        return $request;

    }

    public function reportTemplate(){

        $companies = Company::with('banks','banks.branches','banks.branches.accounts')->get();

        $data = BankHistory::where('types','TOF')->get();

        $total = 0;

        return view('users/dollarbook/report/report-summary',compact('data','companies','total'));

    }

    public function getCompanyDetails(Request $request){

       return $this->dollarBookService->searchCompanyDetails($request);

    }

    public function dollarbookReport(){

         $data = Contract::with([
            'lcdpnego:id,contract_id,percentage,amount,landedcost_particular_id,allocatedAmount',
            'lcdpnego.landedcost_particular.detail'])->get();

        return view('users.dollarbook.report.dollarbook-report',compact('data'));
    }
}
