let bankFormModal   = $("#bankFormModal")

let FundForm        = $("#FundForm")

let toCardAccount   = $("#toCardAccount")

let form01          = $(".form01")

let form02          = $(".form02")

let dated_at        = $('input[name=dated_at]')

let exchangeRateDate= $('input[name=exchangeRateDate]')

let isManual      = $('input[name=isManual]')

$('input[name=amount]').number( true, 4 );
$('input[name=exchangeRate]').number( true, 4 );
$('input[name=phpAmount]').number( true, 4 );

exchangeRateDate.datepicker({
    toggleActive: true,
    autoclose: true,
    format: "yyyy-mm-dd",
    startDate: new Date()
}).on('change',function(){
    dated_at.val($(this).val())
});

dated_at.datepicker({
    toggleActive: true,
    autoclose: true,
    format: "yyyy-mm-dd",
    // startDate: new Date()
}).on('change',function(){
    exchangeRateDate.val($(this).val())
});

$('#date-range').datepicker({
    toggleActive: true
});

const clearForm = () =>{
    
    FundForm[0].reset()

    $("#FundForm *").prop("readonly", false);

    $("#FundForm input[name=isManual]").prop("disabled", false);

    $("input[name=otherTTSpecify]").prop('readonly',true)
    
    $("input[name=bank_history_id]").val('')

    $("input[name=telegraphic_history_id]").val('')

    $("input[name=account]").val('')
        
    $('textarea[name=purposes]').summernote('reset','enable');  
}

const avoidNegative = (value)=>{
    if (parseFloat(value.val())<0) {
        value.val(0)
    }
}

const autoComputePHPAmount = (amnt,exRate) => {
    if (!isManual.is(':checked')) {
        $("input[name=phpAmount]").val((exRate!="" || amnt!="") ? ((parseFloat(exRate)>0 && parseFloat(amnt)>0) ? (amnt*exRate) : 0 ) : 0)
    }
        $(".rateAmount").text((exRate!="" || exRate<0) ?  ((parseFloat(exRate)>0 && parseFloat(amnt)>0) ?exRate:0) : 0).number( true, 2 );
        $(".usdAmount").text((exRate!="" || amnt!="")  ? ((parseFloat(exRate)>0 && parseFloat(amnt)>0) ? (amnt/exRate) : 0 ) : 0).number( true, 2 );
}

const autoComputeUSDAmount = (amnt,exRate) => {
    if (!isManual.is(':checked')) {
        $("input[name=amount]").val((exRate!="" || amnt!="") ? ((parseFloat(exRate)>0 && parseFloat(amnt)>0) ? (amnt/exRate) : 0 ) : 0)
    }
    $(".rateAmount").text((exRate!="" || exRate<0) ? ((parseFloat(exRate)>0 && parseFloat(amnt)>0) ?exRate:0) : 0).number( true, 2 );
    $(".usdAmount").text((exRate!="" || amnt!="") ? ((parseFloat(exRate)>0 && parseFloat(amnt)>0) ?amnt:0) : 0).number( true, 2 );
}

isManual.on('click',function(){
    $("input[name=phpAmount]").val('')
    let ex   = $("input[name=exchangeRate]");
    let amnt = $("input[name=amount]");
    if (!$(this).is("checked")) {
        autoComputePHPAmount(amnt.val(),ex.val())
    }else{
        $("input[name=phpAmount]").val('')
    }
})

let companybankTable = $("#companybankTable").DataTable({
    "serverSide": true,
    paging:true,
    "ajax": {
        url: "dollarbook/bankinfo/list", 
        method: "get"
    },
    // order: [[0, 'desc']],
    columns:[
    {
        data: "currencyType",
        target: 0,
        visible: false,
        searchable: false
    },
    {
        data: "registrationDate",
        target: 0,
        visible: false,
        searchable: false
    },
    {
        data: "tinNo",
        target: 0,
        visible: false,
        searchable: false
    },
    {
        data: "companyAddress",
        target: 0,
        visible: false,
        searchable: false
    },
    {
        data: "accountNo",
        target: 1,
        visible: false,
        searchable: false
    },
    { 
        data:'companyname'
    },
    // { 
    //     data:'bankName'
    // },
    // { 
    //     data:'branchName'
    // },
    { 
        data:null,
        render:function(data){
            return `${data.accountNo} <b>(${data.currencyType})</b>`
        }
    },
    { 
        data:null,
        render:function(data){
            return `<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Action</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            ${
                            
                                data.currencyType!='PHP' ? `<button type="button" class="dropdown-item border" name="aod" value="${data.id}"><i class="fas fa-caret-right"></i>&nbsp;Authority to Debit</button>`:''

                            }
                            <button type="button" class="dropdown-item border" name="tof" value="${data.id}"><i class="fas fa-caret-right"></i>&nbsp;Transfer of Fund</button>
                            <button type="button" class="dropdown-item border" name="tta" value="${data.id}"><i class="fas fa-caret-right"></i>&nbsp;Telegraphic Transfer</button>
                        </div>
                    </div>`
        }
    },
    ]
})

