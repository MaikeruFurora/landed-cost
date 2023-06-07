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
            //<a href="dollarbook/fund/export/${data.id}" class="dropdown-item border"><i class="fas fa-download"></i> Download .docx file</a>
            return `<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Action</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            ${BaseModel.findPrev('DB009')?`<a href="dollarbook/fund/export/${data.id}" class="dropdown-item border"><i class="fas fa-download"></i> Download .docx file</a>`:''}
                            ${BaseModel.findPrev('DB007')?`<button type="button" class="dropdown-item border" name="print" value="${data.id}" id="${data.types}"><i class="fas fa-print"></i> Print Preview</button>`:''}
                            ${BaseModel.findPrev('DB008')?`<button type="button" class="dropdown-item border" name="editBtn_bankHistory" value="${data.id}" id="${data.types}"><i class="fas fa-edit"></i> Edit</button>`:''}
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

    let dataRow = bankHistoryTable.row($(this).closest('tr'));

    bankFormModal.find(".my-card-header em").text(dataRow.data()['companyname'])

    bankFormModal.find(".modal-dialog").removeClass("modal-xl").addClass("modal-md")

    $.ajax({
        
        url:`dollarbook/bankinfo/edit/${$(this).val()}`,
        
        type:'GET',
        
    }).done(function(data){

        clearForm()

        form01.show()

        form02.hide()

        if (data.types=='AOD') {
            toCardAccount.hide()
            bankFormModal.find(".aod-requisite").show()
            autoComputeUSDAmount(data.amount,data.exchangeRate)
        } else {
            toCardAccount.show()
            bankFormModal.find(".aod-requisite").hide()
        }

        $("input[name=dated_at]").val((data.exchangeRateDate!=null)? data.exchangeRateDate : data.dated_at)
        
        $(".prefixCurrency").text(data.account.currencyType)

        $("#bankFormModalLabel").text('EDIT & UPDATE | ' + data.types)
        
        $("input[name=bank_history_id]").val(data.id)

        $("input[name=account]").val(data.account_id)

        $("input[name=attention]").val(data.attention).prop('readonly',true)

        $("input[name=exchangeRate]").val(data.exchangeRate).prop('readonly',true)
        
        $("input[name=exchangeRateDate]").val(data.exchangeRateDate).prop('readonly',true)

        $("input[name=phpAmount]").val(data.exchangeRate*data.amount).prop('readonly',true)

        $("input[name=amount]").val(data.amount).prop('readonly',true)

        $("input[name=subject]").val(data.subject).prop('readonly',true)

        $("select[name=toCurrencyType]").val(data.toCurrencyType).prop('readonly',true)

        $("input[name=toAccountNo]").val(data.toAccountNo).prop('readonly',true)

        $("input[name=toBankName]").val(data.toBankName).prop('readonly',true)

        $("input[name=toBranchName]").val(data.toBranchName).prop('readonly',true)

        $("input[name=toName]").val(data.toName).prop('readonly',true)

        $("input[name=types]").val(data.types).prop('readonly',true)

        $('textarea[name=purposes]').summernote({
            'code':data.purpose
        }).summernote('disable');  

        bankFormModal.modal("show")

    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(textStatus);
    })

})