$('.amount-class').number( true, 4 );
const ConPay = {
    
    freightTable                : $("#freightTable"),
    contractTable               : $("#contractTable"),
    paymentDetailTable          : $("#paymentDetailTable"),
    invoiceTable                : $("#invoiceTable"),
    //form
    freightForm                 : $("#freightForm"),
    contractForm                : $("#contractForm"),
    paymentDetailForm           : $("#paymentDetailForm"),
    seachInvoiceUnderPaymentForm: $("#seachInvoiceUnderPaymentForm"),
    // modal
    modalFreight                : $("#modalFreight"),
    modalPaymentDetail          : $("#modalPaymentDetail"),
    modalInvoice                : $("#modalInvoice"),
    //function
    computeTotal  : ()=>{
        return parseFloat(ConPay.contractForm.find("input[name=totalmt]").val())*parseFloat(ConPay.contractForm.find("input[name=mtprice]").val())
    },

}

let mt              = ConPay.contractForm.find("input[name=metricTon]")
let pmt             = ConPay.contractForm.find("input[name=priceMetricTon]")
let amntUSD         = ConPay.contractForm.find("input[name=amountUSD]")
let contract_percent= ConPay.contractForm.find("input[name=contract_percent]")
let exchangeRate    = ConPay.contractForm.find("input[name=exchangeRate]")
let amountPHP       = ConPay.contractForm.find("input[name=amountPHP]")
let paidAmountUSD   = ConPay.contractForm.find("input[name=paidAmountUSD]")


const getTotalAmountUSD = () =>{

    if ((mt.val()!="" || mt.val()!=0 ) && (pmt.val()!="" || pmt.val()!=0)) {
        amntUSD.val(pmt.val() * mt.val())
    } else {
        // amntUSD.val(0)
        // contract_percent.val(0)
    }

}

const getAmount = () =>{

    if ((mt.val()!="" || mt.val()!=0 ) && (pmt.val()!="" || pmt.val()!=0) && (contract_percent.val()!="" || contract_percent.val()!=0)) {

        paidAmountUSD.val((amntUSD.val()*contract_percent.val())/100)

    }else{
        paidAmountUSD.val(0)
        contract_percent.val(0)
        // alert("Unable to Calculate Amount!");
    }

}

const getcontract_percent = () =>{

    if ((mt.val()!="" || mt.val()!=0 ) && (pmt.val()!="" || pmt.val()!=0) && (paidAmountUSD.val()!="" || paidAmountUSD.val()!=0)) {
        perc = ((paidAmountUSD.val()/amntUSD.val())*100);
        if ((parseInt(paidAmountUSD.val()) <= parseInt(amntUSD.val())) && parseInt(perc)<=100) {
            contract_percent.val((perc))
        }else{
            paidAmountUSD.val(0)
            contract_percent.val(0)
        }

    }else{
        paidAmountUSD.val(0)
        contract_percent.val(0)
        // alert("Unable to Calculate contract_percent!");   
    }

}

const getAmountPHP = () =>{

    amountPHP.val(
            ((paidAmountUSD.val()!="" || parseInt(paidAmountUSD.val())!=0 ) && (exchangeRate.val()!="" || parseInt(exchangeRate.val())!=0)) ? (paidAmountUSD.val()*exchangeRate.val()) : 0
        )
}

paidAmountUSD.on('keyup',function(){
    getcontract_percent()
})

mt.on('keyup',function(){
    getcontract_percent()
    getAmount()
    getTotalAmountUSD()
    getAmountPHP()
})
pmt.on('keyup',function(){
    getcontract_percent()
    getAmount()
    getTotalAmountUSD()
    getAmountPHP()
})

contract_percent.on('keyup',function(){
    if(contract_percent.val()<=100){
        getAmount() 
        getAmountPHP()
    } else {
        $(this).val(0)
    }
})



ConPay.contractForm.find("input[name=totalmt]").on('input',function(){
    ConPay.contractForm.find("input[name=totalprice]").val(($(this).val()!=0 || $(this).val()!="")?ConPay.computeTotal():0)
})

ConPay.contractForm.find("input[name=mtprice]").on('input',function(){
    ConPay.contractForm.find("input[name=totalprice]").val(($(this).val()!=0 || $(this).val()!="")?ConPay.computeTotal():0)
})

ConPay.contractForm.on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:  ConPay.contractForm.attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        if (data.msg) {
            ConPay.contractForm[0].reset()
            ConPay.contractForm.find('input[name=id]').val('')
            toasMessage(data.msg,"success")
            tableCon.ajax.reload()
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})

