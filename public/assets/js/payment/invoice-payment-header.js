//payment Invoice

let shipmentContractMT;
let invoicePaymentDetailTable;

$(document).on('click','button[name=shipmentPayment]',function(){
    $("#shipmentContractMT").find("input[name=contract_payment]").val($(this).val())
    ConPay.modalInvoice.modal("show")
    invoicePayment($(this).val())
    $(".selectedInvoicePaymentReference").text('')
    $('#invoicePaymentDetailTable').DataTable().clear().destroy();

})


const invoicePayment = (contract_payment) =>{

    shipmentContractMT = $("#shipmentContractMT").DataTable({
        serverSide: true,
        ordering:false,
        paging:false,
        destroy:true,
        "ajax": {
            url: $("#shipmentContractMT").attr("data-url").replace("cp",contract_payment),
            method: "get"
        },
        // order: [[0, 'desc']],
        columns:[
            { data:'reference' },
            { data:'metricTon' },
            { data:'priceMetricTon' },
            { data:'amountUSD' },
            { 
                data:null,
                render:function(data){
                    return `<button type="button" name="invoicePaymentDetailBtn" data-json="${data}" value="${data.id}" class="btn btn-sm btn-outline-primary btn-block">View Payment</button>`
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
                
            
                
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 1 ).footer() ).html($.number(monTotal,2));
            $( api.column( 2 ).footer() ).html('Avg: '+$.number(tueTotal,2));
            $( api.column( 3 ).footer() ).html($.number(wedTotal,2));
        },
        
    })
}

$("#shipmentPaymentForm").find("input[name=metricTon]").on('input',function(){
    $("#shipmentPaymentForm").find("input[name=amountUSD]").val(this.value*$("#shipmentPaymentForm").find("input[name=priceMetricTon]").val())
})

$("#shipmentPaymentForm").find("input[name=priceMetricTon]").on('input',function(){
    $("#shipmentPaymentForm").find("input[name=amountUSD]").val(this.value*$("#shipmentPaymentForm").find("input[name=metricTon]").val())
})

$("#shipmentPaymentForm").on("submit",function(e){
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
            $("#shipmentPaymentForm")[0].reset()
            invoicePayment($("#shipmentPaymentForm").find("input[name=contract_payment]").val())
            toasMessage(data.msg,"success")
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})


$(document).on('click','button[name=invoicePaymentDetailBtn]',function(){
    let data = shipmentContractMT.row( $(this).closest('tr') ).data()
    $("#invoicePaymentDetailForm").find("input[name=invoice_payment]").val($(this).val())
    $("#invoicePaymentDetailForm").find("input[name=invoice_amountUSD]").val(data.amountUSD)
    $(".selectedInvoicePaymentReference").text(data.reference)
    invoicePaymentDetail(data.id)
})

$("#invoicePaymentDetailForm").find("input[name=dollar]").on('input',function(){
    $("#invoicePaymentDetailForm").find("input[name=totalAmountInPHP]").val(this.value*$("#invoicePaymentDetailForm").find("input[name=exchangeRate]").val())
})

$("#invoicePaymentDetailForm").find("input[name=exchangeRate]").on('input',function(){
    $("#invoicePaymentDetailForm").find("input[name=totalAmountInPHP]").val(this.value*$("#invoicePaymentDetailForm").find("input[name=dollar]").val())
})

$("#invoicePaymentDetailForm").find('input[name=exchangeRate]').on('input',function(){
    $("#invoicePaymentDetailForm").find('input[name=totalAmountInPHP]').val(this.value*$("#invoicePaymentDetailForm").find('input[name=dollar]').val())
    $("#invoicePaymentDetailForm").find('input[name=totalPercentPayment]').val(
        // $("#invoicePaymentDetailForm").find('input[name=dollar]').val()
        )
})


$("#invoicePaymentDetailForm").find('input[name=exchangeRate]').on('input',function(){
    $("#invoicePaymentDetailForm").find('input[name=totalAmountInPHP]').val(this.value*$("#invoicePaymentDetailForm").find('input[name=dollar]').val())
    $("#invoicePaymentDetailForm").find('input[name=totalPercentPayment]').val(
        ($("#invoicePaymentDetailForm").find('input[name=dollar]').val()/parseFloat($("#invoicePaymentDetailForm").find("input[name=invoice_amountUSD]").val()))*100
    )
})
$("#invoicePaymentDetailForm").find('input[name=dollar]').on('input',function(){
    $("#invoicePaymentDetailForm").find('input[name=totalAmountInPHP]').val(this.value*$("#invoicePaymentDetailForm").find('input[name=exchangeRate]').val())
    $("#invoicePaymentDetailForm").find('input[name=totalPercentPayment]').val(
        ($("#invoicePaymentDetailForm").find('input[name=dollar]').val()/parseFloat($("#invoicePaymentDetailForm").find("input[name=invoice_amountUSD]").val()))*100
    )

})

$("#invoicePaymentDetailForm").on("submit",function(e){
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
            $("#invoicePaymentDetailForm")[0].reset()
            invoicePaymentDetail($("#invoicePaymentDetailForm").find("input[name=invoice_payment]").val())
            toasMessage(data.msg,"success")
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})


const invoicePaymentDetail = (invoice_payment) =>{

    let invoicePaymentDetailTable = $("#invoicePaymentDetailTable").DataTable({
        serverSide: true,
        ordering:false,
        paging:false,
        destroy:true,
        "ajax": {
            url: $("#invoicePaymentDetailTable").attr("data-url").replace("ip",invoice_payment),
            method: "get"
        },
        // order: [[0, 'desc']],
        columns:[
            { data:'exchangeDate' },
            { data:'dollar' },
            { data:'exchangeRate' },
            { data:'totalAmountInPHP' },
            { data:'totalPercentPayment' },
            { 
                data:null,
                render:function(data){
                    return `<button
                               name="editPaymentDetail"
                               value="${data.payment_detail.id}"
                               type="button"
                               class="btn btn-outline-secondary btn-sm">
                               <i class="fas fa-plus-circle"></i> Edit
                            </button>
                           `
                }
            },
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

const getPercentInvoicePaymentDetail = () =>{

}