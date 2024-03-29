
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
    // modal
    modalFreight                : $("#modalFreight"),
    modalPaymentDetail          : $("#modalPaymentDetail"),
    modalInvoicePayment         : $("#modalInvoicePayment"),
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
            ConPay.contractForm.find("select[name=description]").append(new Option(data.description, null, true, true)).trigger('change');
        ConPay.contractForm.find("select[name=suppliername]").append(new Option(data.suppliername, null, true, true)).trigger('change');
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
    language: {
        'loadingRecords': '&nbsp;',
        'processing': 'Loading...'
    },  
    // order: [[0, 'desc']],
    columns:[
        {
            className: 'dt-control',
            orderable: false,
            data: null,
            defaultContent: '',
        },
        { 
            data:null,
            render:function(data){
                let checker = data.invoice_payment.every(v => v.invoiceno!=null);
                return BaseModel.dropdown([
                    {
                        text:'View / Initial Payment',
                        name:'paymentView',
                        icon:'<i class="far fa-eye"></i>',
                        elementType:'button',
                        id:data.amountUSD,
                        value:data.id,
                        disabled: (parseInt(data.contract_percent)==0)
                    },
                    {
                        text:'Payment',
                        name:'shipmentPayment',
                        icon:'<i class="fas fa-tag"></i>',
                        elementType:'button',
                        id:data.amountUSD,
                        value:data.id
                    },
                    {
                        text:'Edit',
                        name:'editContract',
                        icon:'<i class="fas fa-edit"></i>',
                        elementType:'button',
                        id:data.amountUSD,
                        value:data.id,
                        disabled: (checker && (data.invoice_payment.length>0 || data.invoice_payment.length<0))
                    },
                    {
                        text:'',
                        name:'',
                        icon:'',
                        elementType:'button',
                        disabled: (checker && (data.invoice_payment.length>0 || data.invoice_payment.length<0))
                    },
                    {
                        text:'Remove',
                        name:'removeContract',
                        icon:'<i class="fas fa-eraser"></i>',
                        elementType:'button',
                        id:data.amountUSD,
                        value:data.id,
                        disabled: (checker && (data.invoice_payment.length>0 || data.invoice_payment.length<0))
                    },

                ])
            }
        },
        { data:'suppliername' },
        { data:'description' },
        { data:'reference' },
        { data:'metricTon' },
        { data:'priceMetricTon' },
        { data:'paidAmountUSD' },
        { 
            data:null,
            render:function(data){
               if (data.contract_percent!=0) {
                let per = Math.round(data.payment_detail.reduce((total,val)=>total+=parseFloat(val.totalPercentPayment),0))
                return (per==Math.round(data.contract_percent))
                    ?   per+" / "+Math.round(data.contract_percent)+'<i class="ml-2 fas fa-check-circle text-success"></i>'
                    :   per+" / "+Math.round(data.contract_percent)+'<i class="fas fa-exclamation-triangle text-warning"></i>';
               }
               return ''
            }
        },
        {
            data:null,
            render:function(data){
                if (data.invoice_payment.length>0) {
                    let hold=`<table class="table table-bordered" style="width:100%;font-size:11px">
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>Reference</th>
                                    <th>Invoice</th>
                                    <th>MT</th>                                        
                                    <th>Price MT (USD)</th>
                                    <th>Amount (USD)</th>
                                </tr>`;
                        data.invoice_payment.forEach((val,i)=>{
                            // <td>${val.invoiceno!=null?`<a target="_blank" href="#">${val.invoiceno}</a>`:''}</td>
                            hold+=` <tr class="text-center">
                                        <td>${++i}</td>
                                        <td>${val.reference}</td>
                                        <td>${val.invoiceno!=null?val.invoiceno:''}</td>
                                        <th>${$.number(val.metricTon,true)}</th>                                            
                                        <th>${$.number(val.priceMetricTon,true)}</th>
                                        <th>${$.number(val.amountUSD,true)}</th>
                                    </tr>`
                        })

                        return hold;
                } else {
                    return '<em>No Invoice Available</em>'
                }
          
            }
        },
       
    ]
})

/**
 * 
 *  REMOVE
 * 
 */

$(document).on('click','button[name=removeContract]',function(e){
    e.preventDefault()
    let id = this.value
    alertify.confirm("Are you sure you want remove this item? This process can't be undone.",function(evnt){
        if (evnt.isTrusted) {
            $.ajax({
                url:  ConPay.contractTable.attr("data-remove").replace("cp",id),
                type: 'DELETE',
                data: {
                    _token:BaseModel._token,
                }
            }).done(function(data){
                if (data.msg) {
                    tableCon.ajax.reload()
                    toasMessage(data.msg,"success")
                }
            }).fail(function (jqxHR, textStatus, errorThrown) {
                toasMessage(data.msg,jqxHR,"danger")
            })
        }

        return false;
    })
   
})

/**
 * 
 * edit contract
 * 
 */

$(document).on('click','button[name=editContract]',function(e){
    e.preventDefault()
    console.log($(this).attr("id"));
    const data = tableCon.row( $(this).closest('tr') ).data()
    console.log(data);

    $.each($('#contractForm .form-control'),(ind, value) => {
        console.log(data[value.name]);
        // console.log(data[value.name]);
        ConPay.contractForm.find("input[name="+value.name+"]").val(data[value.name]);
        ConPay.contractForm.find("input[name=paidAmountUSD]").prop('disabled',data.payment_detail.length>0)
        ConPay.contractForm.find("input[name=contract_percent]").prop('disabled',data.payment_detail.length>0)
        ConPay.contractForm.find("select[name=description]").append(new Option(data.description, data.description, true, true)).trigger('change');
        ConPay.contractForm.find("select[name=suppliername]").append(new Option(data.suppliername, data.suppliername, true, true)).trigger('change');
    });
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

let cancelpaymentDetail = ConPay.paymentDetailTable.find("button[name=cancelButton]")
let paymentDetailTable;
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
    paymentDetailTable = ConPay.paymentDetailTable.DataTable({
        serverSide:true,
        paging:false,
        searching:false,
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

$(document).on('click','button[name=editPaymentDetail]',function(){
    cancelpaymentDetail.show()
    let data = paymentDetailTable.row( $(this).closest('tr') ).data()
    $.each($('#paymentDetailTable .form-control'),(ind, value) => {
        ConPay.paymentDetailTable.find("input[name="+value.name+"]").val(data[value.name])
    });
    $("input[name=id]").val($(this).val())
})

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

$('select[name="description"]').select2({
    allowClear:true,
    placeholder: 'Select Item',
    tags:true,
    ajax: {
        url: $('select[name="description"]').attr("id"),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.description.toUpperCase(),
                        id: item.description.toUpperCase(),
                    }
                })
            };
        },
        cache: true
    }
}).on('select2:close', function(){
    var element = $(this);
    var new_category = $.trim(element.val());
    element.append('<option value="'+new_category+'">'+new_category+'</option>').val(new_category);
});


$('select[name="suppliername"]').select2({
    allowClear:true,
    placeholder: 'Select Item',
    tags:true,
    ajax: {
        url: $('select[name="suppliername"]').attr("id"),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.suppliername,
                        id: item.suppliername,
                    }
                })
            };
        },
        cache: true
    }
}).on('select2:close', function(){
    var element = $(this);
    var new_category = $.trim(element.val());
    element.append('<option value="'+new_category+'">'+new_category+'</option>').val(new_category);
});