let tableCon = ConPay.contractTable.DataTable({
    "serverSide": true,
    ordering:false,
    paging:true,
    "ajax": {
        url: ConPay.contractTable.attr("data-url"),
        method: "get"
    },
    // order: [[0, 'desc']],
    columns:[
        {
            className: 'dt-control',
            orderable: false,
            data: null,
            defaultContent: '',
        },
        { data:'suppliername' },
        { data:'description' },
        { data:'reference' },
        { data:'metricTon' },
        { data:'priceMetricTon' },
        { 
            data:null,
            render:function(data){
                let per = Math.round(data.payment_detail.reduce((total,val)=>total+=parseFloat(val.totalPercentPayment),0))
                return (per==data.contract_percent)
                    ?   data.contract_percent+" / "+per+'<i class="ml-2 fas fa-check-circle text-success"></i>'
                    :   data.contract_percent+" / "+per;

            }
        },
        { 
            data:null,
            render:function(data){
                return BaseModel.dropdown([
                    {
                        text:'View / Initial Payment',
                        name:'paymentView',
                        icon:'<i class="far fa-eye"></i>',
                        elementType:'button',
                        id:data.amountUSD,
                        value:data.id
                    },
                    {
                        text:'Payment',
                        name:'shipmentPayment',
                        icon:'<i class="fas fa-tag"></i>',
                        elementType:'button',
                        id:data.amountUSD,
                        value:data.id
                    },
                ])
            }
        },
    ]
})


/****
 * 
 * 
 * 
 * VIEW INITIAL PAYMENT
 * 
 * 
 * 
 * 
 */

let cancelpaymentDetail = ConPay.paymentDetailTable.find("button[name=cancelpaymentDetail]")

cancelpaymentDetail.on('click',function(){
    ConPay.paymentDetailForm[0].reset()
    ConPay.paymentDetailForm.find('input[name=id]').val('')
    $(this).hide()
}).hide()

const detailView = (data) =>{
    $('.suppliername').text(data.suppliername)
    $('.totalMetricTon').text($.number(data.metricTon))
    $('.initialContractPayment').text($.number(data.paidAmountUSD))
    $('.initialContractPercent').text($.number(data.contract_percent))
    $('.totalAmountUSD').text($.number(data.amountUSD))
    $('.priceMetricTon').text($.number(data.priceMetricTon))
    $('.reference').text(data.reference)
}

$(document).on('click','button[name=paymentView]',function(e){
    e.preventDefault()
    console.log($(this).attr("id"));
    ConPay.modalPaymentDetail.modal("show")
    const data = tableCon.row( $(this).closest('tr') ).data()
    ConPay.paymentDetailForm[0].reset()
    ConPay.paymentDetailForm.find('input[name=id]').val('')
    detailView(data)
    ConPay.paymentDetailForm.find("input[name=contract_payment]").val($(this).val())
    initialize($(this).val())
})


const initialize = (id) =>{
    ConPay.paymentDetailTable.DataTable({
        serverSide:true,
        paging:false,
        destroy:true,
        ordering:false,
        "ajax": {
            url:ConPay.paymentDetailTable.attr("data-url").replace('cp',id),
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


ConPay.paymentDetailTable.find('input[name=exchangeRate]').on('input',function(){
    ConPay.paymentDetailTable.find('input[name=totalAmountInPHP]').val(this.value*ConPay.paymentDetailTable.find('input[name=dollar]').val())
    ConPay.paymentDetailTable.find('input[name=totalPercentPayment]').val(
        ConPay.paymentDetailTable.find('input[name=dollar]').val()/$('.totalAmountUSD').text().replaceAll(",","")*100
    )
})
ConPay.paymentDetailTable.find('input[name=dollar]').on('input',function(){
    ConPay.paymentDetailTable.find('input[name=totalAmountInPHP]').val(this.value*ConPay.paymentDetailTable.find('input[name=exchangeRate]').val())
    ConPay.paymentDetailTable.find('input[name=totalPercentPayment]').val(
        ConPay.paymentDetailTable.find('input[name=dollar]').val()/$('.totalAmountUSD').text().replaceAll(",","")*100
    )
})

ConPay.paymentDetailForm.on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:  ConPay.paymentDetailForm.attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        if (data.msg) {
            ConPay.paymentDetailForm[0].reset()
            ConPay.paymentDetailForm.find('input[name=id]').val('')
            toasMessage(data.msg,"success")
            initialize(ConPay.paymentDetailForm.find("input[name=contract_payment]").val())
            tableCon.ajax.reload()
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})

ConPay.freightTable.DataTable({
    
})
