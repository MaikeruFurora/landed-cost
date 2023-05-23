let bankHistoryTable = $("#bankHistoryTable").DataTable({
    "serverSide": true,
    paging:true,
    "ajax": {
        url: "dollarbook/bankhistory/list", 
        method: "get"
    },
    // order: [[0, 'desc']],
    columns:[
    { 
        data:'transactionNo'
    },
    { 
        data:null,
        render:function(data){
            return data.types=='TOF'?"TELEGRAPHIC OF FUND":"AUTHORITY TO DEBIT"
        }
    },
    { 
        data:null,
        render:function(data){
            return data.currencyType+' '+data.amount
        }
    },
    { 
        data:'companyname'
    },
    { 
        data:null,
        render:function(data){
            return data.toName!=""?data.toName:data.toAccountNo
        }
    },
    { 
        data:null,
        render:function(data){
            return `<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Action</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            <a href="dollarbook/fund/export/${data.id}" class="dropdown-item border"><i class="fas fa-download"></i> Download .docx file</a>
                            <button type="button" class="dropdown-item border" name="print" value="${data.id}" id="${data.types}"><i class="fas fa-print"></i> Print Preview</button>
                            <button type="button" class="dropdown-item border" name="editBtn_bankHistory" value="${data.id}" id="${data.types}"><i class="fas fa-edit"></i> Edit</button>
                        </div>
                    </div>`
        }
    },
    ]
})

$(document).on('click','button[name=print]',function(){
    
    if ($(this).attr("id")!='TTA') {
        BaseModel.loadToPrint(`dollarbook/fund/print/${$(this).val()}`)
    } else {
        window.open(`dollarbook/fund/print-view/telegPDF/${$(this).val()}/tt2`);
    }

})

$(document).on('click','button[name=editBtn_bankHistory]',function(){

    $.ajax({
        
        url:`dollarbook/bankinfo/edit/${$(this).val()}`,
        
        type:'GET',
        
    }).done(function(data){
        console.log(data.exchangeRate*data.amount);

        clearForm()

        bankFormModal.find(".modal-dialog").removeClass("modal-xl").addClass("modal-md")

        form01.show()

        form02.hide()

        if (data.types=='AOD') {
            toCardAccount.hide()
            bankFormModal.find(".aod-requisite").show()
            autoComputeUSDAmount(data.amount,data.exchangeRate)
        } else {
            bankFormModal.find(".aod-requisite").hide()
        }
        
        $(".prefixCurrency").text(data.account.currencyType)

        $("#bankFormModalLabel").text('EDIT & UPDATE | ' + data.types)
        
        $("input[name=bank_history_id]").val(data.id)

        $("input[name=account]").val(data.account_id)

        $("input[name=attention]").val(data.attention)

        $("input[name=exchangeRate]").val(data.exchangeRate)

        $("input[name=exchangeRateDate]").val(data.exchangeRateDate)

        $("input[name=phpAmount]").val(data.exchangeRate*data.amount)

        $("input[name=amount]").val(data.amount)

        $("input[name=subject]").val(data.subject)


        $("select[name=toCurrencyType]").val(data.toCurrencyType)

        $("input[name=toAccountNo]").val(data.toAccountNo)

        $("input[name=toBankName]").val(data.toBankName)

        $("input[name=toBranchName]").val(data.toBranchName)

        $("input[name=toName]").val(data.toName)

        $("input[name=types]").val(data.types)

        $('textarea[name=purposes]').summernote('code',data.purpose)

        bankFormModal.modal("show")

    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(textStatus);
    })

})