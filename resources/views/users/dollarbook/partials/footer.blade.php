<p class="mt-4">Thank you.</p>
<br>
<p class="mt-5">Very truly yours,</p>
<br>
<br>
@if ($data->types=='AOD')
<h5 class="font-weight-bold">JOSEPH O. YAO/ AZUCENA YAO/ ANNIKA Y. YAO/ JANDRICK YAO/ JOHAN YAO</h5>
@else
<class="font-weight-bold">
<h4>
    @if ($data->account->accountNo=='001500229588') AZUCENA YAO/ ANNIKA SHERRYN LAO @else JOSEPH O. YAO/ AZUCENA YAO/ ANNIKA SHERRYN LAO/ JANDRICK YAO/ JOHAN YAO @endif
</h4>
@endif