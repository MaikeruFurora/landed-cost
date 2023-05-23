<br>
<br>
<p>{{ date("F j, Y",strtotime($data->dated_at)) }}</p>
<br>
<h3 class="mb-0">{{ $data->account->branch->bank->bankName }}</h3>
<p class="mt-0">{{ $data->account->branch->branchName }}</p>
<br>
<p>Attention : {{  $data->attention }}</p>