const generalSetting = (data,aodRequisite=false,datedAt=false,types,needToAccnt=false,modalLabel)=>{
    clearForm()
    bankFormModal.find(".my-card-header em").text(data.data()['companyname'])
    bankFormModal.find(".prefixCurrency").text(data.data()['currencyType'])
    bankFormModal.find(".modal-dialog").removeClass("modal-xl").addClass("modal-md")
    bankFormModal.find("input[name='subject']").val('REQUEST TO DEBIT ACCOUNT')
    bankFormModal.find("input[name='attention']").val('Ms. Vanessa')
    bankFormModal.find("input[name='types']").val(types)
    $("#bankFormModalLabel").text(modalLabel)
    dated_at.prop('readonly',datedAt)
    if (aodRequisite) {  bankFormModal.find(".aod-requisite").show() }else{  bankFormModal.find(".aod-requisite").hide() }
    if (needToAccnt) {   toCardAccount.hide() } else {  toCardAccount.show()  }
    form01.show()
    form02.hide()
    bankFormModal.modal("show")
}

$(document).on('click','button[name=aod]',function(){
    let dataRow = companybankTable.row($(this).closest('tr'));
    generalSetting(dataRow,true,true,'AOD',true,'AUTHORITY TO DEBIT')
    bankFormModal.find("input[name='account']").val($(this).val())
})

$(document).on('click','button[name=tof]',function(){
    let dataRow = companybankTable.row($(this).closest('tr'));
    generalSetting(dataRow,false,false,'TOF',false,'TRANSFER OF FUND')
    bankFormModal.find("input[name='account']").val($(this).val())
})

$(document).on('click','button[name=tta]',function(){
    clearForm()
    let dataRow         = companybankTable.row($(this).closest('tr'));
    $("input[name=50_applicationName]").val(dataRow.data()['companyname'])
    $("textarea[name=50_presentAddress]").val(dataRow.data()['companyAddress'])
    $("input[name=debitFromAccount]").val(dataRow.data()['accountNo'])
    $("input[name=50_taxIdNo]").val(dataRow.data()['tinNo'])
    $("input[name=registrationDate]").val(dataRow.data()['registrationDate'])
    bankFormModal.find(".modal-dialog").removeClass("modal-md").addClass("modal-xl")
    $("#bankFormModalLabel").text('TELEGPAHIC TRANSFER')
    bankFormModal.find("input[name='account']").val($(this).val())
    bankFormModal.find("input[name='types']").val('TTA')
    form01.hide()
    form02.show()
    bankFormModal.modal("show")
})

$("input[name=exchangeRate]").on('input',function(){
    let amnt   = $("input[name=amount]").val()
    avoidNegative($(this))
    autoComputePHPAmount(amnt,$(this).val())
})

$('input[name=amount]').on('input',function(){
    let exRate = $("input[name=exchangeRate]").val()
    avoidNegative($(this))
    autoComputePHPAmount($(this).val(),exRate)
    
})

$("input[name=phpAmount]").on('input',function(){
    let exRate = $("input[name=exchangeRate]").val()
    autoComputeUSDAmount($(this).val(),exRate)
    avoidNegative($(this))
})

$('textarea[name=purposes]').summernote({
    disableDragAndDrop : true,
    toolbar: false,
    height: 100,                 // set editor height
    minHeight: null,             // set minimum height of editor
    maxHeight: null,             // set maximum height of editor
    focus: false,                // set focus to editable area after initializing summernote
    callbacks: {
        onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
    }
});



$("button[name=saveAndPrint]").on('click',function(e){
    e.preventDefault()
    return StoreBankForm(true)
})

$("button[name=save]").on('click',function(e){
    e.preventDefault()
    return StoreBankForm(false)
})

const StoreBankForm = (toPrint=false)=>{

    let cleanText  = $("textarea[name=purposes]").summernote("code")
    // cleanText.replace('<p>', '<p style="margin:0">')
    let resCleanText = cleanText.replace('<p>', '').replace('</p>', '')

    const formData = new FormData(FundForm[0])

    formData.append('purpose',resCleanText)

    $.ajax({
        url:`dollarbook/fund/store`,
        type:'POST',
        data:formData,
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        console.log(data);
        if (toPrint) {
            if (bankFormModal.find("input[name='types']").val()!='TTA') {
                BaseModel.loadToPrint(`dollarbook/fund/print/${data.id}`)
            } else {
                window.open(`dollarbook/fund/print-view/telegPDF/${data.id}/tt2`);
            }
        }
        
        bankFormModal.modal("hide")
        
        console.log(data.id);
        
        clearForm()
        
        bankHistoryTable.ajax.reload()
        telegraphicHistoryTable.ajax.reload()
        
    }).fail(function (jqxHR, textStatus, errorThrown) {
        
        toasMessage(textStatus,textStatus,"danger")
        
    })

}

// dollarBookreport

$("#reportForm").on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:`dollarbook/report`,
        type:'POST',
        data:new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        
        console.log(data);

    }).fail(function (jqxHR, textStatus, errorThrown) {
        
        toasMessage(textStatus,textStatus,"danger")
        
    })
})

const searchType = (target1) => {    
    target1.typeahead({  
        source:  function (query, process) {  
        return $.get('dollarbook/company-details', { query: query }, function (data) {  
                return process(data.map((x => x.toAccountNo+' & '+x.toname)));
            });  
        },
        afterSelect: function (data) {
            let value = data.split(" & ")
            $("input[name=toAccountNo]").val(value[0])
            $("input[name=toName]").val(value[1])
        },
        autoSelect: true
    }).on('keyup',function(){
        let valData = $(this).val().length
        if (valData==0) {
            $("input[name=toAccountNo]").val('')
            $("input[name=toName]").val('')
        } 
    })

}

searchType($("input[name=toAccountNo]"))
searchType($("input[name=toName]"))
