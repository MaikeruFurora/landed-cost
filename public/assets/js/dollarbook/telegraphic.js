$('.ttOption').click(function() {
    $('.ttOption').not(this).prop('checked', false);
    $("input[name=otherTTSpecify]").prop('readonly',($(this).attr('name')!='otherTT'));
});

$("input[name=otherTT]").on('click',function(){
   $("input[name=otherTTSpecify]").prop('readonly',!($(this).is(':checked'))).prop("required",!($(this).is(':checked')));
})

//

$('.crrncyType').click(function() {
    $('.crrncyType').not(this).prop('checked', false);
});

const editTransaction = (id,type) =>{

    $.ajax({
        
        url:`dollarbook/telegraphichistory/edit/${id}`,
        
        type:'GET',
        
    }).done(function(data){

        clearForm()

        bankFormModal.find(".modal-dialog").removeClass("modal-md").addClass("modal-xl")
        $("#bankFormModalLabel").text('TELEGPAHIC TRANSFER '+ ((type=='edit')?'| Update Details':'| Copy as Template'))

        form01.hide()
        form02.show()
    
        bankFormModal.find("input[name='types']").val('TTA')
        $("input[name=telegraphic_history_id]").val(((type=='edit')?data['id'] :''))
        $("input[name=account]").val(data['account_id'])
        $("input[name=dateTT]").val(data['dateTT'])
        $("input[name=branch]").val(data['branch'])
        $("input[name=domesticTT]").prop('checked',data['domesticTT'])
        $("input[name=foreignTT]").prop('checked',data['foreignTT'])
        $("input[name=otherTT]").prop('checked',data['otherTT'])
        $("input[name=otherTTSpecify]").val(data['otherTTSpecify']).prop('readonly',(!data['otherTT']))
        $("input[name=pddtsDollar]").prop('checked',data['pddtsDollar'])
        $("input[name=rtgsPeso]").prop('checked',data['rtgsPeso'])
        $("input[name=20_correspondent]").val(data['20_correspondent'])
        $("input[name=20_referenceNo]").val(data['20_referenceNo'])
        $("input[name=20_remittersAccountNo]").val(data['20_remittersAccountNo'])
        $("input[name=20_invisibleCode]").val(data['20_invisibleCode'])
        $("input[name=20_importersCode]").val(data['20_importersCode'])
        $("input[name=32a_valueDate]").val(data['32a_valueDate'])
        $("input[name=32a_amountAndCurrency]").val(((type=='edit')?data['32a_amountAndCurrency'] :''))
        $("input[name=50_applicationName]").val(data['50_applicationName']).attr('readonly',(type!='edit'))
        $("textarea[name=50_presentAddress]").val(data['50_presentAddress']).attr('readonly',(type!='edit'))
        $("textarea[name=50_permanentAddress]").val(data['50_permanentAddress'])
        $("input[name=50_telephoneNo]").val(data['50_telephoneNo'])
        $("input[name=50_taxIdNo]").val(data['50_taxIdNo']).attr('readonly',(type!='edit'))
        $("input[name=50_faxNo]").val(data['50_faxNo'])
        $("input[name=50_otherIdType]").val(data['50_otherIdType'])
        $("input[name=52_orderingBank]").val(data['52_orderingBank'])
        $("input[name=56_intermediaryBank]").val(data['56_intermediaryBank'])
        $("input[name=56_name]").val(data['56_name'])
        $("textarea[name=56_address]").val(data['56_address'])
        $("input[name=57_beneficiaryBank]").val(data['57_beneficiaryBank'])
        $("input[name=57_name]").val(data['57_name'])
        $("textarea[name=57_address]").val(data['57_address'])
        $("input[name=57_CountryOfDestination]").val(data['57_CountryOfDestination'])
        $("input[name=59_beneficiaryAccountNo]").val(data['59_beneficiaryAccountNo'])
        $("input[name=59_beneficiaryName]").val(data['59_beneficiaryName'])
        $("textarea[name=59_address]").val(data['59_address'])
        $("input[name=59_industryType]").val(data['59_industryType'])
        $("textarea[name=70_remittanceInfo]").val(data['70_remittanceInfo'])
        $("select[name=71_chargeFor]").val(data['71_chargeFor'])
        $("textarea[name=72_senderToReceiverInfo]").val(data['72_senderToReceiverInfo'])
        $("input[name=sourceOfFund]").val(data['sourceOfFund'])
        $("input[name=industrytype]").val(data['industrytype'])
        $("input[name=registrationDate]").val(data['registrationDate'])
        $("input[name=birthPlace]").val(data['birthPlace'])
        $("input[name=nationality]").val(data['nationality'])
        $("input[name=natureOfWorkOrBusiness]").val(data['natureOfWorkOrBusiness'])
        $("textarea[name=purposeOrReason]").val(data['purposeOrReason'])
        $("input[name=debitFromAccount]").val(data.account.accountNo)
        console.log(data);
        bankFormModal.modal("show")

    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(textStatus);
    })
}

let telegraphicHistoryTable = $("#telegraphicHistoryTable").DataTable({
    "serverSide": true,
    "responsive": true,
    "paging":true,
    "ajax": {
        url: "dollarbook/telegraphichistory/list", 
        method: "get"
    },
    // order: [[0, 'desc']],
    columns:[
        { 
            data:'transactionNo'
        },
        { 
            data:'32a_valueDate'
        },
        { 
            data:'32a_amountAndCurrency'
        },
        { 
            data:'50_applicationName'
        },
        { 
            data:'57_beneficiaryBank'
        },
        { 
            data:'57_name'
        },
        { 
            data:'59_beneficiaryAccountNo'
        },
        
    { 
        data:null,
        render:function(data){
            return `<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Action</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            ${BaseModel.findPrev('DB008')?`<button type="button" class="dropdown-item border" name="copy_template" value="${data.id}" data-press="copy" id="TTA"><i class="fas fa-copy"></i> Copy as Template</button>`:''}
                            ${BaseModel.findPrev('DB011')?`<button type="button" class="dropdown-item border" name="print_template" value="${data.id}" id="TTA"><i class="far fa-file-powerpoint"></i> Print Template</button>`:''}
                            ${BaseModel.findPrev('DB007')?`<button type="button" class="dropdown-item border" name="print" value="${data.id}" data-press="copy" id="TTA"><i class="fas fa-print"></i> Print Preview</button>`:''}
                            ${BaseModel.findPrev('DB008')?`<button type="button" class="dropdown-item border" name="editBtn_TelegraphicHistory" value="${data.id}" data-press="edit" id="TTA"><i class="fas fa-edit"></i> Edit</button>`:''}
                            
                        </div>
                    </div>`
        }
    },
    ]
})

$(document).on('click','button[name=print_template]',function(){

    let id = $(this).val();
    
    window.open(`dollarbook/fund/print-view/telegPDF/${id}/ttform2`);

})


$(document).on('click','button[name=editBtn_TelegraphicHistory]',function(){

    editTransaction(
        $(this).val(),
        $(this).attr('data-press')
    )

})


$(document).on('click','button[name=copy_template]',function(){

    editTransaction(
        $(this).val(),
        $(this).attr('data-press')
    )

})


$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
    // var target = $(e.target).attr("href"); // activated tab
    // alert (target);
    $($.fn.dataTable.tables( true ) ).css('width', '100%');
    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    console.log('click');
} ); 
