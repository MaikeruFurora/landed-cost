//payment Invoice
let shipContractTable;
let invoicePaymentDetailTable;
let applyPayment    = $(".applyPayment")
let shipPay         = $("#shipmentPaymentForm")
let shipTbl         = $("#shipContractTable")
let invPayDetailForm  = $("#invoicePaymentDetailForm")



const invoicePayment = (contract_payment) =>{

    shipContractTable = shipTbl.DataTable({
        serverSide: true,
        searching:false,
        ordering:false,
        paging:false,
        destroy:true,
        ajax: {
            url: shipTbl.attr("data-url").replace("cp",contract_payment),
            method: "get"
        },
        language: {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        }, 
        columnDefs: [
            {
                "targets": 5, // your case first column
                "className": "text-center",
           },
         ],
        // order: [[0, 'desc']],
        columns:[
            { data:'reference' },
            { data:'metricTon' },
            { data:'priceMetricTon' },
            { data:'amountUSD' },
            { 
                data:null,
                render:function(data){
                    return data.invoicePayDetail.reduce((total,value)=>total+parseFloat(value.totalPercentPayment),0).toFixed(2)+` %`;
                }
            },
            { 
                data:null,
                render:function(data){
                    let per = Math.round(parseFloat(data.invoicePayDetail.reduce((total,value)=>total+parseFloat(value.totalPercentPayment),0)));
                    return `${((data.invoiceno!=null || per==100)
                                ?  (data.invoiceno!=null)?`<a target="_blank" href="${shipTbl.attr("data-cost").replace("invoice",data.invoice_id)}">${data.invoiceno}</a>` : ''
                                :  `<button type="button" name="applyPayment" value="${data.id}" class="btn btn-sm btn-outline-primary">Apply Payment</button>`)}
                            ${
                               (data.invoicePayDetail.length==0 && data.otherPaymentDetail.length==0)
                                ?`<button type="button" 
                                         name="deleteInvoiceDetail" 
                                         value="${data.id}"
                                         class="btn btn-sm btn-outline-danger">Remove</button>`
                                :((data.invoiceno==null)?(per==100)?(`<button type="button" name="applyPayment" value="${data.id}" class="btn btn-sm btn-outline-primary">Apply Payment</button>
                                <button value="${data.id}" name="searchInvoice" type="button" class="btn btn-outline-primary btn-sm">Invoice</button>`):'':'')
                            }`
                }
            }
        ],
        
        
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
          
            var monTotal = api
            .column( 1 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
                
            var tueTotal = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 )  /  api.column( 2 ).data().count();
                
                
            var thuTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            
                
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 1 ).footer() ).html($.number(monTotal,2));
            $( api.column( 2 ).footer() ).html('Avg: '+$.number(tueTotal,2));
        },
        
    })

    
}

shipPay.find("input[name=metricTon]").on('input',function(){
    shipPay.find("input[name=amountUSD]").val(this.value*shipPay.find("input[name=priceMetricTon]").val())
})

shipPay.find("input[name=priceMetricTon]").on('input',function(){
    shipPay.find("input[name=amountUSD]").val(this.value*shipPay.find("input[name=metricTon]").val())
})

