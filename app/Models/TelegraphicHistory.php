<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TelegraphicHistory extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $guarded=[];

    protected $casts=[

        'domesticTT'    => 'boolean',

        'foreignTT'     => 'boolean',

        'otherTT'       => 'boolean',

        'pddtsDollar'   => 'boolean',

        'rtgsPeso'      => 'boolean',

    ];

    public function account(){

        return $this->belongsTo(Account::class);

    }

    public function scopeStoreTelegraphicHstry($q,$request){

        $request->request->add(

            ['transactionNo'=> $this->setPrefixSeries()

        ]);

        return $q->create($this->requestInput($request));

    }

    public function scopeUpdateTelegraphicHstry($q,$request){

        $arr = $this->requestInput($request);
        
        unset($arr['transactionNo']);

        $q->find($request->telegraphic_history_id)->update($arr);

        return $q->find($request->telegraphic_history_id);
    }

    function setPrefixSeries(){

        $res = Static::orderBy('id', 'DESC')->whereDate('created_at',Carbon::now())->first();

        if (!is_null($res)) {
            $iterate = (strtotime(date("Ymd",strtotime($res->created_at)))==strtotime(date("Ymd"))) ? (intval(Static::whereDate('created_at',Carbon::now())->count())+1) : 1;
        }else{
            $iterate = 1;
        }

        $series = 'TT'.date("y").'-'.date("md").sprintf('%02s', $iterate);

        return $series;

    }


    private function requestInput($request){

        return [
            'transactionNo'           => $request->input('transactionNo'),
            'dateTT'                  => $request->input('dateTT'),
            'branch'                  => $request->input('branch'),
            'account_id'              => $request->input('account'),
            'domesticTT'              => $request->has('domesticTT'),
            'foreignTT'               => $request->has('foreignTT'),
            'otherTT'                 => $request->has('otherTT'),
            'otherTTSpecify'          => strtoupper($request->input('otherTTSpecify')),
            'pddtsDollar'             => $request->has('pddtsDollar'),
            'rtgsPeso'                => $request->has('rtgsPeso'),
            '20_correspondent'        => strtoupper($request->input('20_correspondent')),
            '20_referenceNo'          => strtoupper($request->input('20_referenceNo')),
            '20_remittersAccountNo'   => strtoupper($request->input('20_remittersAccountNo')),
            '20_invisibleCode'        => strtoupper($request->input('20_invisibleCode')),
            '20_importersCode'        => strtoupper($request->input('20_importersCode')),
            '32a_valueDate'           => strtoupper($request->input('32a_valueDate')),
            '32a_amountAndCurrency'   => strtoupper($request->input('32a_amountAndCurrency')),
            '50_applicationName'      => strtoupper($request->input('50_applicationName')),
            '50_presentAddress'       => strtoupper($request->input('50_presentAddress')),
            '50_permanentAddress'     => strtoupper($request->input('50_permanentAddress')),
            '50_telephoneNo'          => strtoupper($request->input('50_telephoneNo')),
            '50_taxIdNo'              => strtoupper($request->input('50_taxIdNo')),
            '50_faxNo'                => strtoupper($request->input('50_faxNo')),
            '50_otherIdType'          => strtoupper($request->input('50_otherIdType')),
            '52_orderingBank'         => strtoupper($request->input('52_orderingBank')),
            '56_intermediaryBank'     => strtoupper($request->input('56_intermediaryBank')),
            '56_name'                 => strtoupper($request->input('56_name')),
            '56_address'              => strtoupper($request->input('56_address')),
            '57_beneficiaryBank'      => strtoupper($request->input('57_beneficiaryBank')),
            '57_name'                 => strtoupper($request->input('57_name')),
            '57_address'              => strtoupper($request->input('57_address')),
            '57_CountryOfDestination' => strtoupper($request->input('57_CountryOfDestination')),
            '59_beneficiaryAccountNo' => strtoupper($request->input('59_beneficiaryAccountNo')),
            '59_beneficiaryName'      => strtoupper($request->input('59_beneficiaryName')),
            '59_address'              => strtoupper($request->input('59_address')),
            '59_industryType'         => strtoupper($request->input('59_industryType')),
            '70_remittanceInfo'       => strtoupper($request->input('70_remittanceInfo')),
            '71_chargeFor'            => strtoupper($request->input('71_chargeFor')),
            '72_senderToReceiverInfo' => strtoupper($request->input('72_senderToReceiverInfo')),
            'sourceOfFund'            => strtoupper($request->input('sourceOfFund')),
            'industrytype'            => strtoupper($request->input('industrytype')),
            'registrationDate'        => strtoupper($request->input('registrationDate')),
            'birthPlace'              => strtoupper($request->input('birthPlace')),
            'nationality'             => strtoupper($request->input('nationality')),
            'natureOfWorkOrBusiness'  => strtoupper($request->input('natureOfWorkOrBusiness')),
            'purposeOrReason'         => strtoupper($request->input('purposeOrReason')),
        ];

    }
}
