// Attach shown.bs.tab event for each tab
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("data-target"); // Get the target tab ID
    
    // Refresh the DataTable based on the active tab
    if (target === "#account") { 
      companybankTable.ajax.reload(null, false); // Refreshes without resetting pagination
    } else if (target === "#draft") {
      bankHistoryTableDraft.ajax.reload(null, false);
    } else if (target === "#posted") {
      bankHistoryTable.ajax.reload(null, false);
    } else if (target === "#config") {
      telegraphicHistoryTable.ajax.reload(null, false);
    }
});


let bankHistoryTable = $("#bankHistoryTable").DataTable({
    "serverSide": true,
    paging:true,
    "ajax": {
        url: "dollarbook/bankhistory/list", 
        method: "get",
        data: function ( d ) {
            d.posted = true;
        }
    },
    // order: [[0, 'desc']],
    columns:[
    { 
        data:'transactionNo'
    },
    { 
        data:null,
        render:function(data){
            return data.types=='TOF'?"TRANSFER OF FUND":"AUTHORITY TO DEBIT"
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
            // <a href="dollarbook/fund/export/${data.id}" class="dropdown-item border"><i class="fas fa-download"></i> Download .docx file</a>
            // ${BaseModel.findPrev('DB008')?`<button type="button" class="dropdown-item border" name="editBtn_bankHistory" value="${data.id}" id="${data.types}"><i class="fas fa-edit"></i> Edit</button>`:''}
            return `<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Action</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            ${BaseModel.findPrev('DB009')?`<a href="dollarbook/fund/export/${data.id}" class="dropdown-item border"><i class="fas fa-download"></i> Download .docx file</a>`:''}
                            ${BaseModel.findPrev('DB007')?`<button type="button" class="dropdown-item border" name="print" value="${data.id}" id="${data.types}"><i class="fas fa-print"></i> Print Preview</button>`:''}
                            
                        </div>
                    </div>`
        }
    },
    ]
})


let bankHistoryTableDraft = $("#bankHistoryTableDraft").DataTable({
    "serverSide": true,
    paging:true,
    "ajax": {
        url: "dollarbook/bankhistory/list", 
        method: "get",
        data: function ( d ) {
            d.posted = false;
        }
    },
    // order: [[0, 'desc']],
    columns:[
    { 
        data:'transactionNo'
    },
    { 
        data:null,
        render:function(data){
            return data.types=='TOF'?"TRANSFER OF FUND":"AUTHORITY TO DEBIT"
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
            // ${BaseModel.findPrev('DB009')?`<a href="dollarbook/fund/export/${data.id}" class="dropdown-item border"><i class="fas fa-download"></i> Download .docx file</a>`:''}
            // ${BaseModel.findPrev('DB007')?`<button type="button" class="dropdown-item border" name="print" value="${data.id}" id="${data.types}"><i class="fas fa-print"></i> Print Preview</button>`:''}
            // 
            return `<div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px">Action</button>
                        <div class="dropdown-menu" style="font-size:11px">
                            ${BaseModel.findPrev('DB008')?`<button type="button" class="dropdown-item border" name="editBtn_bankHistory" value="${data.id}" id="${data.types}"><i class="fas fa-edit"></i> Edit</button>`:''}
                            ${BaseModel.findPrev('DB008')?`<button type="button" class="dropdown-item border" name="postBtn_bankHistory" value="${data.id}" id="${data.transactionNo}"><i class="fas fa-check-circle"></i> Post</button>`:''}
                        </div>
                    </div>`
        }
    },
    ]
})


$(document).on('click','button[name=postBtn_bankHistory]',function(){    
    let button = $(this)
    alertify.confirm(`Are you sure you want to post this? This action is not reversible. ${button.attr('id')}`, function(e){
        if (e) {
            $.ajax({
                url:`dollarbook/bankinfo/post/${button.val()}`,
                data:{
                    _token:BaseModel._token
                },
                type:'PUT',
            }).done(function(res){
                alertify.alert(`Successfully posted the transaction <b>${button.attr('id')}</b>`)
                bankHistoryTableDraft.ajax.reload();
            }).fail(BaseModel.handleAjaxError)
        } else {
            return false
        }
    });
})


$(document).on('click','button[name=print]',function(){
    console.log($(this).attr("id"));
    if ($(this).attr("id")!='TTA') {
        BaseModel.loadToPrint(`dollarbook/fund/print/${$(this).val()}`)
    } else {
        window.open(`dollarbook/fund/print-view/telegPDF/${$(this).val()}/tt2`);
    }

})


$(document).on('click','button[name=editBtn_bankHistory]',function(){

    let dataRow = bankHistoryTableDraft.row($(this).closest('tr'));

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

        $("input[name=isManual]").prop('checked',data.isManual).prop('disabled',true)

        $("input[name=amount]").val(data.amount).prop('readonly',true)

        $("input[name=subject]").val(data.subject).prop('readonly',true)

        $("select[name=toCurrencyType]").val(data.toCurrencyType).prop('readonly',true)

        $("input[name=toAccountNo]").val(data.toAccountNo).prop('readonly',true)

        $("input[name=toBankName]").val(data.toBankName).prop('readonly',true)

        $("input[name=toBranchName]").val(data.toBranchName).prop('readonly',true)

        $("input[name=toName]").val(data.toName).prop('readonly',true)

        $("input[name=types]").val(data.types).prop('readonly',true)
        
        // $('textarea[name=purposes]').summernote({
        //     'code':data.purpose
        // }).summernote('disable'); 
        // Set the content after initialization
        $('textarea[name=purposes]').summernote('code', data.purpose);

        // Then disable it
        $('textarea[name=purposes]').summernote('disable'); 

        bankFormModal.modal("show")

    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(textStatus);
    })

})


// $(document).on('click','button[name=print]',function(){
    
//     if ($(this).attr("id")!='TTA') {
//         BaseModel.loadToPrint(`dollarbook/fund/print/${$(this).val()}`)
//     } else {
//         window.open(`dollarbook/fund/print-view/telegPDF/${$(this).val()}/tt2`);
//     }

// })