shipPay.on("submit",function(e){
    e.preventDefault()
    $.ajax({
        url:  $(this).attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        if (data.msg) {
            shipPay[0].reset()
            invoicePayment(shipPay.find("input[name=contract_payment]").val())
            toasMessage(data.msg,"success")
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})

$(document).on('click','button[name=deleteInvoiceDetail]',function(e){
    e.preventDefault()
    $.ajax({
        url:  shipTbl.attr("data-remove").replace("ip",$(this).val()),
        type:'GET',
    }).done(function(data){
        applyPayment.hide()
        invoicePayment(shipPay.find("input[name=contract_payment]").val())
        toasMessage("Removed","success")
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})

$(document).on('click','button[name=applyPayment]',function(){
    var $row = $(this).closest('tr'); // Find the parent row
    $('#shipContractTable tbody tr').removeClass('highlight-yellow');
    // Add or remove the 'highlight-yellow' class to change the row color
    $row.toggleClass('highlight-yellow');
    let data = shipContractTable.row( $(this).closest('tr') ).data()
    invPayDetailForm.find("input[name=invoice_payment]").val($(this).val())
    Other.form.find("input[name=invoice_payment]").val($(this).val())
    invPayDetailForm.find("input[name=invoice_amountUSD]").val(data.amountUSD)
    $(".selectedInvoicePaymentReference").text(data.reference)
    invPayDetailForm[0].reset()
    cancelpaymentInvoicePayDetail.hide()
    applyPayment.show()
    invoicePaymentDetail(data.id)
    invoiceOtherPaymentDetail(data.id)
    // Disabled if 100 percent payment
    $("#invoicePaymentDetailForm *").prop("disabled", data.invoicePayDetail.reduce((total,value)=>total+parseFloat(value.totalPercentPayment),0)==100);
})

let cancelpaymentInvoicePayDetail= $('#invoicePaymentDetailTable').find("button[name=cancelButton]")

cancelpaymentInvoicePayDetail.on('click',function(){
    invPayDetailForm[0].reset()
    invPayDetailForm.find('input[name=id]').val('')
    $(this).hide()
}).hide()

$(".applyPaymentAction").on('click',function(){
    applyPayment.hide()
    $('#shipContractTable tbody tr').removeClass('highlight-yellow');
    $('#invoicePaymentDetailTable').DataTable().clear().destroy();
})

invPayDetailForm.find("input[name=dollar]").on('input',function(){
    invPayDetailForm.find("input[name=totalAmountInPHP]").val(this.value*invPayDetailForm.find("input[name=exchangeRate]").val())
})

invPayDetailForm.find("input[name=exchangeRate]").on('input',function(){
    invPayDetailForm.find("input[name=totalAmountInPHP]").val(this.value*invPayDetailForm.find("input[name=dollar]").val())
})

invPayDetailForm.find('input[name=exchangeRate]').on('input',function(){
    invPayDetailForm.find('input[name=totalAmountInPHP]').val(this.value*invPayDetailForm.find('input[name=dollar]').val())
    invPayDetailForm.find('input[name=totalPercentPayment]').val(
        // invPayDetailForm.find('input[name=dollar]').val()
        )
})

invPayDetailForm.find('input[name=exchangeRate]').on('input',function(){
    invPayDetailForm.find('input[name=totalAmountInPHP]').val(this.value*invPayDetailForm.find('input[name=dollar]').val())
})

invPayDetailForm.find('input[name=dollar]').on('input',function(){
    invPayDetailForm.find('input[name=totalAmountInPHP]').val(this.value*invPayDetailForm.find('input[name=exchangeRate]').val())
    invPayDetailForm.find('input[name=totalPercentPayment]').val(
        (invPayDetailForm.find('input[name=dollar]').val()/parseFloat(invPayDetailForm.find("input[name=invoice_amountUSD]").val().replaceAll(",","")))*100
    )

})

invPayDetailForm.on("submit",function(e){
    e.preventDefault()
    $.ajax({
        url:  $(this).attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        if (data.msg) {
            invPayDetailForm[0].reset()
            invPayDetailForm.find("input[name=id]").val('')
            cancelpaymentInvoicePayDetail.hide()
            invoicePayment(shipPay.find("input[name=contract_payment]").val())
            invoicePaymentDetail(invPayDetailForm.find("input[name=invoice_payment]").val())
            toasMessage(data.msg,"success")
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})

const invoicePaymentDetail = (invoice_payment) =>{

     invoicePaymentDetailTable = $("#invoicePaymentDetailTable").DataTable({
        serverSide: true,
        searching:false,
        ordering:false,
        paging:false,
        destroy:true,
        "ajax": {
            url: $("#invoicePaymentDetailTable").attr("data-url").replace("ip",invoice_payment),
            method: "get"
        },
        language: {
            'loadingRecords': '&nbsp;',
            'processing': 'Loading...'
        },  
        // order: [[0, 'desc']],
        columns:[
            { 
                data:null,
                render:function(data){
                    return data.exchangeDate +' '+(data.partial==1?'<small class="text-danger"><b>(PARTIAL FROM ADV PAYMT.)</b></small>':'')
                }
            },
            { data:'dollar' },
            { data:'exchangeRate' },
            { data:'totalAmountInPHP' },
            { data:'totalPercentPayment' },
            { 
                data:null,
                render:function(data){
                    return `<button
                    name="editInvoicePaymentDetail"
                    value="${data.payment_detail.id}"
                    type="button"
                    class="btn btn-outline-secondary btn-sm btn-block">
                    <i class="fas fa-plus-circle"></i> Edit
                    </button>
                    `
                }
            },
            { 
                visible:false,
                data:'partial',
            },
        ],
        rowCallback: function(row, data) {
            if (data.partial === '1') { // Check the 'highlight' flag
                $(row).addClass('highlight'); // Add a CSS class to highlight the row
            }
        },
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var monTotal = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            var tueTotal = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 ) /  api.column( 2 ).data().count();
                
            var wedTotal = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            var thuTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            var friTotal = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
                
            // Update footer by showing the total with the reference of the column index 
        $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 1 ).footer() ).html($.number(monTotal,2));
            $( api.column( 2 ).footer() ).html('Avg. '+$.number(tueTotal,2));
            $( api.column( 3 ).footer() ).html($.number(wedTotal,2));
            $( api.column( 4 ).footer() ).html(Math.round(thuTotal));
        },
        
    })

}

$(document).on('click','button[name=shipmentPayment]',function(){
    shipPay.find("input[name=contract_payment]").val($(this).val())
    ConPay.modalInvoicePayment.modal({
        keyboard: false
    });
    let data = tableCon.row( $(this).closest('tr') ).data()
    detailView(data)
    invoicePayment($(this).val())
    $(".selectedInvoicePaymentReference").text('')
    $('#invoicePaymentDetailTable').DataTable().clear().destroy();
    applyPayment.hide()
})

$(document).on('click','button[name=editInvoicePaymentDetail]',function(){
    let data = invoicePaymentDetailTable.row( $(this).closest('tr') ).data()
    $.each($('#invoicePaymentDetailForm .form-control'),(ind, value) => {
        console.log(parseInt(data[value.name]));
        invPayDetailForm.find("input[name="+value.name+"]").val(data[value.name])
    });
    cancelpaymentInvoicePayDetail.show()
    cancelpaymentInvoicePayDetail.prop('disabled',false)
    invPayDetailForm.find("input[name=id]").val($(this).val())
    invPayDetailForm.find("input[name=partial]").prop("checked",data.partial==1)
})

// $(document).on('click',".searchInvoice",function(){
//     console.log('dasd',$(this).val());
//     $(this).webuiPopover('destroy').webuiPopover($.extend({},{
//         title:'Search Invoice',
//         closeable:true,
//         trigger:'manual',
//         html: true, // Enable HTML content
//         content: $('#customPopover').html()
//     })).webuiPopover('show');
// })


/***
 * 
 * SEARCH INVOICE
 * 
 * 
 * 
 */
				
$(document).on('click',"button[name=searchInvoice]",function(){
    $("#searchInvoiceForm")[0].reset()
    $("#searchInvoiceTable").DataTable().clear().draw()
    $("#modalPayment").modal("show")
    $("#modalPayment").find("#searchInvoiceForm input[name=control]").val("nego")
    $("#searchInvoiceTable").attr('data-invoicePayment',$(this).val())
});

$("#searchInvoiceForm").on('submit',function(e){
    e.preventDefault()
    let control = $("#searchInvoiceForm").find("input[name=control]").val()
    BaseModel.disabledProperties('searchInvoiceForm',true)
    $.ajax({
        url: $(this).attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        beforeSend:function(){
            return $("#searchInvoiceForm").find('button').html(`<span class="spinner-border spinner-border-sm"></span>`)
        }
    }).done(function(data){
        console.log(data);
        $("#searchInvoiceForm")[0].reset()
        toasMessage('Status: Good',"success")
        BaseModel.disabledProperties('searchInvoiceForm',false)
        searchInvoiceTable(data,control)
        $("#searchInvoiceForm").find('button').html(`Submit`)
    }).fail(function (jqxHR, textStatus, errorThrown) {
        BaseModel.disabledProperties('searchInvoiceForm',false)
        $("#searchInvoiceForm").find('button').html(`Submit`)
        toasMessage(data.msg,jqxHR,"danger")
    })
})

$("#searchInvoiceTable").DataTable()

const searchInvoiceTable = (data,control) =>{
    $("#searchInvoiceTable").DataTable({
        searching:false,
        ordering:false,
        destroy:true,
        data:data,
        columns:[
            { data:'invoiceno' },
            { data:'qtymt' },
            { data:'description' },
            {
                data:null,
                render:function(data){
                    if (control=="nego") {
                        return `<button value="${data.invoiceno}" type="submit" name="addInvoiceContract" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i></button>`
                    } else {
                        return `<button value="${data.invoiceno}" type="submit" name="addInvoiceFreight" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i></button>`
                    }
                }
            }
        ]
    })
}

$(document).on('click','button[name=addInvoiceContract]',function(data){
    $.ajax({
        url:  shipTbl.attr("data-invoice"),
        type: 'POST',
        data: {
            _token:BaseModel._token,
            invoice_payment: $("#searchInvoiceTable").attr("data-invoicePayment"),
            invoiceno:$(this).val()
        }
    }).done(function(data){
        if (data.msg) {
            invPayDetailForm[0].reset()
            invoicePayment(shipPay.find("input[name=contract_payment]").val())
            toasMessage(data.msg,"success")
            $("#modalPayment").modal("hide")
            $("#searchInvoiceTable").DataTable()
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})