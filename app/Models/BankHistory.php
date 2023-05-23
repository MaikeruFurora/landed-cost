<?php

namespace App\Models;

use App\Helper\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use function PHPUnit\Framework\isNull;

class BankHistory extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $guarded=[];

    public function setToNameAttribute($value){
        
        return $this->attributes['toName'] = strtoupper($value);

    }

    public function account(){

        return $this->belongsTo(Account::class);

    }

    public function scopeStoreBankHstry($q,$request){

        $request->request->add(

            ['transactionNo'=> $this->setPrefixSeries($request->input('types'))

        ]);

        
        return $q->create($this->requestInput($request));

    }

    function setPrefixSeries($type){

        $res = Static::orderBy('id', 'DESC')->whereDate('created_at',Carbon::now())->first();

        if (!is_null($res)) {
            $iterate = (strtotime(date("Ymd",strtotime($res->created_at)))==strtotime(date("Ymd"))) ? (intval(Static::whereDate('created_at',Carbon::now())->count())+1) : 1;
        }else{
            $iterate = 1;
        }

        $series = strtoupper($type).date("y").'-'.date("md").sprintf('%02s', $iterate);

        return $series;

    }

    public function scopeUpdateBankHstry($q,$request){

        $arr = $this->requestInput($request);
        
        unset($arr['transactionNo']);

        $q->find($request->bank_history_id)->update($arr);

        return $q->find($request->bank_history_id);

    }


    private function requestInput($request){

        return [
            'account_id'       => $request->input('account'),
            'attention'        => $request->input('attention'),
            'subject'          => $request->input('subject'),
            'transactionNo'    => $request->input('transactionNo'),
            'types'            => $request->input('types'),
            'amount'           => Helper::cleanNumberByFormat($request->input('amount')),
            'exchangeRate'     => Helper::cleanNumberByFormat($request->input('exchangeRate')),
            'exchangeRateDate' => $request->input('exchangeRateDate'),
            'toName'           => $request->input('toName'),
            'toBankName'       => $request->input('toBankName'),
            'toBranchname'     => $request->input('toBranchName'),
            'toAccountNo'      => $request->input('toAccountNo'),
            'purpose'          => $request->input('purpose'),
            'dated_at'          => $request->input('dated_at'),
        ];

    }

}
