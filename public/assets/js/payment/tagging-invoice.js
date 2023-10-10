$(document).on('click','button[name=editPaymentDetail]',function(){
    cancelpaymentDetail.show()
    console.log($(this).closest('tr').find('td')[0].innerText);
    let tdd = $(this).closest('tr')
    $("input[name=id]").val($(this).val())
    $("input[name=exchangeDate]").val(tdd.find('td')[0].innerText)
    $("input[name=dollar]").val(tdd.find('td')[1].innerText)
    $("input[name=exchangeRate]").val(tdd.find('td')[2].innerText)
    $("input[name=totalAmountInPHP]").val(tdd.find('td')[3].innerText)
    $("input[name=totalPercentPayment]").val(tdd.find('td')[4].innerText)
})



ConPay.seachInvoiceUnderPaymentForm.on('submit',function(e){    
    e.preventDefault()
    if($('input[name="search"]').val()!=""){
        $.ajax({
            url:ConPay.seachInvoiceUnderPaymentForm.attr("action"),
            type: "GET",
            data:{
                search:ConPay.seachInvoiceUnderPaymentForm.find('input[name="search"]').val(),
            },
        }).done(function(data){
            console.log(data);
            dataTableInvoice(data)
            $("#formSearchInvoice *").prop("disabled", false);
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log((jqxHR, textStatus, errorThrown));
        })
    }else{  alert('Please do not leave blank') }
    
})


const dataTableInvoice = (data) =>{
    ConPay.invoiceTable.DataTable({
        initComplete: function() {
            this.api().columns.adjust()
         },
        paging:true,
        searching:false,
        ordering:false,
        destroy:true,
        data:data,
        columns:[
            { data: 'suppliername', visible: false, },
            { data: 'itemcode', visible: false, },
            { data: 'containerno', visible: false, },
            { data: 'pono', visible: false, },
            { data: 'cardname', visible: false, },
            { data: 'cardcode', visible: false, },
            { data: 'vessel', visible: false, },
            { data: 'broker', visible: false, },
            { data: 'createdate', visible: false, },
            { data: 'docdate', visible: false, },
            { data: 'weight', visible: false, },
            { data: 'uom', visible: false, },
            { data: 'blno', visible: false, },
            { data: 'doc_date', visible: false, },
            { data:'invoiceno'},
            { data:'description'},
            { data:'quantity'},
            { data:'qtykls'},
            { data:'qtymt'},
            { data:'fcl'},
            { data: null,
                render:function(data){
                    return `<button value="${data.invoiceno}" name="invoiceno" class="btn btn-sm btn-primary btn-block text-center"><i class="fas fa-plus-circle"></i> Tag</button>`
                }
            },
        ],
    })
}

$(document).on('click','button[name=invoiceno]',function(){
    
    $.ajax({
        url: ConPay.invoiceTable.attr("data-url"),
        type: "POST",
        data:{
            _token:BaseModel._token,
            invoiceno:$(this).val(),
            
        },
    }).done(function(data){
        dataTableInvoice([])
        toasMessage('Information','Successfully save the transaction','info')
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
})




dataTableInvoice([])

