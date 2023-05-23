<br>
<br>
<p>{{ is_null($data->exchangeRateDate)? date("F j, Y",strtotime($data->created_at)) : date("F j, Y",strtotime($data->exchangeRateDate)) }}</p>
<br>
<h3 class="mb-0">{{ $data->account->branch->bank->bankName }}</h3>
<p class="mt-0">{{ $data->account->branch->branchName }}</p>
<br>
<p>Attention : {{  $data->attention }}</p>