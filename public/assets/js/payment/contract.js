$('.amount-class').number( true, 4 );
const ConPay = {
    
    freightTable        : $("#freightTable"),
    contractTable       : $("#contractTable"),
    paymentDetailTable  : $("#paymentDetailTable"),
    invoiceTable        : $("#invoiceTable"),
    //form
    freightForm         : $("#freightForm"),
    contractForm        : $("#contractForm"),
    paymentDetailForm   : $("#paymentDetailForm"),
    // modal
    modalFreight        : $("#modalFreight"),
    modalPaymentDetail  : $("#modalPaymentDetail"),
    modalInvoice        : $("#modalInvoice"),
    //function
    computeTotal  : ()=>{
        return parseFloat(ConPay.contractForm.find("input[name=totalmt]").val())*parseFloat(ConPay.contractForm.find("input[name=mtprice]").val())
    }
}


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
        { data:'suppliername' },
        { data:'reference' },
        { data:'totalmt' },
        { data:'mtprice' },
        { data:'totalprice' },
        { 
            data:null,
            render:function(data){
                return `<button
                           name="paymentView"
                           value="${data.id}"
                           class="btn btn-outline-secondary btn-sm">
                           <i class="fas fa-plus-circle"></i> Payment / View
                        </button>
                        <button
                            name="searchInvoice"
                            value="${data}"
                            type="button"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-plus-circle"></i> Invoice
                        </button>`
            }
        },
    ]
})

$(document).on('click','button[name=paymentView]',function(e){
    e.preventDefault()
    ConPay.modalPaymentDetail.modal("show")
    const data = tableCon.row( $(this).closest('tr') ).data()
    ConPay.paymentDetailForm[0].reset()
    ConPay.paymentDetailForm.find('input[name=id]').val('')
    $('.suppliername').text(data.suppliername)
    $('.totalMetricTon').text(data.totalmt)
    $('.reference').text(data.reference)
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
            { data:'metricTon' },
            { data:'exchangeRate' },
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
        ]
    })
}


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
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})


$(document).on('click','button[name=editPaymentDetail]',function(){
    console.log($(this).closest('tr').find('td')[0].innerText);
    let tdd = $(this).closest('tr')
    $("input[name=id]").val($(this).val())
    $("input[name=exchangeDate]").val(tdd.find('td')[0].innerText)
    $("input[name=metricTon]").val(tdd.find('td')[1].innerText)
    $("input[name=exchangeRate]").val(tdd.find('td')[2].innerText)
})

//search invoice
$(document).on('click','button[name=searchInvoice]',function(){
    ConPay.modalInvoice.modal("show")
})

ConPay.invoiceTable.DataTable({
    // serverSide: true,
    paging:false,
    ordering:false,
    // createdRow:function( row, data, dataIndex){
    //     if (data.res.posted_at!=null) {
    //         $(row).find("td").addClass('highlight');
    //     }
    // },
    // "ajax": {
    //     url: "details/list", 
    //     method: "get"
    // },
    // columns:[
    //     {
    //         orderable:false,
    //         searchable: false,
    //         data:null,
    //         render:function(data){
    //             if (data.res.posted_at==null) {
    //                return `<input type="checkbox" class="form-check" value="${data["id"]}" ${BaseModel.findPrev('posting') ? '':'disabled'}>`
    //             }else{
    //                 return '<i class="fas fa-check-circle text-secondary" style="font-size:13px"></i>'
    //             }
    //         }
    //     },
    //     {
    //         data: "updated_at",
    //         // target: 0,
    //         visible: false,
    //         searchable: false
    //     },
    //     {
    //         orderable: false,
    //         data:"pono"
    //     },
    //     {
    //         orderable: false,
    //         data:null,
    //         render:function(data){
    //             return (data["vessel"]!="null")?data["vessel"]:""
    //         }
    //     },
    //     {
    //         orderable: false,
    //         data:"description"
    //     },
    //     {
    //         orderable: false,
    //         data:"invoiceno"
    //     },
    //     {
    //         orderable: false,
    //         data:"blno"
    //     },
    //     {
    //         orderable: false,
    //         data:null,
    //         render:function(data){
    //             return (data["broker"]!="null")?data["broker"]:""
    //         }
    //     },
    //     {
    //         orderable: false,
    //         data:"quantity"
    //     },
    //     {
    //         orderable: false,
    //         data:"qtykls"
    //     },
    //     {
    //         orderable: false,
    //         data:null,
    //         render:function(data){
    //             return data.qtymt.toFixed(2)
    //         }
    //     },
    //     {
    //         orderable: false,
    //         data:"fcl"
    //     },
    //     {
    //         orderable: false,
    //         data:null,
    //         render:function(data){
    //             return `<div class="btn-group btn-group-sm" role="group">
    //                         <button type="button" class="btn btn-primary btn-sm dropdown-toggle" style="font-size:11px" data-toggle="dropdown" aria-expanded="false">
    //                             Action
    //                         </button>
    //                         <div class="dropdown-menu" style="font-size:11px">
    //                             <a href="details/cost/${data["id"]}" class="dropdown-item"><i class="fas fa-project-diagram"></i> Particular</a>
    //                             ${
    //                                 BaseModel.findPrev('LC004')?`
    //                                 <a class="dropdown-item border" id="print"  style="cursor:pointer" value="${data["id"]}"><i class="fas fa-print"></i> Print</a>
    //                                 `:``
    //                             }
    //                             ${
    //                                 BaseModel.findPrev('LC005')?`
    //                                 <a class="dropdown-item border" id="posting" style="cursor:pointer; display:${ (data.res.posted_at!=null && !BaseModel.findPrev('LCOO6'))? 'none' :'' }"
    //                                  value="${data.res.id}"
    //                                  data-title="${data.res.invoiceno}"
    //                                  data-post="${data.res.posted_at}">
    //                                  ${data.res.posted_at!=null?'<i class="fas fa-check-circle"></i>&nbsp;Unpost':'<i class="far fa-check-circle"></i>&nbsp;Post'}
    //                                 </a>
                                    
    //                                 `:``
    //                             }
    //                             <a class="dropdown-item"  aria-expanded="false" style="cursor:pointer"><i class="fas fa-times"></i> Close Action</a>
                               
    //                         </div>
    //                     </div>`
    //         }
    //     },
    // ]
})
